<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}

$id_pesanan = (int)$_GET['id'];

// Validasi pesanan milik user
$query_pesanan = mysqli_query($conn, "
    SELECT * FROM pesanan 
    WHERE id_pesanan = $id_pesanan 
    AND id_user = {$_SESSION['user_id']}
");
$pesanan = mysqli_fetch_assoc($query_pesanan);

if (!$pesanan) {
    die("Pesanan tidak ditemukan atau bukan milik Anda.");
}

// Ambil item pesanan
$query_items = mysqli_query($conn, "
    SELECT detail_pesanan.*, produk.nama_produk, produk.gambar
    FROM detail_pesanan
    JOIN produk ON detail_pesanan.id_produk = produk.id_produk
    WHERE detail_pesanan.id_pesanan = $id_pesanan
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .status-pending { color: #e67e22; }
        .status-diproses { color: #3498db; }
        .status-dikirim { color: #2ecc71; }
        .status-selesai { color: #27ae60; }
        .card-img-top { width: 100%; height: 150px; object-fit: cover; }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4">Detail Pesanan #<?= $pesanan['id_pesanan'] ?></h2>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Informasi Pesanan</div>
        <div class="card-body">
            <p><strong>Tanggal:</strong> <?= date('d/m/Y H:i', strtotime($pesanan['created_at'])) ?></p>
            <p><strong>Status:</strong> 
                <span class="status-<?= $pesanan['status'] ?>">
                    <?php
                    $status = [
                        'pending' => 'Pending',
                        'diproses' => 'Diproses',
                        'dikirim' => 'Dikirim',
                        'selesai' => 'Selesai'
                    ];
                    echo $status[$pesanan['status']] ?? ucfirst($pesanan['status']);
                    ?>
                </span>
            </p>
            <p><strong>Total:</strong> Rp <?= number_format($pesanan['total'], 0, ',', '.') ?></p>
            <p><strong>Alamat Pengiriman:</strong><br><?= nl2br(htmlspecialchars($pesanan['alamat'])) ?></p>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-secondary text-white">Item Pesanan</div>
        <div class="card-body">
            <?php if (mysqli_num_rows($query_items) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($item = mysqli_fetch_assoc($query_items)): ?>
                                <tr>
                                    <td><img src="admin/uploads/<?= htmlspecialchars($item['gambar']) ?>" width="60" height="60"></td>
                                    <td><?= htmlspecialchars($item['nama_produk']) ?></td>
                                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                    <td><?= $item['jumlah'] ?></td>
                                    <td>Rp <?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">Tidak ada item dalam pesanan ini.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="index.php" class="btn btn-outline-secondary">&laquo; Kembali ke Beranda</a>
    </div>
</div>

</body>
</html>
