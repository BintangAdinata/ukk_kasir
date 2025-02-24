<?php
include 'koneksi.php'; // Pastikan file koneksi.php ada

// Ambil total transaksi hari ini
$query_transaksi = mysqli_query($koneksi, "SELECT SUM(tratotal) AS total FROM transaksi WHERE DATE(tratanggal) = CURDATE()");
$data_transaksi = mysqli_fetch_assoc($query_transaksi);
$total_transaksi = $data_transaksi['total'] ?? 0;

// Ambil pendapatan bulan ini
$query_pendapatan = mysqli_query($koneksi, "SELECT SUM(tratotal) AS total FROM transaksi WHERE MONTH(tratanggal) = MONTH(CURDATE())");
$data_pendapatan = mysqli_fetch_assoc($query_pendapatan);
$total_pendapatan = $data_pendapatan['total'] ?? 0;

// Ambil jumlah produk terjual
$query_produk = mysqli_query($koneksi, "SELECT SUM(tdjumlah) AS total FROM transaksi_detail");
$data_produk = mysqli_fetch_assoc($query_produk);
$total_produk_terjual = $data_produk['total'] ?? 0;

// Ambil jumlah produk yang sedang dijual
$query_produk = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM produk");
$data_produk = mysqli_fetch_assoc($query_produk);
$total_produk = $data_produk['total'] ?? 0;

?>
<!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                    <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Transaksi Hari Ini</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp. <?= number_format($total_transaksi, 0, ',', '.') ?></div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-cash-register fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Transaksi Bulan Ini</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp. <?= number_format($total_pendapatan, 0, ',', '.') ?></div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Jumlah Produk Yang Terjual</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($total_produk_terjual, 0, ',', '.') ?></div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Jumlah Produk</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($total_produk, 0, ',', '.') ?> Produk</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-boxes fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

                    </div>
                </div>
                <!-- /.container-fluid -->