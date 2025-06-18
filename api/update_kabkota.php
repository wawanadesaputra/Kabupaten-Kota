<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Method: PUT');

include_once('conf/db_config.php');
include_once('model/kabkota.php');

$database = new Database;
$db = $database->connect();
$kabkota = new KabKota($db);

const TARGET_DIR = "../image/logo/";
const ALLOWED_EXT = ['png', 'jpg', 'jpeg', 'gif'];
const MAX_FILE_SIZE = 512000;

function checkImage($imageField, $removeImage)
{
    $filename = $_FILES[$imageField]['name'];
    $fileSize = $_FILES[$imageField]['size'];
    $tmpFile = $_FILES[$imageField]['tmp_name'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $targetFile = TARGET_DIR . basename($filename);

    if ($_FILES[$imageField]['error'] !== UPLOAD_ERR_OK) {
        return "Tidak ada file yang diupload atau terjadi error!";
    }

    $imageInfo = getimagesize($tmpFile);
    if (!$imageInfo) {
        return "File yang diupload bukan gambar!";
    }

    if (file_exists($targetFile)) {
        return "File sudah ada, silakan ganti nama file!";
    }

    if ($fileSize > MAX_FILE_SIZE) {
        return "Ukuran file melebihi batas 512KB!";
    }

    if (!in_array(strtolower($ext), ALLOWED_EXT)) {
        return "Ekstensi file tidak diperbolehkan (hanya png, jpg, jpeg, gif)!";
    }

    if (move_uploaded_file($tmpFile, $targetFile)) {
        if (!empty($removeImage)) {
            $removeFile = TARGET_DIR . $removeImage;
            if (file_exists($removeFile) && is_file($removeFile)) {
                unlink($removeFile);
            }
        }
        return "OK";
    } else {
        return "Gagal mengupload file ke server!";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $removeImage = $_POST['logo'] ?? '';
    $fileImageField = 'image';

    if (isset($_FILES[$fileImageField])) {
        $uploadResult = checkImage($fileImageField, $removeImage);
        if ($uploadResult !== "OK") {
            echo json_encode(['message' => $uploadResult, 'data' => null]);
            exit;
        }

        $logoBaru = $_FILES[$fileImageField]['name'];
    } else {
        $logoBaru = $removeImage;
    }

    $params = [
        'id' => $_POST['id'] ?? '',
        'kabupaten_kota' => $_POST['kabupaten_kota'] ?? '',
        'pusat_pemerintahan' => $_POST['pusat_pemerintahan'] ?? '',
        'bupati_walikota' => $_POST['bupati_walikota'] ?? '',
        'tanggal_berdiri' => $_POST['tanggal_berdiri'] ?? '',
        'luas_wilayah' => $_POST['luas_wilayah'] ?? '',
        'jumlah_penduduk' => $_POST['jumlah_penduduk'] ?? '',
        'jumlah_kecamatan' => $_POST['jumlah_kecamatan'] ?? '',
        'jumlah_kelurahan' => $_POST['jumlah_kelurahan'] ?? '',
        'jumlah_desa' => $_POST['jumlah_desa'] ?? '',
        'url_peta_wilayah' => $_POST['url_peta_wilayah'] ?? '',
        'deskripsi' => $_POST['deskripsi'] ?? '',
        'logo' => $logoBaru,
    ];

    if ($kabkota->updateKabKota($params)) {
        echo json_encode(['message' => 'Data kabupaten kota berhasil diupdate!', 'data' => $params]);
    } else {
        echo json_encode(['message' => 'Gagal mengupdate data!', 'data' => null]);
    }
} else {
    echo json_encode(['message' => 'Metode tidak diizinkan. Gunakan POST.', 'data' => null]);
}
