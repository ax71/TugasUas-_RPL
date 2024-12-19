<?php
session_start();
require_once 'db/config.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$film_id = $_GET['id'];
$query = "SELECT * FROM films WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $film_id);
$stmt->execute();
$result = $stmt->get_result();
$film = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($film['judul']); ?></title>
    <?php include 'layout/header.html'; ?>

    <style>
        .movie-hero {
            position: relative;
            width: 100%;
            height: 800px;
            background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.9)),
                url('img/<?php echo $film['gambar']; ?>');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 2rem;
            display: flex;
            align-items: center;
        }

        .movie-content {
            display: flex;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .movie-poster {
            width: 300px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }

        .movie-info {
            flex: 1;
        }

        .movie-title {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .theater-badges {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .badge {
            background: #333;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .synopsis {
            margin: 2rem 0;
            font-size: 1.1rem;
            line-height: 1.6;
            max-width: 800px;
        }

        .duration {
            /* margin-left: 10px; */
            color: #ccc;
            font-size: 1.1rem;
        }

        .votes {
            color: #ccc;
        }

        .btn-book {
            background: #ffd700;
            color: #000;
            padding: 1rem 2rem;
            border: none;
            border-radius: 5px;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 15px;
        }

        .btn-cancel {
            padding: 1rem 2rem;
            border: none;
            border-radius: 5px;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-book:hover {
            background: #ffed4a;
            transform: translateY(-2px);
        }

        .btn-cancel:hover {
            transform: translateY(-2px);
        }

        .booking-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
        }

        .booking-content {
            background: white;
            max-width: 500px;
            margin: 50px auto;
            padding: 2rem;
            border-radius: 10px;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="movie-hero">
        <div class="movie-content">
            <img src="img/<?php echo htmlspecialchars($film['gambar']); ?>" alt="<?php echo htmlspecialchars($film['judul']); ?>" class="movie-poster">
            <div class="movie-info">
                <h1 class="movie-title"><?php echo htmlspecialchars($film['judul']); ?></h1>

                <div class="theater-badges">
                    <span class="badge">Gedung A</span>
                    <span class="badge">Gedung B</span>
                    <span class="badge">Gedung C</span>
                </div>

                <p class="synopsis"><?php echo htmlspecialchars($film['deskripsi']); ?></p>

                <p class="duration"><?php echo htmlspecialchars($film['durasi']); ?> min</p>

                <button class="btn-book" onclick="openBooking()">BUY A TICKET</button>
            </div>
        </div>
    </div>

    <div id="bookingModal" class="booking-modal">
        <div class="booking-content">
            <h2>Book Tickets for <?php echo htmlspecialchars($film['judul']); ?></h2>
            <form id="bookingForm" action="process_booking.php" method="POST">
                <input type="hidden" name="film_id" value="<?php echo $film_id; ?>">
                <input type="hidden" name="harga" value="<?php echo $film['harga']; ?>">

                <div class="form-group">
                    <label for="jumlah_tiket">Number of Tickets:</label>
                    <input type="number" id="jumlah_tiket" name="jumlah_tiket" min="1" max="10" value="1" required>
                </div>

                <div class="total-price">
                    Total: Rp. <span id="totalHarga"><?php echo number_format($film['harga'], 2, ',', '.'); ?></span>
                </div>

                <button type="submit" class="btn-book">Confirm Booking</button>
                <button type="button" onclick="closeBooking()" class="btn-cancel">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function openBooking() {
            document.getElementById('bookingModal').style.display = 'block';
        }

        function closeBooking() {
            document.getElementById('bookingModal').style.display = 'none';
        }

        document.getElementById('jumlah_tiket').addEventListener('change', function() {
            const hargaTiket = <?php echo $film['harga']; ?>;
            const jumlahTiket = this.value;
            const totalHarga = hargaTiket * jumlahTiket;
            document.getElementById('totalHarga').textContent = totalHarga.toLocaleString('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        });
    </script>
    <div>
        <?php include 'layout/footer.html'; ?>
    </div>
</body>

</html>