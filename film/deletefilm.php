<?php
session_start();
require_once '../db/config.php';

// Periksa apakah pengguna sudah login dan memiliki role admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit('Akses ditolak!');
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Menggunakan prepared statements untuk mencegah SQL injection
    $query = "SELECT gambar FROM films WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id); // Mengikat parameter (integer)
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && mysqli_num_rows($result) > 0) {
        $row = $result->fetch_assoc();
        $gambar = $row['gambar'];

        // Hapus film dari database menggunakan prepared statement
        $queryDelete = "DELETE FROM films WHERE id = ?";
        $stmtDelete = $conn->prepare($queryDelete);
        $stmtDelete->bind_param("i", $id); // Mengikat parameter (integer)

        if ($stmtDelete->execute()) {
            // Hapus file gambar dari server jika file ada
            $filePath = "../img/" . $gambar;
            if (file_exists($filePath)) {
                if (unlink($filePath)) {
                    // Redirect jika sukses menghapus file dan data film
                    header('Location: ../index.php');
                    exit();
                } else {
                    echo "Gagal menghapus file gambar.";
                }
            } else {
                echo "File gambar tidak ditemukan.";
            }
        } else {
            echo "Error: " . $stmtDelete->error;
        }
    } else {
        echo "Film tidak ditemukan.";
    }
} else {
    echo "ID film tidak ditemukan.";
}
