<?php
$id = $_GET['id'];
$query = mysqli_query($koneksi, "DELETE FROM produk WHERE proid=$id");
if($query) {
    echo '<script>alert("HAPUS DATA BERHASIL"); location.href="?page=barang"</script>';
}else{
    echo '<script>alert("HAPUS DATA GAGAL")</script>';
}
?>