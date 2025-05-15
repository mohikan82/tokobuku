<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}

$id_user = $_SESSION['user_id'];

$query = mysqli_query($conn, "
    SELECT 
        p.id_pesanan, 
        p.created_at, 
        p.status, 
        SUM(dp.jumlah * dp.harga_satuan) AS total
    FROM pesanan p
    INNER JOIN detail_pesanan dp ON p.id_pesanan = dp.id_pesanan
    WHERE p.id_user = $id_user
    GROUP BY p.id_pesanan
    ORDER BY p.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h4 class="mb-4">Riwayat Pemesanan Anda</h4>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID Pesanan</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($query)): ?>
                <tr>
                    <td><?= $row['id_pesanan'] ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                    <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                    <td><?= $row['status'] ?></td>
                    <td>
                        <a href="detail_pesanan.php?id=<?= $row['id_pesanan'] ?>" class="btn btn-info btn-sm">Lihat Detail</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="index.php" class="btn btn-secondary mt-3">
        &larr; Kembali Belanja
    </a>
</div>

</body>
</html>
