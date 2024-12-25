<?php
session_start();
require_once 'db/config.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($search)) {
    $search = "%{$search}%";
    $query = "SELECT * FROM films WHERE status = 'showing' AND (judul LIKE ? OR genre LIKE ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $search, $search);
} else {
    $query = "SELECT * FROM films WHERE status = 'showing'";
    $stmt = $conn->prepare($query);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Tidak ada film yang ditemukan.</p>";
} else {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='movie-card'>";
        echo "<img src='img/" . htmlspecialchars($row['gambar']) . "' alt='Poster Film' class='movie-poster'>";
        echo "<div class='movie-info'>";
        echo "<h3>" . htmlspecialchars($row['judul']) . "</h3>";
        echo "<p>Durasi: " . htmlspecialchars($row['durasi']) . " menit</p>";
        echo "<p>Harga: Rp. " . number_format($row['harga'], 2, ',', '.') . "</p>";

        if (isset($_SESSION['role']) && $_SESSION['role'] === 'user') {
            echo "<a href='pesanTiket.php?id=" . $row['id'] . "' class='btn btn-success'>Pesan Tiket</a>";
        }

        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            echo "<a href='film/deletefilm.php?id=" . $row['id'] . "' class='btn btn-danger' onclick=\"return confirm('Yakin ingin menghapus film ini?');\">Hapus</a>";
        }
        echo "</div>";
        echo "</div>";
    }
}

$stmt->close();
?>