<?php
session_start();
include "config.php";

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit();
}

// Validasi parameter ID pesanan
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id_pesanan = (int) $_GET['id'];
$id_user = $_SESSION['user_id'];

// Ambil data pesanan untuk validasi
$query = mysqli_query($conn, "SELECT * FROM pesanan WHERE id_pesanan = $id_pesanan AND id_user = $id_user LIMIT 1");
$pesanan = mysqli_fetch_assoc($query);

// Cek apakah pesanan ditemukan dan masih pending
if (!$pesanan) {
    $_SESSION['error'] = "Pesanan tidak ditemukan.";
    header("Location: index.php");
    exit();
}

if ($pesanan['status_pesanan'] !== 'pending') {
    $_SESSION['error'] = "Pesanan hanya bisa dibatalkan jika statusnya masih pending.";
    header("Location: index.php");
    exit();
}

// Update status pesanan jadi "dibatalkan"
$update = mysqli_query($conn, "UPDATE pesanan SET status_pesanan = 'dibatalkan' WHERE id_pesanan = $id_pesanan");

if ($update) {
    $_SESSION['success'] = "Pesanan berhasil dibatalkan.";
} else {
    $_SESSION['error'] = "Gagal membatalkan pesanan. Silakan coba lagi.";
}

header("Location: index.php");
exit();
