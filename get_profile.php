<?php
session_start();
require_once 'db/config.php';

header('Content-Type: application/json');

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Anda harus login terlebih dahulu.']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data pengguna
$sql = "SELECT username, email FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    echo json_encode(['username' => $user['username'], 'email' => $user['email']]);
} else {
    echo json_encode(['error' => 'Pengguna tidak ditemukan.']);
}
