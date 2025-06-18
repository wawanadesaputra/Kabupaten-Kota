<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KabKota Login</title>

    <link rel="stylesheet" href="/kabupaten-kota/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/kabupaten-kota/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="/kabupaten-kota/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">

    <?php
    if (isset($_SESSION['login_error'])) {
        echo "<script>alert('{$_SESSION['login_error']}');</script>";
        unset($_SESSION['login_error']);
    }
    ?>

    <div class="login-box">
        <div class="login-logo">
            <a href="index.php"><b>KabKota</b>App</a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Silahkan login untuk mengelola data</p>

                <form action="../../proses/user/proses_login.php" method="post">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" id="email" name="email" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" id="password" name="password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Ingatkan saya
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="/kabupaten-kota/plugins/jquery/jquery.min.js"></script>
    <script src="/kabupaten-kota/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/kabupaten-kota/dist/js/adminlte.min.js"></script>
</body>

</html>