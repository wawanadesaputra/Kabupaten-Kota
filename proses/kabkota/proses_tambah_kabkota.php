<?php
include("../../api/conf/db_conn.php");

const TARGET_DIR = "../../image/logo/";
const ALLOWED_EXT = array('png', 'jpg', 'jpeg', 'gif');
const MAX_FILE_SIZE = 512000; // 512KB

function checkImage($image_input_name, $db_connection)
{
    if (!isset($_FILES[$image_input_name]) || $_FILES[$image_input_name]['error'] == UPLOAD_ERR_NO_FILE) {
        return "Tidak ada file yang diupload.";
    }

    $filename = $_FILES[$image_input_name]['name'];
    $ukuran = $_FILES[$image_input_name]['size'];
    $tmp_file = $_FILES[$image_input_name]['tmp_name'];
    $error_code = $_FILES[$image_input_name]['error'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $target_file = TARGET_DIR . basename($filename);

    if ($error_code != UPLOAD_ERR_OK) {
        return "Terjadi error saat upload file. Kode: $error_code";
    }

    $image_info = @getimagesize($tmp_file);
    if (!$image_info) {
        return "File bukan gambar valid.";
    }

    if (!in_array(strtolower($ext), ALLOWED_EXT)) {
        return "Ekstensi file tidak diperbolehkan. Hanya " . implode(', ', ALLOWED_EXT);
    }

    if (file_exists($target_file)) {
        return "Nama file sudah ada di server. Ganti nama file!";
    }

    if (!($db_connection instanceof mysqli)) {
        return "Koneksi database tidak valid.";
    }

    $stmt_check_db = $db_connection->prepare("SELECT COUNT(*) FROM tb_kab_kota WHERE logo = ?");
    if (!$stmt_check_db) {
        return "Kesalahan database: " . $db_connection->error;
    }

    $stmt_check_db->bind_param("s", $filename);
    $stmt_check_db->execute();
    $stmt_check_db->bind_result($count);
    $stmt_check_db->fetch();
    $stmt_check_db->close();

    if ($count > 0) {
        return "Nama file logo sudah digunakan di database. Gunakan nama file lain.";
    }

    if ($ukuran > MAX_FILE_SIZE) {
        return "Ukuran file melebihi batas " . (MAX_FILE_SIZE / 1000) . "KB.";
    }

    if (move_uploaded_file($tmp_file, $target_file)) {
        return "OK";
    } else {
        return "Gagal memindahkan file. Pastikan folder '" . TARGET_DIR . "' memiliki izin tulis.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kabupaten_kota = $_POST['kabupaten_kota'] ?? '';
    $pusat_pemerintahan = $_POST['pusat_pemerintahan'] ?? '';
    $bupati_walikota = $_POST['bupati_walikota'] ?? '';
    $tanggal_berdiri = $_POST['tanggal_berdiri'] ?? '';
    $luas_wilayah = $_POST['luas_wilayah'] ?? 0;
    $jumlah_penduduk = $_POST['jumlah_penduduk'] ?? 0;
    $jumlah_kecamatan = $_POST['jumlah_kecamatan'] ?? 0;
    $jumlah_kelurahan = $_POST['jumlah_kelurahan'] ?? 0;
    $jumlah_desa = $_POST['jumlah_desa'] ?? 0;
    $url_peta_wilayah = $_POST['url_peta_wilayah'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $filename = $_FILES['logo']['name'] ?? '';

    $upload_check_result = checkImage('logo', $conn);

    if ($upload_check_result != 'OK') {
        echo "<script>alert('" . addslashes($upload_check_result) . "');</script>";
        echo "<script>window.location = '../../index.php?page=tambah_kabkota';</script>";
    } else {
        $query = "INSERT INTO tb_kab_kota SET
            kabupaten_kota = ?,
            pusat_pemerintahan = ?,
            bupati_walikota = ?,
            tanggal_berdiri = ?,
            luas_wilayah = ?,
            jumlah_penduduk = ?,
            jumlah_kecamatan = ?,
            jumlah_kelurahan = ?,
            jumlah_desa = ?,
            url_peta_wilayah = ?,
            deskripsi = ?,
            logo = ?";

        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "sssssdiiisss",
                $kabupaten_kota,
                $pusat_pemerintahan,
                $bupati_walikota,
                $tanggal_berdiri,
                $luas_wilayah,
                $jumlah_penduduk,
                $jumlah_kecamatan,
                $jumlah_kelurahan,
                $jumlah_desa,
                $url_peta_wilayah,
                $deskripsi,
                $filename
            );

            $execute_result = mysqli_stmt_execute($stmt);

            if ($execute_result) {
                echo "<script>alert('Berhasil menambahkan data $kabupaten_kota!');</script>";
                echo "<script>window.location = '../../index.php?page=data_kabkota';</script>";
            } else {
                echo "<script>alert('Gagal menambahkan data $kabupaten_kota, coba cek isian Anda!');</script>";
                echo "<script>window.location = '../../index.php?page=tambah_kabkota';</script>";
            }
        } else {
            echo "<script>alert('Gagal menyiapkan statement database.');</script>";
            echo "<script>window.location = '../../index.php?page=tambah_kabkota';</script>";
        }
    }
}
