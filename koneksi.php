<?php
// Cek apakah session belum dimulai sebelum mengatur session
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_cookies', 1);
    ini_set('session.cookie_httponly', 1);
    session_start();
};
$koneksi = mysqli_connect('localhost', 'root', '', 'kasir_db');
?>