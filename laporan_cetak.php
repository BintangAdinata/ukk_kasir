<?php
    require 'koneksi.php';

    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

    $where = "";
    if ($filter == "minggu") {
        $where = "WHERE tratanggal >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
    } elseif ($filter == "bulan") {
        $where = "WHERE MONTH(tratanggal) = MONTH(CURDATE()) AND YEAR(tratanggal) = YEAR(CURDATE())";
    } elseif ($filter == "tahun") {
        $where = "WHERE YEAR(tratanggal) = YEAR(CURDATE())";
    }

    $sql = "SELECT trafaktur, trapelanggan, tratanggal, tratotal,
                   (SELECT SUM(tdjumlah) FROM transaksi_detail WHERE trafaktur=t.trafaktur) AS tdjumlah,
                   username
            FROM transaksi t
            INNER JOIN user u ON u.userid = t.userid
            $where
            ORDER BY tratanggal DESC";

    $res = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan</title>
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
    <h3>Laporan Penjualan</h3>
    <table>
        <tr>
            <th>No</th>
            <th>No. Faktur</th>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Jum. Barang</th>
            <th>Jum. Bayar</th>
            <th>Kasir</th>
        </tr>
        <?php
        $no = 1;
        while ($row = $res->fetch_assoc()) {
            echo "<tr>
                    <td>".$no."</td>
                    <td>".$row['trafaktur']."</td>
                    <td>".$row['tratanggal']."</td>
                    <td>".$row['trapelanggan']."</td>
                    <td>".$row['tdjumlah']."</td>
                    <td>".number_format($row['tratotal'], 2)."</td>
                    <td>".$row['username']."</td>
                  </tr>";
            $no++;
        }
        ?>
    </table>

    <script>
            window.print();
            setTimeout(function() {
                window.close();
            });
    </script>
</body>
</html>

<?php
    $koneksi->close();
?>
