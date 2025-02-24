<?php
require 'koneksi.php';
$msg = '';

// Pastikan user sudah login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Koneksi ke database
global $koneksi;

$cari = isset($_GET['c']) ? $_GET['c'] : "";
$sql = "SELECT * FROM produk WHERE pronama LIKE '%$cari%' AND projumlah > 0";
$res = mysqli_query($koneksi, $sql);
$count = mysqli_num_rows($res);

// Menangani pemilihan produk
if (isset($_GET['id'])) {
    $proid = intval($_GET['id']);

    $sql = "SELECT * FROM produk WHERE proid = $proid AND projumlah > 0";
    $res = mysqli_query($koneksi, $sql);
    $count = mysqli_num_rows($res);

    if ($count == 0) {
        $msg = 'Barang yang dipilih tidak tersedia atau stok habis!';
    } else {
        $produk = mysqli_fetch_assoc($res);
        $produk['jumlah'] = 1;
        $item[] = $produk;

        $cart = $_SESSION['cart'] ?? [];

        $replace = false;
        foreach ($cart as $key => $val) {
            if ($val['proid'] == $produk['proid']) {
                $cart[$key]['jumlah'] += 1;
                $replace = true;
                break;
            }
        }

        if (!$replace) {
            $cart = array_merge($cart, $item);
        }

        $_SESSION['cart'] = $cart;
        header("Location: ?page=penjualan");
        exit();
    }
}
?>

<div id="content">
    <div class="container-fluid">
        <h3 class="text-dark mb-4">Pilih Produk</h3>
        <?= $msg ? '<h6 class="text-dark mb-4">'.$msg.'</h6>' : ""?>

        <!-- Form Pencarian -->
        <form method="GET" class="mb-3">
            <input type="hidden" name="page" value="penjualan_pilih">
            <div class="input-group">
                <input type="text" class="form-control" name="c" placeholder="Cari produk..." value="<?= isset($_GET['c']) ? $_GET['c'] : '' ?>">
                <button type="submit" class="btn btn-primary" style="background-color: #1cc88a;">Cari</button>
                <a href="?page=penjualan_pilih" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                    <table class="table my-0" id="dataTable">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Sisa Stok</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($res)): ?>
                                <tr>
                                    <td><?= $row['pronama'] ?></td>
                                    <td><?= $row['projumlah'] ?></td>
                                    <td><?= number_format($row['proharga'], 2) ?></td>
                                    <td><a href="?page=penjualan_pilih&id=<?= $row['proid'] ?>" class="btn btn-primary" style="background-color: #36b9cc;">Pilih</a></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-6 align-self-center">
                        <p id="dataTable_info" class="dataTables_info" role="status" aria-live="polite">Menampilkan <?= $count; ?> Data</p>
                    </div>
                    <div class="col-md-6">
                        <nav class="d-lg-flex justify-content-lg-end dataTables_paginate paging_simple_numbers">
                            <ul class="pagination"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
