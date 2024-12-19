<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'tiket_bioskop';

// Membuat koneksi
$conn = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");
