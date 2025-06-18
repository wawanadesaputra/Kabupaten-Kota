<?php
include("../../api/conf/db_conn.php");
const TARGET_DIR = "../../../image/logo/";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id <= 0) {
        echo "<script>alert('ID tidak valid'); window.location='../../index.php?page=data_kabkota';</script>";
        exit;
    }

    $query = "SELECT * FROM tb_kab_kota WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $kabupaten_kota = $row['kabupaten_kota'];
        $logo = $row['logo'];
        $target_file = TARGET_DIR . $logo;

        if (!empty($logo) && file_exists($target_file)) {
            unlink($target_file);
        }

        $deleteQuery = "DELETE FROM tb_kab_kota WHERE id = '$id'";
        $deleteResult = mysqli_query($conn, $deleteQuery);

        if ($deleteResult) {
            echo "<script>alert('Berhasil menghapus data $kabupaten_kota.');</script>";
        } else {
            echo "<script>alert('Gagal menghapus data $kabupaten_kota, terjadi kesalahan pada query.');</script>";
        }
    } else {
        echo "<script>alert('Data tidak ditemukan.');</script>";
    }

    echo "<script>window.location = '../../index.php?page=data_kabkota';</script>";
}
