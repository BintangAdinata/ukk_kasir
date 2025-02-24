<?php
// Cek apakah session belum dimulai sebelum mengatur session
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_cookies', 1);
    ini_set('session.cookie_httponly', 1);
    session_start();
};
date_default_timezone_set('Asia/Jakarta');
$koneksi = mysqli_connect('localhost', 'root', '', 'kasir_db');
?>