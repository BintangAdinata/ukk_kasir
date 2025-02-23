<?php
require 'koneksi.php'; // Pastikan koneksi ke database sudah ada

$msg = ""; // Inisialisasi variabel pesan

if (isset($_POST['pronama'])) {
    $pronama = $_POST['pronama'];
    $proharga = $_POST['proharga'];
    $projumlah = $_POST['projumlah'];

    // Cek apakah barang dengan nama yang sama sudah ada
    $cek_query = mysqli_query($koneksi, "SELECT * FROM produk WHERE pronama = '$pronama'");
    if (mysqli_num_rows($cek_query) > 0) {
        $msg = "Barang sudah ada! Harap gunakan nama lain atau perbarui stok.";
    } else {
        // Jika belum ada, lakukan insert data
        $query = mysqli_query($koneksi, "INSERT INTO produk(pronama, proharga, projumlah) VALUES ('$pronama', '$proharga', '$projumlah')");

        if ($query) {
            $msg = "Tambah Data Berhasil!";
        } else {
            $msg = "Tambah Data Gagal! Harap periksa kembali.";
        }
    }
}
?>

<div id="content">
    <div class="container-fluid">
        <h3 class="text-dark mb-4">Tambah Barang</h3>
        <?= $msg ? '<h6 class="text-dark mb-4">'.$msg.'</h6>' : ""?>
        <div class="row mb-3">
            <div class="col-lg-8">
                <div class="row">
                    <div class="col">
                        <div class="card shadow mb-3">
                            <div class="card-body">
                                <form method="POST">
                                    <div class="form-row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="email"><strong>Nama Produk</strong></label>
                                                <input class="form-control" type="text" placeholder="Nama Produk" name="pronama" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="last_name"><strong>Harga</strong></label>
                                                <input class="form-control" type="number" placeholder="Harga" name="proharga" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="last_name"><strong>Stok</strong></label>
                                                <input class="form-control" type="number" placeholder="Sisa Stok" name="projumlah" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-auto">
                                            <input class="btn btn-primary" type="submit" name="simpan" style="background-color: #1cc88a;" value="Simpan" />
                                        </div>
                                        <div class="col-auto">
                                            <a href="?page=barang" class="btn btn-danger">Kembali</a>
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
