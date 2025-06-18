<?php
session_start();

// Hapus semua session
$_SESSION = [];

// Hapus cookie session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"] ?? false,
        $params["httponly"] ?? false
    );
}

// Hancurkan sesi
session_destroy();

// Arahkan ke halaman login
header("Location: ../../pages/user/login.php");
exit;
