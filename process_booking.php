<?php
session_start();
require_once 'db/config.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id']) || !isset($_POST['film_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$film_id = $_POST['film_id'];
$jumlah_tiket = $_POST['jumlah_tiket'];
$harga = $_POST['harga'];
$total_harga = $harga * $jumlah_tiket;
$status = 'pending';

// Insert booking ke database
$query = "INSERT INTO bookings (user_id, film_id, jumlah_tiket, total_harga, status) 
          VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);
$stmt->bind_param("iiids", $user_id, $film_id, $jumlah_tiket, $total_harga, $status);

if ($stmt->execute()) {
    $booking_id = $stmt->insert_id;
    // Redirect ke halaman pembayaran
    header("Location: payment.php?booking_id=" . $booking_id);
    exit();
} else {
    $_SESSION['error'] = "Gagal melakukan pemesanan";
    header("Location: pesanTiket.php?id=" . $film_id);
    exit();
}
