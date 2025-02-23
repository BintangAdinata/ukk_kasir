<?php
require 'koneksi.php';

// Ambil filter dari GET parameter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$tgl_awal = isset($_GET['tglDari']) ? $_GET['tglDari'] : '';
$tgl_sampai = isset($_GET['tglSampai']) ? $_GET['tglSampai'] : '';

// Buat kondisi filter
$where = "WHERE 1=1"; // Default agar query tidak error
if ($filter == "minggu") {
    $where .= " AND tratanggal >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
} elseif ($filter == "bulan") {
    $where .= " AND MONTH(tratanggal) = MONTH(CURDATE()) AND YEAR(tratanggal) = YEAR(CURDATE())";
} elseif ($filter == "tahun") {
    $where .= " AND YEAR(tratanggal) = YEAR(CURDATE())";
}

// Jika ada filter berdasarkan tanggal khusus
if (!empty($tgl_awal)) {
    $where .= " AND tratanggal >= '$tgl_awal'";
}
if (!empty($tgl_sampai)) {
    $where .= " AND tratanggal <= '$tgl_sampai'";
}

// Query data laporan penjualan
$sql = "SELECT t.trafaktur, t.trapelanggan, t.tratanggal, t.tratotal,
               (SELECT SUM(tdjumlah) FROM transaksi_detail WHERE trafaktur=t.trafaktur) AS tdjumlah,
               u.username
        FROM transaksi t
        INNER JOIN user u ON u.userid = t.userid
        $where
        ORDER BY t.tratanggal DESC";

$res = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Penjualan</title>
    <link rel="stylesheet" href="../dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <center>
            <h3>E-Kasir</h3>
            <h2>Laporan Penjualan Restoran</h2>
            <?php if (!empty($tgl_awal) && !empty($tgl_sampai)) : ?>
                Periode: <?= date('d-m-Y', strtotime($tgl_awal)) ?> s/d <?= date('d-m-Y', strtotime($tgl_sampai)) ?>
            <?php else: ?>
                Periode: <?= ucfirst($filter) ?>
            <?php endif; ?>
        </center>
        <br>

        <table class="table table-bordered table-striped">
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
                $no = 1;
                $totalSemua = 0;
                while ($row = $res->fetch_assoc()) {
                    $totalSemua += $row['tratotal']; // Hitung total semua transaksi
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['trafaktur'] ?></td>
                        <td><?= date('d-m-Y', strtotime($row['tratanggal'])) ?></td>
                        <td><?= $row['trapelanggan'] ?></td>
                        <td><?= $row['tdjumlah'] ?></td>
                        <td>Rp <?= number_format($row['tratotal'], 2) ?></td>
                        <td><?= $row['username'] ?></td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td colspan="5"><strong>Total Keseluruhan</strong></td>
                    <td><strong>Rp <?= number_format($totalSemua, 2, ',', '.') ?></strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        window.print();
        setTimeout(function() {
            window.close();
        }, 1000);
    </script>
</body>
</html>

<?php
$koneksi->close();
?>
