<?php
require('koneksi.php');

// Pastikan user sudah login
if (!isset($_SESSION['user'])) {
    header("Location:login.php");
    exit();
}

$tanggal = date("Y-m-d");
$trapelanggan = isset($_POST['trapelanggan']) ? $_POST['trapelanggan'] : "";
$msg = "";

// Buat nomor faktur otomatis
$query_faktur = mysqli_query($koneksi, "SELECT trafaktur FROM transaksi ORDER BY trafaktur DESC LIMIT 1");
$data_faktur = mysqli_fetch_assoc($query_faktur);

if ($data_faktur) {
    $last_faktur = $data_faktur['trafaktur'];
    $last_number = intval(substr($last_faktur, 3));
    $new_number = $last_number + 1;
} else {
    $new_number = 1;
}

$trafaktur = "TRA" . str_pad($new_number, 4, '0', STR_PAD_LEFT);

if (isset($_POST['simpan'])) {
    $cart = $_SESSION['cart'] ?? [];
    $user = $_SESSION['user'] ?? [];
    $userid = $user['userid'] ?? null;
    $err = 0;
    $grandtotal = 0;
    $total_diskon = 0;
    $uang_pembeli = $_POST['uangpembeli'] ?? 0;
    $diskon_input = $_POST['diskon'] ?? [];

    mysqli_begin_transaction($koneksi);

    if (empty($trapelanggan)) {
        $msg = 'Harap masukkan data dengan benar';
    } elseif (count($cart) == 0) {
        $msg = 'Keranjang Kosong';
    } else {
        foreach ($cart as $i => $item) {
            $proid = $item['proid'];
            $harga = $item['proharga'];
            $jumlah = $item['jumlah'];
            $diskon = isset($diskon_input[$i]) ? (float)$diskon_input[$i] : 0;
                if ($diskon > 100) {
                $msg = 'Diskon tidak boleh lebih dari 100%';
                mysqli_rollback($koneksi);
                exit();
                }

            $sub = $harga * $jumlah;
            $sub_diskon = $sub - ($sub * ($diskon / 100));
            $total_diskon += ($sub * ($diskon / 100));
            $grandtotal += $sub_diskon;

            $sql = "INSERT INTO transaksi_detail (trafaktur, proid, tdjumlah, tdharga, tdsubtotal, tddiskon)
            VALUES ('$trafaktur', $proid, $jumlah, $harga, $sub_diskon, $diskon)";
            if (!mysqli_query($koneksi, $sql)) {
            $err++;
            }

            $cek_stok = mysqli_query($koneksi, "SELECT projumlah FROM produk WHERE proid = $proid");
            $data_stok = mysqli_fetch_assoc($cek_stok);
            if ($data_stok['projumlah'] < $jumlah) {
                $msg = "Stok produk '{$item['pronama']}' tidak mencukupi!";
                mysqli_rollback($koneksi);
                return;
            }

            // Kurangi stok produk
            $sql_update_stok = "UPDATE produk SET projumlah = projumlah - $jumlah WHERE proid = $proid";
            if (!mysqli_query($koneksi, $sql_update_stok)) {
            $err++;
            }

        }


        if ($uang_pembeli < $grandtotal) {
            $msg = 'Uang pembeli tidak cukup!';
            mysqli_rollback($koneksi);
        } else {
            $sql = "INSERT INTO transaksi (trafaktur, tratanggal, trapelanggan, tratotal, userid, tradiskon, uangpembeli)
                    VALUES ('$trafaktur', '$tanggal', '$trapelanggan', $grandtotal, $userid, $total_diskon, $uang_pembeli)";
            if (mysqli_query($koneksi, $sql) && $err == 0) {
                mysqli_commit($koneksi);
                unset($_SESSION['cart']);
                $msg = 'Penjualan Berhasil disimpan';
            } else {
                mysqli_rollback($koneksi);
                $msg = 'GAGAL';
            }
        }
    }
}

if (isset($_GET['reset'])) {
    unset($_SESSION['cart']); // Reset keranjang setelah cetak
    header("Location: index.php?page=penjualan"); // Kembali ke halaman penjualan tanpa parameter reset
    exit();
}
?>

<div id="content">
    <div class="container-fluid">
        <h3 class="text-dark mb-4">Penjualan</h3>
        <?= $msg ? "<h6 class='text-dark mb-4'>$msg</h6>" : "" ?>
        <form method="POST">
            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <p class="text-primary m-0 font-weight-bold">Detail Penjualan</p>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label><strong>Nama Pelanggan</strong></label>
                                <input class="form-control" type="text" placeholder="Nama Pelanggan" name="trapelanggan" value="<?= htmlspecialchars($trapelanggan) ?>">
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <label><strong>No Faktur</strong></label>
                                    <input class="form-control" type="text" disabled value="<?= $trafaktur ?>">
                                </div>
                                <div class="col">
                                    <label><strong>Tanggal</strong></label>
                                    <input class="form-control" type="text" disabled value="<?= $tanggal ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header py-3">
                            <p class="text-primary m-0 font-weight-bold">Data Keranjang</p>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>Diskon (%)</th>
                                        <th>Subtotal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $cart = $_SESSION['cart'] ?? [];
                                    $total = 0;
                                    $total_diskon = 0;

                                    if (empty($cart)) {
                                    echo "<tr><td colspan='6' class='text-center'>Keranjang masih kosong</td></tr>";
                                    } else {
                                        foreach ($cart as $i => $val) {
                                            $subtotal = $val['proharga'] * $val['jumlah'];
                                            $diskon = isset($val['diskon']) ? $val['diskon'] : 0;
                                            $subtotal_diskon = $subtotal - ($subtotal * ($diskon / 100));
                                            $total += $subtotal_diskon;
                                            $total_diskon += ($subtotal * ($diskon / 100));

                                            echo "<tr>
                                            <td>{$val['pronama']}</td>
                                            <td>" . number_format($val['jumlah'], 2) . "</td>
                                            <td>" . number_format($val['proharga'], 2) . "</td>
                                            <td>
                                                <input type='number' name='diskon[$i]' value='{$diskon}' min='0' max='100' class='form-control diskon-input' data-index='$i' style='width:80px'>
                                            </td>
                                            <td class='subtotal' data-index='$i' data-harga='{$val['proharga']}' data-jumlah='{$val['jumlah']}'>" . number_format($subtotal_diskon, 2) . "</td>
                                            <td><a href='?page=penjualan_hapus&id={$val['proid']}' class='btn btn-danger'>Hapus</a></td>
                                        </tr>";
                                        }
                                    }
                                ?>
                                </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3"><strong>Total Akhir</strong></td>
                                            <td colspan="2"><input type="text" id="total_akhir" class="form-control" readonly></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><strong>Uang Pembeli</strong></td>
                                            <td colspan="2"><input type="number" name="uangpembeli" class="form-control" required></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><strong>Kembalian</strong></td>
                                            <td colspan="2"><input type="text" id="kembalian" class="form-control" readonly></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col">
                            <a href="?page=penjualan_pilih" class="btn btn-info w-100">Pilih Produk</a>
                        </div>
                        <div class="col">
                            <input class="btn btn-success w-100" type="submit" name="simpan" value="Simpan">
                        </div>
                        <?php if (trim(strtolower($msg)) == 'penjualan berhasil disimpan') : ?>
                            <div class="col">
                                <a href="cetak_struk.php?faktur=<?= $trafaktur ?>" class="btn btn-primary w-100">Cetak Struk</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
document.querySelectorAll(".diskon-input").forEach(input => {
    input.addEventListener("input", function() {
        let diskon = parseFloat(this.value) || 0;

        if (diskon > 100) {
            alert("Diskon tidak boleh lebih dari 100%");
            this.value = 100;
            diskon = 100; // Set diskon ke 100 agar tidak tetap dihitung dengan nilai lebih besar
        }

        let index = this.dataset.index;
        let subtotalElement = document.querySelector(`.subtotal[data-index='${index}']`);
        let harga = parseFloat(subtotalElement.dataset.harga);
        let jumlah = parseFloat(subtotalElement.dataset.jumlah);

        let subtotal = harga * jumlah;
        let subtotalDiskon = subtotal - (subtotal * (diskon / 100));

        subtotalElement.textContent = subtotalDiskon.toFixed(2);

        hitungTotal();
    });
});

document.querySelector("input[name='uangpembeli']").addEventListener("input", function() {
    hitungTotal();
});

function hitungTotal() {
    let total = 0;
    document.querySelectorAll(".subtotal").forEach(el => {
        total += parseFloat(el.textContent.replace(",", "")); // Hilangkan koma jika ada
    });

    document.getElementById("total_akhir").value = total.toLocaleString("id-ID", { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    let uangPembeli = parseFloat(document.querySelector("input[name='uangpembeli']").value) || 0;
    let kembalian = uangPembeli - total;

    document.getElementById("kembalian").value = kembalian >= 0
        ? kembalian.toLocaleString("id-ID", { minimumFractionDigits: 2, maximumFractionDigits: 2 })
        : "Uang kurang!";
}
</script>

