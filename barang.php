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

    <a href="?page=barang_tambah" class="btn btn-primary" style="background-color: #1cc88a;">Tambah Barang</a>
    <hr>

    <table class="table table-bordered">
        <tr>
            <th>Nama Barang</th>
            <th>Sisa Stock</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>

        <?php
            require 'koneksi.php';

            // Menangkap keyword pencarian jika ada
            $search = isset($_GET['search']) ? $_GET['search'] : '';

            // Query untuk mencari barang
            $query = mysqli_query($koneksi, "SELECT * FROM produk WHERE pronama LIKE '%$search%'");

            // Menampilkan hasil pencarian
            while ($data = mysqli_fetch_array($query)) {
        ?>
            <tr>
                <td><?= $data['pronama'] ?></td>
                <td><?= $data['projumlah'] ?></td>
                <td><?= number_format($data['proharga'], 2) ?></td>
                <td>
                    <a href="?page=barang_ubah&id=<?= $data['proid'] ?>" class="btn btn-primary" style="background-color: #36b9cc;">Ubah</a>
                    <a href="?page=barang_hapus&id=<?= $data['proid'] ?>" class="btn btn-primary" style="background-color: #e74a3b;">Hapus</a>
                </td>
            </tr>
        <?php
            }
        ?>
    </table>
</div>
