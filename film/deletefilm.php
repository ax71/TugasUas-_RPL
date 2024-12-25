<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../db/config.php';

// Debug session
echo "Status Session:<br>";
var_dump($_SESSION);
echo "<br><br>";

// Cek role admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Role tidak sesuai: " . ($_SESSION['role'] ?? 'tidak ada role');
    header('Location: index.php');
    exit('Akses ditolak!');
}

// Cek ID film
if (!isset($_GET['id'])) {
    die("ID film tidak ditemukan dalam URL");
}

$id = $_GET['id'];
echo "ID Film yang akan dihapus: " . $id . "<br><br>";

// Hapus booking terkait terlebih dahulu
$queryDeleteBookings = "DELETE FROM bookings WHERE film_id = ?";
$stmtDeleteBookings = $conn->prepare($queryDeleteBookings);
if (!$stmtDeleteBookings) {
    die("Error prepare delete bookings: " . $conn->error);
}

$stmtDeleteBookings->bind_param("i", $id);
if (!$stmtDeleteBookings->execute()) {
    die("Error saat menghapus bookings: " . $stmtDeleteBookings->error);
}

// Query pertama - ambil data gambar
$query = "SELECT gambar FROM films WHERE id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Error prepare statement: " . $conn->error);
}

$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
    die("Error execute statement: " . $stmt->error);
}

$result = $stmt->get_result();
if (!$result || mysqli_num_rows($result) == 0) {
    die("Film dengan ID $id tidak ditemukan");
}

$row = $result->fetch_assoc();
$gambar = $row['gambar'];
echo "Nama file gambar: " . $gambar . "<br><br>";

// Query kedua - hapus dari database
$queryDelete = "DELETE FROM films WHERE id = ?";
$stmtDelete = $conn->prepare($queryDelete);
if (!$stmtDelete) {
    die("Error prepare delete statement: " . $conn->error);
}

$stmtDelete->bind_param("i", $id);
if (!$stmtDelete->execute()) {
    die("Error saat menghapus dari database: " . $stmtDelete->error);
}

// Hapus file gambar
$filePath = "../img/" . $gambar;
echo "Path file yang akan dihapus: " . $filePath . "<br>";
echo "File exists: " . (file_exists($filePath) ? 'Ya' : 'Tidak') . "<br>";

if (file_exists($filePath)) {
    if (!is_writable($filePath)) {
        die("File tidak bisa dihapus (permission denied)");
    }

    if (!unlink($filePath)) {
        die("Gagal menghapus file gambar: " . error_get_last()['message']);
    }

    echo "File berhasil dihapus<br>";
    header('Location: ../index.php');
    exit();
} else {
    echo "File gambar tidak ditemukan di server<br>";
    header('Location: ../index.php');
    exit();
}
