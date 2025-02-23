<?php
require 'koneksi.php';

// Ambil halaman saat ini (default = 1)
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$limit = 10; // Jumlah data per halaman
$offset = ($halaman - 1) * $limit; // Hitung offset

// Query untuk mendapatkan data transaksi dengan pagination
$sql = "SELECT trafaktur, trapelanggan, tratanggal, tratotal,
        (SELECT SUM(tdjumlah) FROM transaksi_detail WHERE trafaktur=t.trafaktur) AS tdjumlah,
        username
        FROM transaksi t
        INNER JOIN user u ON u.userid = t.userid
        ORDER BY trafaktur ASC
        LIMIT $limit OFFSET $offset";

$res = $koneksi->query($sql);

// Hitung total data untuk pagination
$result_total = $koneksi->query("SELECT COUNT(*) AS total FROM transaksi");
$total_data = $result_total->fetch_assoc()['total'];
$total_halaman = ceil($total_data / $limit);
?>

<div id="content">
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-dark">Data Penjualan</h3>
            <!-- Tombol Cetak -->
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                    Cetak Laporan
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="laporan_cetak.php?filter=all" target="_blank">Semua</a></li>
                    <li><a class="dropdown-item" href="laporan_cetak.php?filter=minggu" target="_blank">Mingguan</a></li>
                    <li><a class="dropdown-item" href="laporan_cetak.php?filter=bulan" target="_blank">Bulanan</a></li>
                    <li><a class="dropdown-item" href="laporan_cetak.php?filter=tahun" target="_blank">Tahunan</a></li>
                </ul>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <!-- Tabel Data Penjualan -->
                <div class="table-responsive">
                    <table class="table my-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No. Faktur</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Jum. Barang</th>
                                <th>Jum. Bayar</th>
                                <th>Kasir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = $offset + 1;
                                while($row = mysqli_fetch_assoc($res)) {
                            ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td><?= $row['trafaktur']; ?></td>
                                    <td><?= $row['tratanggal']; ?></td>
                                    <td><?= $row['trapelanggan']; ?></td>
                                    <td><?= $row['tdjumlah']; ?></td>
                                    <td><?= number_format($row['tratotal'],2); ?></td>
                                    <td><?= $row['username']; ?></td>
                                </tr>
                            <?php
                                $no++;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
<br>
                <!-- Pagination -->
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <!-- Tombol Previous -->
                        <li class="page-item <?= ($halaman <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=laporan&halaman=<?= ($halaman > 1) ? $halaman - 1 : 1 ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        <!-- Nomor Halaman -->
                        <?php for ($i = 1; $i <= $total_halaman; $i++) : ?>
                            <li class="page-item <?= ($i == $halaman) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=laporan&halaman=<?= $i ?>"> <?= $i ?> </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Tombol Next -->
                        <li class="page-item <?= ($halaman >= $total_halaman) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=laporan&halaman=<?= ($halaman < $total_halaman) ? $halaman + 1 : $total_halaman ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
