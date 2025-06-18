<?php
include("../../api/conf/db_conn.php");

const TARGET_DIR = "../../image/logo/";
const ALLOWED_EXT = array('png', 'jpg', 'jpeg', 'gif');
const MAX_FILE_SIZE = 512000;

/**
 * 
 * 
 *
 * @param string 
 * @param mysqli
 * @return string 
 */

function checkImage($image, $remove_image)
{
    $filename = $_FILES[$image]['name'];
    $ukuran = $_FILES[$image]['size'];
    $tmp_file = $_FILES[$image]['tmp_name'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $target_file = TARGET_DIR . basename($filename);

    if ($_FILES[$image]['error'] !== UPLOAD_ERR_OK) {
        return "Tidak ada file yang diupload atau error!";
    }

    $img_info = getimagesize($tmp_file);
    if (!$img_info) {
        return "File yang diupload bukan image!";
    }

    if (file_exists($target_file)) {
        return "File yang diupload sudah ada, silahkan ganti nama file!";
    }

    if ($ukuran > MAX_FILE_SIZE) {
        return "File yang diupload melebihi 512kb!";
    }

    if (!in_array($ext, ALLOWED_EXT)) {
        return "Ekstensi file yang diupload tidak diperbolehkan (upload hanya .png | .jpg | .jpeg | .gif!)";
    }

    if (move_uploaded_file($tmp_file, $target_file)) {
        if (!empty($remove_image)) {
            $old_image_path = TARGET_DIR . $remove_image;
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }
        }
        return "OK";
    } else {
        return "Gagal mengupload file! " . $target_file;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $kabupaten_kota = $_POST['kabupaten_kota'];
    $pusat_pemerintahan = $_POST['pusat_pemerintahan'];
    $bupati_walikota = $_POST['bupati_walikota'];
    $tanggal_berdiri = $_POST['tanggal_berdiri'];
    $luas_wilayah = $_POST['luas_wilayah'];
    $jumlah_penduduk = $_POST['jumlah_penduduk'];
    $jumlah_kecamatan = $_POST['jumlah_kecamatan'];
    $jumlah_kelurahan = $_POST['jumlah_kelurahan'];
    $jumlah_desa = $_POST['jumlah_desa'];
    $url_peta_wilayah = $_POST['url_peta_wilayah'];
    $deskripsi = $_POST['deskripsi'];
    $remove_image = $_POST['logo_lama'];
    $ubah_logo = isset($_POST['ubah_logo']) && $_POST['ubah_logo'] ? true : false;

    $filename = null;

    if ($ubah_logo) {
        $result = checkImage('logo', $remove_image);
        $filename = $_FILES['logo']['name'];

        if ($result != "OK") {
            echo "<script> alert('$result');</script>";
            echo "<script> window.location = '../../index.php?page=ubah_kabkota&id=$id';</script>";
            exit;
        }
    } else {
        $filename = $remove_image;
    }

    $query = "UPDATE tb_kab_kota SET
        kabupaten_kota = '$kabupaten_kota',
        pusat_pemerintahan = '$pusat_pemerintahan',
        bupati_walikota = '$bupati_walikota',
        tanggal_berdiri = '$tanggal_berdiri',
        luas_wilayah = '$luas_wilayah',
        jumlah_penduduk = '$jumlah_penduduk',
        jumlah_kecamatan = '$jumlah_kecamatan',
        jumlah_kelurahan = '$jumlah_kelurahan',
        jumlah_desa = '$jumlah_desa',
        url_peta_wilayah = '$url_peta_wilayah',
        deskripsi = '$deskripsi'";

    $query .= ", logo = '$filename'";

    $query .= " WHERE id='$id'";

    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script> alert('Berhasil mengubah data $kabupaten_kota!');</script>";
        echo "<script> window.location = '../../index.php?page=data_kabkota';</script>";
    } else {
        echo "<script> alert('Gagal mengubah data $kabupaten_kota, coba cek isian anda!');</script>";
        echo "<script> window.location = '../../index.php?page=ubah_kabkota&id=$id';</script>";
    }
}
