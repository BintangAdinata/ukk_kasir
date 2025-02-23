<?php
require('koneksi.php');

if (!isset($_GET['faktur'])) {
    die("Nomor faktur tidak ditemukan.");
}

$trafaktur = $_GET['faktur'];
$query = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE trafaktur = '$trafaktur'");
$data_transaksi = mysqli_fetch_assoc($query);

if (!$data_transaksi) {
    die("Data transaksi tidak ditemukan.");
}

$query_detail = mysqli_query($koneksi, "SELECT td.*, p.pronama FROM transaksi_detail td
                                       JOIN produk p ON td.proid = p.proid
                                       WHERE td.trafaktur = '$trafaktur'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .struk {
            width: 300px;
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            border-bottom: 1px dashed #000;
            padding: 5px;
            text-align: left;
        }
        .total {
            font-weight: bold;
        }
        .cetak {
            margin-top: 10px;
        }
    </style>
</head>
<body onload="window.print();">
    <div class="struk">
        <h3>STRUK PEMBELIAN</h3>
        <p>No. Faktur: <?= $data_transaksi['trafaktur'] ?></p>
        <p>Tanggal: <?= $data_transaksi['tratanggal'] ?></p>
        <p>Pelanggan: <?= htmlspecialchars($data_transaksi['trapelanggan']) ?></p>
        <hr>
        <table>
            <tr>
                <th>Produk</th>
                <th>Jml</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($query_detail)) { ?>
            <tr>
                <td><?= $row['pronama'] ?></td>
                <td><?= $row['tdjumlah'] ?></td>
                <td><?= number_format($row['tdharga'], 0, ',', '.') ?></td>
                <td><?= number_format($row['tdsubtotal'], 0, ',', '.') ?></td>
            </tr>
            <?php } ?>
        </table>
        <hr>
        <p class="total">Total: Rp <?= number_format($data_transaksi['tratotal'], 0, ',', '.') ?></p>
        <p>Uang Pembeli: Rp <?= number_format($data_transaksi['uangpembeli'], 0, ',', '.') ?></p>
        <p>Kembalian: Rp <?= number_format($data_transaksi['uangpembeli'] - $data_transaksi['tratotal'], 0, ',', '.') ?></p>
        <p>Terima kasih telah berbelanja!</p>
    </div>
</body>
<script>
    window.print();
    window.onafterprint = function() {
        window.location.href = "index.php?page=penjualan&reset=true";
    };
</script>
</html>
