<?php
session_start();
require_once '../db/config.php';

// Periksa apakah pengguna sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit('Akses ditolak!');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Film</title>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Global Style */
        :root {
            --navy-blue: #1B1B3A;
            --soft-white: #F5F5F7;
            --pure-white: #FFFFFF;
            --grey-blue: #8E8EA0;
            --border-grey: #E0E0E0;
            --dark-grey: #333333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: var(--soft-white);
            color: var(--dark-grey);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 2rem;
        }

        .container {
            background-color: var(--pure-white);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h1 {
            font-size: 1.8rem;
            color: var(--navy-blue);
            margin-bottom: 1.5rem;
            font-weight: 700;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            text-align: left;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid var(--border-grey);
            border-radius: 6px;
            font-size: 1rem;
            background-color: #FAFAFA;
            color: var(--dark-grey);
            transition: border 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: var(--navy-blue);
            box-shadow: 0 0 0 2px rgba(27, 27, 58, 0.2);
        }

        button {
            background: var(--navy-blue);
            color: var(--pure-white);
            border: none;
            border-radius: 6px;
            padding: 0.8rem;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        textarea {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid var(--border-grey);
            border-radius: 6px;
            font-size: 1rem;
            background-color: #FAFAFA;
            color: var(--dark-grey);
            transition: border 0.3s ease;
            resize: vertical;
            min-height: 100px;
            font-family: 'Poppins', Arial, sans-serif;
        }

        textarea:focus {
            outline: none;
            border-color: var(--navy-blue);
            box-shadow: 0 0 0 2px rgba(27, 27, 58, 0.2);
        }

        button:hover {
            background-color: #29294A;
        }

        .alert {
            margin-top: 1rem;
            padding: 0.8rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .footer {
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: var(--grey-blue);
        }

        .back-link {
            display: inline-block;
            margin-top: 1rem;
            color: var(--navy-blue);
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s ease;
        }

        .back-link:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Tambah film</h1>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <div class="alert alert-success">Film berhasil ditambahkan!</div>
        <?php endif; ?>

        <form action="processfilm.php" method="post" enctype="multipart/form-data" id="filmForm">
            <label for="judul">Judul Film:</label>
            <input type="text" name="judul" id="judul" required>

            <label for="deskripsi">Deskripsi Film:</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" required></textarea>

            <label for="durasi">Durasi (menit):</label>
            <input type="text" name="durasi" id="durasi" min="1" required>

            <label for="harga">Harga Tiket:</label>
            <input type="number" name="harga" id="harga" min="0" step="1000" required>

            <label for="gambar">Upload Poster:</label>
            <input type="file" name="gambar" id="gambar" accept="image/*" required>

            <button type="submit">Tambah Film</button>
        </form>

        <a href="../index.php" class="back-link">Kembali ke Beranda</a>
    </div>

    <script>
        document.getElementById('filmForm').addEventListener('submit', function(e) {
            // Validasi file
            const file = document.getElementById('gambar').files[0];
            const maxSize = 2 * 1024 * 1024; // 2MB

            if (file && file.size > maxSize) {
                e.preventDefault();
                alert('Ukuran file terlalu besar. Maksimal 2MB');
                return;
            }

            // Validasi durasi
            const durasi = document.getElementById('durasi').value;
            if (durasi <= 0) {
                e.preventDefault();
                alert('Durasi harus lebih dari 0 menit');
                return;
            }

            // Validasi harga
            const harga = document.getElementById('harga').value;
            if (harga <= 0) {
                e.preventDefault();
                alert('Harga tidak boleh 0 atau negatif');
                return;
            }
        });
    </script>
</body>

</html>