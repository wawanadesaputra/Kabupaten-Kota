<?php
session_start();
include("../../api/conf/db_conn.php");

$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, md5($_POST['password']));

$sql_query = "SELECT * FROM tb_user WHERE email = '$email' AND password = '$password'";
$result = mysqli_query($conn, $sql_query);
$row = mysqli_fetch_array($result);

if ($row) {
    $_SESSION['email'] = $row['email'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['role'] = $row['role'];
    $_SESSION['login_success'] = "Selamat {$row['username']}, Anda berhasil login!";

    header("Location: ../../index.php?page=beranda");
    exit();
} else {
    $_SESSION['login_error'] = "Email atau password salah!";
    header("Location: ../../pages/user/login.php");
    exit();
}
