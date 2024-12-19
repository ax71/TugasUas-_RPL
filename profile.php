<?php
session_start();
require_once 'db/config.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil data user
$user_id = $_SESSION['user_id'];
$query_user = "SELECT username, email FROM users WHERE id = ?";
$stmt_user = $conn->prepare($query_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user_result = $stmt_user->get_result();
$user_data = $user_result->fetch_assoc();

// Ambil data booking user (hanya untuk user biasa)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    $query_booking = "SELECT b.*, f.judul, f.gambar 
                     FROM bookings b 
                     JOIN films f ON b.film_id = f.id 
                     WHERE b.user_id = ? 
                     ORDER BY b.created_at DESC";

    $stmt_booking = $conn->prepare($query_booking);
    $stmt_booking->bind_param("i", $user_id);
    $stmt_booking->execute();
    $booking_result = $stmt_booking->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <?php include 'layout/header.html'; ?>
    <style>
        .profile-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #28a745;
        }

        .profile-info h1 {
            margin: 0;
            color: #333;
            font-size: 1.8rem;
        }

        .profile-info p {
            margin: 0.5rem 0;
            color: #666;
        }

        /* Style untuk User */
        .booking-history {
            margin-top: 2rem;
        }

        .booking-card {
            background: white;
            border: 1px solid #eee;
            border-radius: 8px;
            margin-bottom: 1rem;
            padding: 1rem;
            display: flex;
            gap: 1rem;
            transition: transform 0.2s;
        }

        .booking-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .movie-poster {
            width: 100px;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
        }

        .booking-details {
            flex: 1;
        }

        .status-pending {
            color: #f0ad4e;
        }

        .status-paid {
            color: #5cb85c;
        }

        .status-cancelled {
            color: #d9534f;
        }

        .booking-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        /* Style untuk tombol */
        .btn-bayar,
        .btn-batal {
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 1rem;
            margin-right: 0.5rem;
            transition: background-color 0.2s;
        }

        .btn-bayar {
            background: #28a745;
        }

        .btn-bayar:hover {
            background: #218838;
        }

        .btn-batal {
            background: #dc3545;
        }

        .btn-batal:hover {
            background: #c82333;
        }

        /* Style untuk Admin Dashboard */
        .admin-dashboard {
            margin-top: 2rem;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .admin-dashboard h2 {
            color: #333;
            margin-bottom: 1rem;
        }

        .admin-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .admin-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .admin-card h3 {
            margin-bottom: 1rem;
            color: #333;
        }

        .btn-admin {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: #1a1a3e;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .btn-admin:hover {
            background: #2b2b5c;
        }
    </style>
</head>

<body>
    <div class="profile-container">
        <?php if (isset($_SESSION['success'])): ?>
            <div style="color: green; padding: 1rem; background: #d4edda; border-radius: 4px; margin-bottom: 1rem;">
                <?php echo $_SESSION['success'];
                unset($_SESSION['success']); ?>
            </div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div style="color: #721c24; padding: 1rem; background: #f8d7da; border-radius: 4px; margin-bottom: 1rem;">
                <?php echo $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="profile-header">
            <img src="assets/profile.jpg" alt="Profile Picture" class="profile-image">
            <div class="profile-info">
                <h1><?php echo htmlspecialchars($user_data['username']); ?></h1>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user_data['email']); ?></p>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <p><strong>Role:</strong> Administrator</p>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'): ?>
            <div class="booking-history">
                <h2>Riwayat Pemesanan</h2>

                <?php if ($booking_result->num_rows > 0): ?>
                    <?php while ($booking = $booking_result->fetch_assoc()): ?>
                        <div class="booking-card">
                            <img src="img/<?php echo htmlspecialchars($booking['gambar']); ?>"
                                alt="<?php echo htmlspecialchars($booking['judul']); ?>"
                                class="movie-poster">

                            <div class="booking-details">
                                <h3><?php echo htmlspecialchars($booking['judul']); ?></h3>

                                <div class="booking-info">
                                    <p>
                                        <strong>Status:</strong>
                                        <span class="status-<?php echo $booking['status']; ?>">
                                            <?php echo ucfirst($booking['status']); ?>
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Jumlah Tiket:</strong>
                                        <?php echo $booking['jumlah_tiket']; ?>
                                    </p>
                                    <p>
                                        <strong>Total Harga:</strong>
                                        Rp <?php echo number_format($booking['total_harga'], 0, ',', '.'); ?>
                                    </p>
                                    <p>
                                        <strong>Tanggal Pesan:</strong>
                                        <?php echo date('d/m/Y H:i', strtotime($booking['created_at'])); ?>
                                    </p>
                                </div>

                                <?php if ($booking['status'] === 'pending'): ?>
                                    <button class="btn-bayar" onclick="window.location.href='payment.php?booking_id=<?php echo $booking['id']; ?>'">
                                        Bayar Sekarang
                                    </button>
                                    <button class="btn-batal" onclick="window.location.href='cancel_booking.php?booking_id=<?php echo $booking['id']; ?>'">
                                        Batalkan Pesanan
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Belum ada riwayat pemesanan.</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
                <div class="admin-actions">
                    <a href="film/addfilm.php" class="btn-admin">Tambah Film Baru</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'layout/footer.html'; ?>
</body>

</html>