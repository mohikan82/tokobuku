<?php
session_start();
include "config.php";

// Redirect jika belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}

// Ambil data user
$user_query = mysqli_query($conn, "SELECT * FROM user WHERE id_user = {$_SESSION['user_id']}");
$user_data = mysqli_fetch_assoc($user_query);

// Ambil data keranjang user
$cart_query = mysqli_query($conn, "
    SELECT produk.*, keranjang.jumlah 
    FROM keranjang 
    JOIN produk ON keranjang.id_produk = produk.id_produk 
    WHERE keranjang.id_user = {$_SESSION['user_id']}
");

// Hitung total harga
$total = 0;
$items = [];
while ($row = mysqli_fetch_assoc($cart_query)) {
    $total += $row['harga'] * $row['jumlah'];
    $items[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <h2 class="mb-4">Checkout</h2>

    <div class="section-title">Ringkasan Pesanan</div>
    <div class="summary-box">
        <?php foreach ($items as $item): ?>
            <p>
                <?= htmlspecialchars($item['nama_produk']) ?> (<?= $item['jumlah'] ?>x) - Rp <?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?>
            </p>
        <?php endforeach; ?>
        <p><strong>Total Harga: Rp <?= number_format($total, 0, ',', '.') ?></strong></p>
    </div>

    <form action="proses_checkout.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama" name="nama_lengkap" value="<?= htmlspecialchars($user_data['nama_lengkap'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="telepon" class="form-label">Nomor Telepon</label>
            <input type="tel" class="form-control" id="telepon" name="no_telepon" value="<?= htmlspecialchars($user_data['no_telepon'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat Lengkap</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?= htmlspecialchars($user_data['alamat'] ?? '') ?></textarea>
        </div>

        <input type="hidden" name="metode_pembayaran" value="transfer_bank">

        <div class="section-title">Instruksi Pembayaran</div>
        <div class="payment-box">
            <div class="bank-info">
                Silakan transfer ke rekening berikut:<br>
                <strong>Bank:</strong> Bank ABC<br>
                <strong>Nomor Rekening:</strong> 1234 5678 9012<br>
                <strong>Atas Nama:</strong> Nama Toko Anda<br>
                <strong>Jumlah:</strong> Rp <?= number_format($total, 0, ',', '.') ?><br>
                <strong>Kode Referensi:</strong> ORDER-<?= time() ?>
            </div>
        </div>

        <div class="mb-3">
            <label for="bukti" class="form-label">Upload Bukti Transfer (JPG/PNG, max 2MB)</label>
            <input class="form-control" type="file" id="bukti" name="bukti_transfer" accept=".jpg,.jpeg,.png" required>
        </div>

        <button type="submit" class="btn btn-success">Konfirmasi Pesanan</button>
    </form>
</div>

</body>
</html>