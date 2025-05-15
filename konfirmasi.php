<?php
session_start();
include "config.php";

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}

// Ambil data konfirmasi terakhir user
$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM konfirmasi_pembayaran WHERE id_user = $user_id ORDER BY id_konfirmasi DESC LIMIT 1");

$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <h2 class="mb-4">Konfirmasi Pesanan</h2>

    <?php if ($data): ?>
        <p><strong>Nama:</strong> <?= htmlspecialchars($data['nama_lengkap']) ?></p>
        <p><strong>No Telepon:</strong> <?= htmlspecialchars($data['no_telepon']) ?></p>
        <p><strong>Alamat:</strong> <?= htmlspecialchars($data['alamat']) ?></p>
        <p><strong>Jumlah Transfer:</strong> Rp <?= number_format($data['jumlah'], 0, ',', '.') ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($data['status']) ?></p>
        <p><strong>Bukti Transfer:</strong><br>
        <img src="bukti_transfer/<?= htmlspecialchars($data['bukti_transfer']) ?>" alt="Bukti Transfer" width="300">
        </p>
    <?php else: ?>
        <div class="alert alert-warning">Belum ada konfirmasi pesanan ditemukan.</div>
    <?php endif; ?>
<div class="d-flex justify-content-between align-items-center mt-4">
    <a href="index.php" class="btn btn-secondary">&larr; Kembali Belanja</a>    
</div>
</div>

</body>
</html>
