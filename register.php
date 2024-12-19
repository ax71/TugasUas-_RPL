<?php
session_start();
require_once 'db/config.php';

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek username sudah ada atau belum
    $check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $error = "Username atau email sudah terdaftar!";
    } else {
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Registrasi berhasil! Silahkan login.";
            header("Location: login.php");
        } else {
            $error = "Terjadi kesalahan! Silahkan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - tiketBioskop</title>
    <?php include 'layout/header.html'; ?>
</head>

<body>
    <div class="container">
        <div class="register-form">
            <h2>Registrasi</h2>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" name="register" class="btn btn-primary">Daftar</button>
            </form>
            <p>Sudah punya akun? <a href="login.php">Login disini</a></p>
        </div>
    </div>
    <?php include 'layout/footer.html'; ?>
</body>

</html>