<?php
ob_start();
include "koneksi.php"; // session_start() sudah ada di koneksi.php

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Ambil level user dari session
$level = $_SESSION['user']['level'] ?? '';

// Daftar halaman yang diizinkan untuk masing-masing level
$allowed_pages = [
    'admin'  => ['home', 'barang', 'barang_tambah', 'barang_hapus', 'barang_ubah', 'penjualan', 'penjualan_pilih', 'penjualan_hapus', 'cetak_struk', 'laporan', 'laporan_cetak', 'kelola'],
    'petugas'  => ['home', 'barang', 'penjualan', 'penjualan_pilih', 'penjualan_hapus', 'cetak_stuk'],
];

// Pastikan level valid, jika tidak valid maka logout
if (!isset($allowed_pages[$level])) {
    header("Location: logout.php");
    exit();
}

// Cek apakah halaman yang diakses sesuai dengan level pengguna
$page = isset($_GET['page']) && in_array($_GET['page'], $allowed_pages[$level]) ? $_GET['page'] : 'home';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Kasir - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Kasir</div>
            </a>

            <hr class="sidebar-divider my-0">

            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt" style="font-size: 20px;"></i>
                    <span style="font-size: 18px;margin-left: 10px;">Dashboard</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <?php if (in_array($level, ['admin', 'petugas'])) { ?>
            <li class="nav-item">
                <a class="nav-link" href="?page=barang">
                    <i class="fas fa-fw fa-box" style="font-size: 20px;"></i>
                    <span style="font-size: 18px;margin-left: 10px;">Data Barang</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="?page=penjualan">
                    <i class="fas fa-fw fa-shopping-cart" style="font-size: 20px;"></i>
                    <span style="font-size: 18px;margin-left: 10px;">Penjualan</span>
                </a>
            </li>
            <?php } ?>

            <?php if ($level === 'admin') { ?>
            <li class="nav-item">
                <a class="nav-link" href="?page=laporan">
                    <i class="fas fa-fw fa-file-alt" style="font-size: 20px;"></i>
                    <span style="font-size: 18px;margin-left: 10px;">Laporan</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="?page=kelola">
                    <i class="fas fa-fw fa-users" style="font-size: 20px;"></i>
                    <span style="font-size: 18px;margin-left: 10px;">Kelola User</span>
                </a>
            </li>
            <?php } ?>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $_SESSION['user']['nama'] ?></span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <?php
                // Reset session penjualan jika ada parameter reset
                if ($page == 'penjualan' && isset($_GET['reset'])) {
                    unset($_SESSION['cart']); // Hapus session cart
                    header("Location: index.php?page=penjualan"); // Redirect ulang
                    exit();
                }

                include $page . '.php';
                ?>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; UKK Kasir 2025</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Bootstrap core JavaScript-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>
