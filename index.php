<?php
session_start();

if (isset($_SESSION['login_success'])) {
    echo "<script>alert('" . $_SESSION['login_success'] . "');</script>";
    unset($_SESSION['login_success']);
}

$page = $_GET['page'] ?? 'beranda';

$adder = '/kabupaten-kota/';
$beranda = array($adder, $adder . 'index.php', $adder . 'index.php?page=beranda');
$kab_kota_active = array(
    $adder . 'index.php?page=data_kabkota',
    $adder . 'index.php?page=tambah_kabkota',
    $adder . 'index.php?page=ubah_kabkota',
);
$user_active = array(
    $adder . 'index.php?page=data_user',
    $adder . 'index.php?page=tambah_user',
    $adder . 'index.php?page=ubah_user',
);
$kelola_data = array_merge($kab_kota_active, $user_active);

$request_uri = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Kabupaten Kota</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
    <style>
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active {
            background-color: #007bff !important;
            color: white !important;
        }

        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active i {
            color: white !important;
        }

        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link:hover {
            background-color: #0056b3;
            color: white;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="#" class="brand-link">
                <img src="image/logo/logo_polbeng.png" alt="Logo Polbeng" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">KabKota App</span>
            </a>
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="image/logo/awancutbray.jpeg"
                            class="img-circle elevation-3"
                            style="width: 35px; height: 35px; object-fit: cover; border-radius: 50%;"
                            alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?= $_SESSION['username']; ?></a>
                    </div>
                </div>
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="index.php?page=beranda" class="nav-link <?= in_array($request_uri, $beranda) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Beranda</p>
                            </a>
                        </li>
                        <li class="nav-item <?= in_array($request_uri, $kelola_data) ? 'menu-open' : '' ?>">
                            <a href="#" class="nav-link <?= in_array($request_uri, $kelola_data) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-briefcase"></i>
                                <p>Kelola Data <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="index.php?page=data_user" class="nav-link <?= in_array($request_uri, $user_active) ? 'active' : '' ?>">
                                        <i class="far fa-user nav-icon"></i>
                                        <p>Pengguna</p>
                                    </a>
                                </li>
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'User'): ?>
                                    <li class="nav-item">
                                        <a href="index.php?page=data_kabkota" class="nav-link <?= in_array($request_uri, $kab_kota_active) ? 'active' : '' ?>">
                                            <i class="far fa-building nav-icon"></i>
                                            <p>Kabupaten/Kota</p>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Admin'): ?>
                                    <li class="nav-item">
                                        <a href="index.php?page=data_kabkota" class="nav-link <?= in_array($request_uri, $kab_kota_active) ? 'active' : '' ?>">
                                            <i class="far fa-building nav-icon"></i>
                                            <p>Kabupaten/Kota</p>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="/kabupaten-kota/proses/user/proses_logout.php" onclick="return confirm('Apakah Anda yakin ingin logout?')" class=" nav-link">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <?php
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
                switch ($page) {
                    case 'beranda':
                        include 'pages/beranda.php';
                        break;
                    case 'data_kabkota':
                        if ($_SESSION['role'] == 'User') {
                            include 'pages/kabkota/data_kabkota.php';
                        } else {
                            include 'pages/error/404.php';
                        }
                        break;
                    case 'tambah_kabkota':
                        include 'pages/kabkota/tambah_kabkota.php';
                        break;
                    case 'ubah_kabkota':
                        include 'pages/kabkota/ubah_kabkota.php';
                        break;
                    case 'data_user':
                        if ($_SESSION['role'] == 'Admin') {
                            include 'pages/user/data_user.php';
                        } else {
                            include 'pages/error/401.php';
                        }
                        break;
                    case 'tambah_user':
                        if ($_SESSION['role'] == 'Admin') {
                            include 'pages/user/tambah_user.php';
                        } else {
                            include 'pages/error/401.php';
                        }
                        break;
                    case 'ubah_user':
                        if ($_SESSION['role'] == 'Admin') {
                            include 'pages/user/ubah_user.php';
                        } else {
                            include 'pages/error/401.php';
                        }
                        break;
                    default:
                        include 'pages/error/404.php';
                        break;
                }
            } else {
                include 'pages/beranda.php';
            }
            ?>
        </div>
        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                Developed By : Wawan Saputra
            </div>
            <strong>Copyright &copy; 2023 <a href="#">Admin LTE</a>.</strong> All rights reserved.
        </footer>
    </div>
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="plugins/jquery-validation/additional-methods.min.js"></script>
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script>
        $(function() {
            $('#kabkota').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
            $("#user").DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
            $('#tanggal_berdiri').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $("#tambahUser").validate({
                rules: {
                    username: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    no_hp: {
                        required: true,
                        rangelength: [10, 16]
                    },
                    password: {
                        required: true,
                        rangelength: [6, 25]
                    },
                    retype_password: {
                        required: true,
                        equalTo: "#password"
                    },
                },
                messages: {
                    username: {
                        required: "Silahkan masukkan nama pengguna!",
                        minlength: "Panjang nama pengguna minimal 3 karakter"
                    },
                    email: {
                        required: "Silahkan masukkan data email!",
                        email: "Format email salah!"
                    },
                    no_hp: {
                        required: "Silahkan masukkan data nomor handphone!",
                        rangelength: "Jumlah digit nomor handphone antara 10 - 16 digit."
                    },
                    password: {
                        required: "Silahkan masukkan password pengguna!",
                        rangelength: "Panjang password pengguna harus 6 - 25 karakter."
                    },
                    retype_password: {
                        required: "Silahkan ketik ulang password!",
                        equalTo: "Password tidak sama."
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
            $('#tambahData').validate({
                rules: {
                    kabupaten_kota: {
                        required: true,
                        minlength: 3
                    },
                    pusat_pemerintahan: {
                        required: true,
                        minlength: 3
                    },
                    bupati_walikota: {
                        required: true,
                        minlength: 3
                    },
                    tanggal_berdiri: {
                        required: true,
                        dateISO: true
                    },
                    luas_wilayah: {
                        required: true,
                        number: true,
                        min: 1
                    },
                    jumlah_penduduk: {
                        required: true,
                        number: true,
                        min: 1
                    },
                    jumlah_kecamatan: {
                        required: true,
                        number: true,
                        min: 1
                    },
                    jumlah_kelurahan: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    jumlah_desa: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    url_peta_wilayah: {
                        required: true,
                        url: true
                    },
                    deskripsi: {
                        required: true,
                        minlength: 10
                    },
                    logo: {
                        required: true,
                        extension: "jpg|jpeg|png|gif"
                    },
                },
                messages: {
                    kabupaten_kota: {
                        required: "Silahkan masukkan data kabupaten/kota baru!",
                        minlength: "Panjang nama kabupaten/kota minimal 3 karakter"
                    },
                    pusat_pemerintahan: {
                        required: "Silahkan masukkan data pusat pemerintahan!",
                        minlength: "Panjang nama pusat pemerintahan minimal 3 karakter"
                    },
                    bupati_walikota: {
                        required: "Silahkan masukkan data kepala daerah!",
                        minlength: "Panjang nama kepala daerah minimal 3 karakter"
                    },
                    tanggal_berdiri: {
                        required: "Silahkan masukkan data tanggal berdiri!",
                        dateISO: "Format tanggal yang anda masukan salah!"
                    },
                    luas_wilayah: {
                        required: "Silahkan masukkan data luas wilayah!",
                        number: "Silahkan masukkan angka desimal!",
                        min: "Nilai tidak boleh nol atau negatif!"
                    },
                    jumlah_penduduk: {
                        required: "Silahkan masukkan data jumlah penduduk!",
                        number: "Silahkan masukkan angka desimal!",
                        min: "Nilai tidak boleh nol atau negatif!"
                    },
                    jumlah_kecamatan: {
                        required: "Silahkan masukkan data jumlah kecamatan!",
                        number: "Silahkan masukkan angka desimal!",
                        min: "Nilai tidak boleh nol atau negatif!"
                    },
                    jumlah_kelurahan: {
                        required: "Silahkan masukkan data jumlah kelurahan!",
                        number: "Silahkan masukkan angka desimal!",
                        min: "Nilai tidak boleh nol atau negatif!"
                    },
                    jumlah_desa: {
                        required: "Silahkan masukkan data jumlah desa!",
                        number: "Silahkan masukkan angka desimal!",
                        min: "Nilai tidak boleh nol atau negatif!"
                    },
                    url_peta_wilayah: {
                        required: "Silahkan masukkan data url!",
                        url: "Silahkan masukkan link url yang benar!"
                    },
                    deskripsi: {
                        required: "Silahkan masukkan data deskripsi!",
                        minlength: "Panjang teks deskripsi singkat minimal 10 karakter!"
                    },
                    logo: {
                        required: "Silahkan masukkan data logo!",
                        extension: "Ekstensi file logo yang diizinkan adalah .jpg|.jpeg|.png|.gif!"
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
            $('#deskripsi').summernote({
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview']],
                ]
            });
            bsCustomFileInput.init();
        });

        $(function() {
            $("#tanggal_berdiri").datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $("#tambahUser").validate({
                rules: {
                    username: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    no_hp: {
                        required: true,
                        rangelength: [10, 16]
                    },
                    password: {
                        required: true,
                        rangelength: [6, 25]
                    },
                    retype_password: {
                        required: true,
                        equalTo: "#password"
                    },
                },
                messages: {
                    username: {
                        required: "Silahkan masukkan nama pengguna!",
                        minlength: "Panjang nama pengguna minimal 3 karakter"
                    },
                    email: {
                        required: "Silahkan masukkan data email!",
                        email: "Format email salah!"
                    },
                    no_hp: {
                        required: "Silahkan masukkan data nomor handphone!",
                        rangelength: "Jumlah digit nomor handphone antara 10 - 16 digit."
                    },
                    password: {
                        required: "Silahkan masukkan password pengguna!",
                        rangelength: "Panjang karakter password harus 6 - 25 karakter."
                    },
                    retype_password: {
                        required: "Silahkan ketik ulang password!",
                        equalTo: "Password tidak sama."
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });

        function disableLogo(checkboxElem) {
            let value = false;
            if (checkboxElem.checked) {
                value = false;
            } else {
                value = true;
            }
            document.getElementById('logo').disabled = value;
        }
    </script>
</body>

</html>