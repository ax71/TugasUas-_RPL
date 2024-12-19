<?php
session_start();
require_once '../db/config.php';
// Debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cek koneksi database
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form dan lakukan sanitasi
    $judul = htmlspecialchars($_POST['judul']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']); // Tambahkan ini
    $durasi = intval($_POST['durasi']);
    $harga = floatval($_POST['harga']);
    $status = 'coming_soon';
    $created_at = date('Y-m-d H:i:s');

    // Validasi input
    if (empty($judul) || empty($deskripsi) || $durasi <= 0 || $harga <= 0) { // Tambahkan validasi deskripsi
        die("Semua field harus diisi dengan benar.");
    }

    // Cek file yang diupload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['gambar']['tmp_name'];
        $fileName = basename($_FILES['gambar']['name']);
        $uploadFolder = '../img/uploads/';

        // Pastikan folder "uploads" ada
        if (!is_dir($uploadFolder)) {
            mkdir($uploadFolder, 0777, true);
        }

        // Menghasilkan nama file unik
        $newFileName = uniqid() . "-" . $fileName;
        $dest_path = $uploadFolder . $newFileName;

        // Validasi ekstensi gambar
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedExtensions)) {
            die("Hanya file JPG, JPEG, dan PNG yang diperbolehkan.");
        }

        // Validasi tipe MIME
        $fileMimeType = mime_content_type($fileTmpPath);
        if (strpos($fileMimeType, 'image') === false) {
            die("File yang diupload bukan gambar.");
        }

        // Cek ukuran file (maksimal 2MB)
        if ($_FILES['gambar']['size'] > 2 * 1024 * 1024) {
            die("Ukuran file gambar terlalu besar. Maksimal 2MB.");
        }

        // Memindahkan file yang diupload
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Jika upload sukses, simpan ke database
            $query = "INSERT INTO films (judul, deskripsi, durasi, harga, status, created_at, gambar) VALUES (?, ?, ?, ?, ?, ?, ?)"; // Tambahkan deskripsi
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                die("Error in prepare: " . $conn->error);
            }

            // Bind parameter dan eksekusi query
            $stmt->bind_param("sssdsss", $judul, $deskripsi, $durasi, $harga, $status, $created_at, $dest_path); // Tambahkan deskripsi

            if ($stmt->execute()) {
                // Mengubah status film menjadi 'showing'
                $film_id = $stmt->insert_id;
                $updateQuery = "UPDATE films SET status = 'showing' WHERE id = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("i", $film_id);
                $updateStmt->execute();

                // Redirect jika sukses
                header("Location: addfilm.php?status=success");
                exit();
            } else {
                die("Error in execute: " . $stmt->error);
            }
        } else {
            die("Gagal memindahkan file yang diupload.");
        }
    } else {
        die("Error upload file: " . $_FILES['gambar']['error']);
    }
} else {
    die("Metode akses tidak valid.");
}
