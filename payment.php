<?php
session_start();
require_once 'db/config.php';

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Validasi booking_id
if (!isset($_GET['booking_id']) || empty($_GET['booking_id'])) {
    $_SESSION['error'] = "Booking ID tidak valid.";
    header('Location: profile.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$booking_id = $_GET['booking_id'];

// Ambil detail pesanan
$query = "SELECT b.*, f.judul FROM bookings b 
          JOIN films f ON b.film_id = f.id 
          WHERE b.id = ? AND b.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Pesanan tidak ditemukan.";
    header('Location: profile.php');
    exit();
}

$booking = $result->fetch_assoc();

// Jika tombol Bayar ditekan
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
) {
    try {
        // Update status pesanan menjadi "paid" saja (tanpa payment_method)
        $update_query = "UPDATE bookings SET status = 'paid' WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);

        if (!$update_stmt) {
            throw new Exception("Prepare statement error: " . $conn->error);
        }

        $update_stmt->bind_param("i", $booking_id);

        if (!$update_stmt->execute()) {
            throw new Exception("Execute error: " . $update_stmt->error);
        }

        $_SESSION['success'] = "Pembayaran berhasil! Status pesanan telah diperbarui.";
        header('Location: profile.php');
        exit();
    } catch (Exception $e) {
        error_log("Payment Error: " . $e->getMessage());
        $_SESSION['error'] = "Terjadi kesalahan saat memproses pembayaran.";
        header('Location: profile.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <style>
        .payment-container {
            max-width: 400px;
            margin: 3rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
        }

        .payment-container h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #000;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .payment-container p {
            font-size: 1rem;
            color: #333;
            margin-bottom: 1rem;
        }

        .payment-container strong {
            font-weight: bold;
        }

        .payment-container button {
            background-color: #1a1a3e;
            color: #fff;
            border: none;
            width: 100%;
            padding: 0.8rem;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            margin-top: 1rem;
        }

        .payment-container button:hover {
            background-color: #2b2b5c;
        }

        .cancel-link {
            text-align: center;
            margin-top: 1rem;
        }

        .cancel-link a {
            color: #5c00b3;
            text-decoration: none;
            font-weight: 600;
        }

        .cancel-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="payment-container">
        <h2>Pembayaran Tiket</h2>
        <p><strong>Film:</strong> <?php echo htmlspecialchars($booking['judul']); ?></p>
        <p><strong>Jumlah Tiket:</strong> <?php echo $booking['jumlah_tiket']; ?></p>
        <p><strong>Total Harga:</strong> Rp <?php echo number_format($booking['total_harga'], 0, ',', '.'); ?></p>

        <form method="POST" action="">
            <p>Terimakasih sudah memesan tiket di booTIK_ID! 😊</p>
            <button type="submit">Konfirmasi Pembayaran</button>
        </form>
        <div class="cancel-link">
            <a href="profile.php">Batal</a>
        </div>
    </div>
</body>

</html>