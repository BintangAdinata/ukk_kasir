<?php
require 'koneksi.php';

// Ambil tanggal filter dari parameter GET
$tglDari = isset($_GET['tglDari']) ? $_GET['tglDari'] : date('Y-m-01');
$tglSampai = isset($_GET['tglSampai']) ? $_GET['tglSampai'] : date('Y-m-d');

// Menentukan kondisi WHERE berdasarkan filter tanggal
$where = "WHERE 1=1";
if (!empty($tglDari)) {
    $where .= " AND DATE(tratanggal) >= '$tglDari'";
}
if (!empty($tglSampai)) {
    $where .= " AND DATE(tratanggal) <= '$tglSampai'";
}

// Ambil halaman saat ini (default = 1)
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$limit = 10; // Jumlah data per halaman
$offset = ($halaman - 1) * $limit; // Hitung offset

// Query untuk mendapatkan data transaksi dengan pagination dan filter tanggal
$sql = "SELECT trafaktur, trapelanggan, tratanggal, tratotal,
        (SELECT SUM(tdjumlah) FROM transaksi_detail WHERE trafaktur=t.trafaktur) AS tdjumlah,
        COALESCE(username, 'Tidak diketahui') AS username
        FROM transaksi t
        LEFT JOIN user u ON u.userid = t.userid
        $where
        ORDER BY trafaktur ASC
        LIMIT $limit OFFSET $offset";
$res = $koneksi->query($sql);

// Hitung total data setelah filter untuk pagination
$result_total = $koneksi->query("SELECT COUNT(*) AS total FROM transaksi t $where");
$total_data = $result_total->fetch_assoc()['total'];
$total_halaman = ceil($total_data / $limit);
?>

<div id="content">
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-dark">Data Penjualan</h3>
        </div>

        <!-- Filter Tanggal -->
        <form action="" method="get" class="form-inline mb-3">
            <input type="hidden" name="page" value="laporan">
            <label for="tglDari">Tanggal Awal:</label>
            <input type="date" name="tglDari" class="form-control mx-2" value="<?= htmlspecialchars($tglDari) ?>">
            <label for="tglSampai">Tanggal Sampai:</label>
            <input type="date" name="tglSampai" class="form-control mx-2" value="<?= htmlspecialchars($tglSampai) ?>">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="laporan_cetak.php?tglDari=<?= urlencode($tglDari) ?>&tglSampai=<?= urlencode($tglSampai) ?>"
               target="_blank" class="btn btn-success ms-2">
               Cetak Laporan
            </a>
        </form>


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
                            <?php if ($res->num_rows > 0): ?>
                                <?php $no = $offset + 1; ?>
                                <?php while ($row = $res->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $no ?></td>
                                        <td><?= htmlspecialchars($row['trafaktur']) ?></td>
                                        <td><?= htmlspecialchars($row['tratanggal']) ?></td>
                                        <td><?= htmlspecialchars($row['trapelanggan']) ?></td>
                                        <td><?= htmlspecialchars($row['tdjumlah']) ?></td>
                                        <td>Rp <?= number_format($row['tratotal'], 2) ?></td>
                                        <td><?= htmlspecialchars($row['username']) ?></td>
                                    </tr>
                                    <?php $no++; ?>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center font-weight-bold">Data tidak ditemukan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <br>

                <!-- Pagination -->
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <!-- Tombol Previous -->
                        <li class="page-item <?= ($halaman <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=laporan&halaman=<?= max(1, $halaman - 1) ?>&tglDari=<?= urlencode($tglDari) ?>&tglSampai=<?= urlencode($tglSampai) ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        <!-- Nomor Halaman -->
                        <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                            <li class="page-item <?= ($i == $halaman) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=laporan&halaman=<?= $i ?>&tglDari=<?= urlencode($tglDari) ?>&tglSampai=<?= urlencode($tglSampai) ?>"> <?= $i ?> </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Tombol Next -->
                        <li class="page-item <?= ($halaman >= $total_halaman) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=laporan&halaman=<?= min($total_halaman, $halaman + 1) ?>&tglDari=<?= urlencode($tglDari) ?>&tglSampai=<?= urlencode($tglSampai) ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
</div>