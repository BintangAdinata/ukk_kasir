<?php
require 'koneksi.php'; // Pastikan koneksi sudah ada

$msg = ""; // Inisialisasi variabel pesan

// Ambil ID produk dan parameter redirect
$id = $_GET['id'] ?? null;
$redirect = $_GET['redirect'] ?? null;

if (!$id) {
    $msg = "ID produk tidak ditemukan!";
} else {
    // Cek apakah form dikirim
    if (isset($_POST['pronama'])) {
        $pronama = $_POST['pronama'];
        $projumlah = $_POST['projumlah'];
        $proharga = $_POST['proharga'];
        $redirect_post = $_POST['redirect'] ?? null;

        // Query UPDATE data produk
        $query = mysqli_query($koneksi, "UPDATE produk SET pronama='$pronama', projumlah='$projumlah', proharga='$proharga' WHERE proid=$id");

        if ($query) {
            $msg = "Update Data Berhasil!";

            // Jika berasal dari penjualan, kembali ke halaman penjualan
            if ($redirect_post === 'penjualan') {
                header("Location: penjualan.php");
                exit;
            }
        } else {
            $msg = "Update Data Gagal! Harap periksa kembali.";
        }
    }

    // Ambil data produk berdasarkan ID
    $query = mysqli_query($koneksi, "SELECT * FROM produk WHERE proid=$id");
    $data = mysqli_fetch_array($query);
}
?>

<div id="content">
    <div class="container-fluid">
        <h3 class="text-dark mb-4">Ubah Barang</h3>
        <?= $msg ? '<h6 class="text-dark mb-4">'.$msg.'</h6>' : ""?>
        <div class="row mb-3">
            <div class="col-lg-8">
                <div class="row">
                    <div class="col">
                        <div class="card shadow mb-3">
                            <div class="card-body">
                                <form method="POST">
                                    <input type="hidden" name="redirect" value="<?= $redirect ?>">

                                    <div class="form-row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="email"><strong>Nama Produk</strong></label>
                                                <input class="form-control" type="text" placeholder="Nama Produk" name="pronama" value="<?= $data['pronama'] ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="last_name"><strong>Harga</strong><br></label>
                                                <input class="form-control" type="number" placeholder="Harga" name="proharga" value="<?= $data['proharga'] ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="last_name"><strong>Stok</strong></label>
                                                <input class="form-control" type="number" placeholder="Sisa Stok" name="projumlah" value="<?= $data['projumlah'] ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-auto">
                                            <input class="btn btn-primary" type="submit" name="simpan" style="background-color: #1cc88a;" value="Simpan" />
                                        </div>
                                        <div class="col-auto">
                                            <a href="<?= $redirect === 'penjualan' ? 'penjualan.php' : '?page=barang' ?>" class="btn btn-danger">Kembali</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
