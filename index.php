<?php
session_start(); // Memulai session
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>tiketBioskop</title>
    <?php include 'layout/header.html'; ?>

    <style>
        .movie-card {
            display: flex;
            flex-direction: column;
            width: 200px;
            margin: 1rem;
            padding: 1rem;
            border: 2px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .movie-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            gap: 1em;
            padding: 2em 0;
        }

        .movie-card:hover {
            transform: scale(1.05);
        }

        .movie-card img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .movie-info {
            padding-top: 1rem;
            text-align: center;
        }

        .movie-info h3 {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }

        .movie-info p {
            font-size: 0.9rem;
            color: #555;
        }

        .movie-info a {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .movie-info a:hover {
            background-color: #218838;
        }

        .no-result {
            width: 100%;
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }

        .movie-card {
            display: flex;
            /* default display */
        }
    </style>
</head>

<body>
    <!-- kode slide film  -->
    <div class="carousel">
        <img id="carouselImg" src="posterSlide/tranformersONE.png" alt="Movie Poster" />
    </div>

    <!-- fitur search film -->
    <div class="search-box-container">
        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Search Movie" onkeyup="searchMovie(this.value)" />
        </div>
    </div>
    <div id="movieResults"></div>

    <!-- kode untuk menampilkan film -->
    <div class="container">
        <section class="container">
            <div class="movie-list">
                <?php
                // Koneksi database
                require_once 'db/config.php';

                // Query untuk mengambil data film yang sedang tayang
                $query = "SELECT * FROM films WHERE status = 'showing'";
                $result = mysqli_query($conn, $query);

                if (!$result) {
                    die("Query gagal: " . mysqli_error($conn));
                }

                if (mysqli_num_rows($result) === 0) {
                    echo "<p>Tidak ada film yang sedang tayang.</p>";
                } else {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='movie-card'>";
                        echo "<img src='img/" . htmlspecialchars($row['gambar']) . "' alt='Poster Film' class='movie-poster'>";
                        echo "<div class='movie-info'>";
                        echo "<h3>" . htmlspecialchars($row['judul']) . "</h3>";
                        echo "<p>Durasi: " . htmlspecialchars($row['durasi']) . " menit</p>";
                        echo "<p>Harga: Rp. " . number_format($row['harga'], 2, ',', '.') . "</p>";

                        // Tampilkan tombol Pesan Tiket hanya untuk user
                        if (isset($_SESSION['role']) && $_SESSION['role'] === 'user') {
                            echo "<a href='pesanTiket.php?id=" . $row['id'] . "' class='btn btn-success'>Pesan Tiket</a>";
                        }

                        // Tampilkan tombol Hapus hanya untuk admin
                        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                            echo "<a href='film/deletefilm.php?id=" . $row['id'] . "' class='btn btn-danger' onclick=\"return confirm('Yakin ingin menghapus film ini?');\">Hapus</a>";
                        }
                        echo "</div>";
                        echo "</div>";
                    }
                }
                ?>
            </div>
        </section>
    </div>
    <div>
        <?php include 'layout/footer.html'; ?>
    </div>

    <script>
        // Array Contoh Film
        function searchMovie(searchValue) {
            const movieContainer = document.querySelector(".movie-list");

            // Ambil semua movie card yang ada
            const movieCards = document.querySelectorAll('.movie-card');

            // Ubah input pencarian menjadi lowercase
            searchValue = searchValue.toLowerCase();

            // Loop setiap movie card
            movieCards.forEach(card => {
                // Ambil judul film dari card
                const title = card.querySelector('.movie-info h3').textContent.toLowerCase();

                // Cek apakah judul mengandung kata yang dicari
                if (title.includes(searchValue)) {
                    card.style.display = 'flex'; // Tampilkan card jika sesuai
                } else {
                    card.style.display = 'none'; // Sembunyikan card jika tidak sesuai
                }
            });

            // Cek apakah ada film yang ditampilkan
            const visibleMovies = document.querySelectorAll('.movie-card[style="display: flex;"]');
            if (visibleMovies.length === 0 && searchValue !== '') {
                const noResult = document.createElement('p');
                noResult.textContent = "Tidak ada film yang ditemukan";
                noResult.className = "no-result";

                // Hapus pesan "tidak ditemukan" sebelumnya jika ada
                const existingNoResult = document.querySelector('.no-result');
                if (existingNoResult) {
                    existingNoResult.remove();
                }

                movieContainer.appendChild(noResult);
            } else {
                // Hapus pesan "tidak ditemukan" jika ada hasil
                const noResult = document.querySelector('.no-result');
                if (noResult) {
                    noResult.remove();
                }
            }
        }

        // Carousel Poster Film
        const posters = ["posterSlide/cars.png", "posterSlide/DespicableMe.png", "posterSlide/insideout2feature.jpeg 1.png", "posterSlide/interstellar.png", "posterSlide/Pacific rim 1.png", "posterSlide/SpiderVerse.png", "posterSlide/tranformersONE.png", "posterSlide/up potong rpl.png"]; //  gambar yang akan tampil di slide
        let currentIndex = 0;

        function changePoster() {
            currentIndex = (currentIndex + 1) % posters.length;
            document.getElementById("carouselImg").src = posters[currentIndex];
        }

        setInterval(changePoster, 3000); // Ubah poster setiap 3 detik
    </script>
</body>

</html>