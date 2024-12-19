<?php
session_start();
require_once 'db/config.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Validasi booking_id
if (!isset($_GET['booking_id']) || empty($_GET['booking_id'])) {
    header('Location: profile.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$booking_id = intval($_GET['booking_id']); // Pastikan ID valid

// Update status booking menjadi 'cancelled'
$query_cancel = "UPDATE bookings SET status = 'cancelled' WHERE id = ? AND user_id = ? AND status = 'pending'";
$stmt_cancel = $conn->prepare($query_cancel);
$stmt_cancel->bind_param("ii", $booking_id, $user_id);

if ($stmt_cancel->execute()) {
    $_SESSION['success'] = "Pesanan berhasil dibatalkan.";
} else {
    $_SESSION['error'] = "Gagal membatalkan pesanan. Silakan coba lagi.";
}

header('Location: profile.php');
exit();
