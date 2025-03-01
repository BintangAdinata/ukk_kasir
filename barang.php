<?php
require 'koneksi.php';

// Pastikan session sudah ada dan user sudah login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Ambil level user dari session
$level = $_SESSION['user']['level'];
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Data Barang</h1>

    <!-- Form Pencarian -->
    <form method="GET" class="mb-3">
        <input type="hidden" name="page" value="barang">
        <div class="input-group">
            <input type="text" class="form-control" name="search" placeholder="Cari barang..." value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
            <button type="submit" class="btn btn-primary" style="background-color: #1cc88a;">Cari</button>
            <a href="?page=barang" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <?php if ($level !== 'petugas'): ?>
        <a href="?page=barang_tambah" class="btn btn-primary" style="background-color: #1cc88a;">Tambah Barang</a>
    <?php endif; ?>
    <hr>

    <table class="table table-bordered">
        <tr>
            <th>Nama Barang</th>
            <th>Sisa Stock</th>
            <th>Harga</th>
            <?php if ($level !== 'petugas'): ?>
                <th>Aksi</th>
            <?php endif; ?>
        </tr>

        <?php
            // Menangkap keyword pencarian jika ada
            $search = isset($_GET['search']) ? $_GET['search'] : '';

            // Query untuk mencari barang
            $query = mysqli_query($koneksi, "SELECT * FROM produk WHERE pronama LIKE '%$search%'");

            // Cek apakah ada data yang ditemukan
            if (mysqli_num_rows($query) > 0) {
                while ($data = mysqli_fetch_array($query)) {
        ?>
            <tr>
                <td><?= $data['pronama'] ?></td>
                <td><?= $data['projumlah'] ?></td>
                <td><?= number_format($data['proharga'], 2) ?></td>
                <?php if ($level !== 'petugas'): ?>
                    <td>
                        <a href="?page=barang_ubah&id=<?= $data['proid'] ?>" class="btn btn-primary" style="background-color: #36b9cc;">Ubah</a>
                        <a href="?page=barang_hapus&id=<?= $data['proid'] ?>" class="btn btn-primary" style="background-color: #e74a3b;">Hapus</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php
                }
            } else {
        ?>
            <tr>
                <td colspan="<?= ($level !== 'petugas') ? 4 : 3 ?>" class="text-center">Tidak ada barang yang cocok</td>
            </tr>
        <?php
            }
        ?>
    </table>
</div>
