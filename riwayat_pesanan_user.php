<?php
session_start();
include "config.php";

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}

$id_user = $_SESSION['user_id'];

// Jika ada permintaan untuk menghapus pesanan
if (isset($_GET['hapus']) && is_numeric($_GET['hapus'])) {
    $id_hapus = (int) $_GET['hapus'];

    // Pastikan pesanan milik user dan statusnya "selesai"
    $cek = mysqli_query($conn, "SELECT * FROM pesanan WHERE id_pesanan = $id_hapus AND id_user = $id_user AND status_pesanan = 'selesai'");
    if (mysqli_num_rows($cek) > 0) {
        // Hapus dari tabel detail pesanan terlebih dahulu
        mysqli_query($conn, "DELETE FROM pesanan_detail WHERE id_pesanan = $id_hapus");
        // Hapus dari tabel pesanan
        mysqli_query($conn, "DELETE FROM pesanan WHERE id_pesanan = $id_hapus");
        $pesan_sukses = "Pesanan berhasil dihapus.";
    }
}

// Ambil riwayat pesanan user
$query = mysqli_query($conn, "
    SELECT 
        p.id_pesanan, 
        p.created_at, 
        p.total, 
        p.status_pesanan
    FROM pesanan p
    WHERE p.id_user = $id_user
    ORDER BY p.created_at DESC
");

$pesanan_list = [];
while ($row = mysqli_fetch_assoc($query)) {
    $pesanan_list[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pesanan - Bhumida Bookstore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .status-pending { color: orange; font-weight: bold; }
        .status-diproses { color: blue; font-weight: bold; }
        .status-dikirim { color: green; font-weight: bold; }
        .status-selesai { color: gray; font-weight: bold; }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Riwayat Pesanan Anda</h2>

        <?php if (isset($pesan_sukses)): ?>
            <div class="alert alert-success text-center"><?php echo $pesan_sukses; ?></div>
        <?php endif; ?>

        <?php if (!empty($pesanan_list)): ?>
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Tanggal Pesanan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pesanan_list as $pesanan): ?>
                        <tr>
                            <td><?php echo $pesanan['id_pesanan']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($pesanan['created_at'])); ?></td>
                            <td>Rp <?php echo number_format($pesanan['total'], 0, ',', '.'); ?></td>
                            <td class="status-<?php echo str_replace('_', '-', $pesanan['status_pesanan']); ?>">
                                <?php
                                $status = [
                                    'pending' => 'Pending',
                                    'diproses' => 'Diproses',
                                    'dikirim' => 'Dikirim',
                                    'selesai' => 'Selesai'
                                ];
                                echo $status[$pesanan['status_pesanan']] ?? $pesanan['status_pesanan'];
                                ?>
                            </td>
                            <td>
                                <a href="detail_pesanan_user.php?id=<?php echo $pesanan['id_pesanan']; ?>" class="btn btn-sm btn-primary">Detail</a>
                                <?php if ($pesanan['status_pesanan'] == 'selesai'): ?>
                                    <a href="?hapus=<?php echo $pesanan['id_pesanan']; ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Yakin ingin menghapus pesanan ini?');">
                                        Hapus
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info text-center">
                Belum ada riwayat pesanan.
            </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-secondary">← Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
