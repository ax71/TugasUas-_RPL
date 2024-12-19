<?php
// Memulai session
session_start();
if (session_status() !== PHP_SESSION_ACTIVE) {
    exit('Session tidak aktif.');
}

// Memastikan file config.php ada
$configPath = __DIR__ . '/db/config.php'; // Path yang benar ke config.php
if (!file_exists($configPath)) {
    exit('File config.php tidak ditemukan.');
}
require_once $configPath;

// Hapus session
session_unset();
session_destroy();

// Redirect ke halaman login
header("Location: login.php");
exit();
