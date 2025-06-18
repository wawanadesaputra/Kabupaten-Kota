<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');

require_once('conf/db_config.php');
require_once('model/kabkota.php');

$database = new Database();
$db = $database->connect();
$kabkota = new KabKota($db);

define('TARGET_DIR', __DIR__ . '/../image/logo/');
const MAX_FILE_SIZE = 512000;
const ALLOWED_EXT = ['png', 'jpg', 'jpeg', 'gif'];

if (!is_dir(TARGET_DIR)) {
    mkdir(TARGET_DIR, 0755, true);
}

function handleUpload($imageField)
{
    if (!isset($_FILES[$imageField])) {
        return ['status' => false, 'message' => 'File tidak ditemukan dalam request.'];
    }

    $file = $_FILES[$imageField];
    $filename = basename($file['name']);
    $tmp = $file['tmp_name'];
    $size = $file['size'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['status' => false, 'message' => 'Gagal upload file.'];
    }

    if (!getimagesize($tmp)) {
        return ['status' => false, 'message' => 'File bukan gambar valid.'];
    }

    if ($size > MAX_FILE_SIZE) {
        return ['status' => false, 'message' => 'Ukuran file melebihi batas maksimum (512KB).'];
    }

    if (!in_array($ext, ALLOWED_EXT)) {
        return ['status' => false, 'message' => 'Format file tidak diperbolehkan.'];
    }

    $newFilename = uniqid('logo_', true) . '.' . $ext;
    $targetPath = TARGET_DIR . $newFilename;

    if (!move_uploaded_file($tmp, $targetPath)) {
        return ['status' => false, 'message' => 'Gagal menyimpan file ke server.'];
    }

    return ['status' => true, 'filename' => $newFilename];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['kabupaten_kota'])) {
        echo json_encode(['message' => 'Field "kabupaten_kota" wajib diisi.', 'data' => null]);
        exit;
    }

    $upload = handleUpload('image');
    if (!$upload['status']) {
        echo json_encode(['message' => $upload['message'], 'data' => null]);
        exit;
    }

    $params = [
        'kabupaten_kota' => trim($_POST['kabupaten_kota']),
        'pusat_pemerintahan' => trim($_POST['pusat_pemerintahan'] ?? ''),
        'bupati_walikota' => trim($_POST['bupati_walikota'] ?? ''),
        'tanggal_berdiri' => trim($_POST['tanggal_berdiri'] ?? ''),
        'luas_wilayah' => trim($_POST['luas_wilayah'] ?? ''),
        'jumlah_penduduk' => trim($_POST['jumlah_penduduk'] ?? ''),
        'jumlah_kecamatan' => trim($_POST['jumlah_kecamatan'] ?? ''),
        'jumlah_kelurahan' => trim($_POST['jumlah_kelurahan'] ?? ''),
        'jumlah_desa' => trim($_POST['jumlah_desa'] ?? ''),
        'url_peta_wilayah' => trim($_POST['url_peta_wilayah'] ?? ''),
        'deskripsi' => trim($_POST['deskripsi'] ?? ''),
        'logo' => $upload['filename']
    ];

    if ($kabkota->createKabKota($params)) {
        echo json_encode([
            'message' => 'Data kabupaten/kota berhasil ditambahkan.',
            'data' => $params
        ]);
    } else {
        echo json_encode([
            'message' => 'Gagal menyimpan data ke database.',
            'data' => null
        ]);
    }
} else {
    echo json_encode(['message' => 'Metode tidak diperbolehkan. Gunakan POST.', 'data' => null]);
}
