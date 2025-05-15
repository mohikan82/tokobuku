
<?php
session_start();
include "../config.php";

$id_pesanan = (int)$_GET['id'];

$query_pesanan = mysqli_query($conn, "
    SELECT pesanan.*, user.username 
    FROM pesanan 
    JOIN user ON pesanan.id_user = user.id_user
    WHERE pesanan.id_pesanan = $id_pesanan
");
$pesanan = mysqli_fetch_assoc($query_pesanan);
if (!$pesanan) {
    echo "<div class='alert alert-danger'>Pesanan tidak ditemukan.</div>";
    exit;
}

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
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .status-pending {
            color: #e67e22;
            font-weight: bold;
        }
        .status-verified {
            color: #2ecc71;
            font-weight: bold;
        }
        img.bukti-transfer {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        .table img {
            max-width: 50px;
        }
    </style>
</head>

<body>
<div class="container">
    <h2 class="mb-4">Detail Pesanan #<?= $id_pesanan ?></h2>

    <div class="mb-4">
        <h5>Informasi Pesanan</h5>
        <p><strong>Pelanggan:</strong> <?= $pesanan['username'] ?></p>
        <p><strong>Tanggal Pesan:</strong> <?= date('d/m/Y H:i', strtotime($pesanan['created_at'])) ?></p>
        <p><strong>Status:</strong>
            <span class="<?= ($pesanan['status'] == 'menunggu_verifikasi') ? 'status-pending' : 'status-verified' ?>">
                <?= ucfirst(str_replace('_', ' ', $pesanan['status'])) ?>
            </span>
        </p>
        <p><strong>Total:</strong> Rp <?= number_format($pesanan['total'], 0, ',', '.') ?></p>
        <p><strong>Alamat Pengiriman:</strong><br><?= nl2br($pesanan['alamat']) ?></p>
    </div>

    <div class="mb-4">
        <h5>Bukti Transfer</h5>
        <?php if ($pesanan['bukti_transfer']): ?>
            <img src="../bukti_transfer/<?= $pesanan['bukti_transfer'] ?>" alt="Bukti Transfer" class="bukti-transfer mb-2">
            <p><a href="../bukti_transfer/<?= $pesanan['bukti_transfer'] ?>" class="btn btn-sm btn-primary" download>Download Bukti</a></p>
        <?php else: ?>
            <p class="text-muted">Belum mengupload bukti transfer.</p>
        <?php endif; ?>
    </div>

    <div class="mb-4">
        <h5>Item Pesanan</h5>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>Produk</th>
                    <th>Gambar</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
            <tbody>
    <?php while ($item = mysqli_fetch_assoc($query_items)): ?>
        <tr>
            <td><?= $item['nama_produk'] ?></td>
            <td><img src="../uploads/<?= $item['gambar'] ?>" alt="Gambar Produk"></td>
            <td>Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?></td>
            <td><?= $item['jumlah'] ?></td>
            <td>Rp <?= number_format($item['harga_satuan'] * $item['jumlah'], 0, ',', '.') ?></td>
        </tr>
    <?php endwhile; ?>

    <?php
    // Hitung total keseluruhan dan tampilkan
    mysqli_data_seek($query_items, 0); // reset ulang pointer
    $total_item = 0;
    while ($item = mysqli_fetch_assoc($query_items)) {
        $total_item += $item['harga_satuan'] * $item['jumlah'];
    }
    ?>
    <tr>
        <td colspan="4" class="text-end"><strong>Total Keseluruhan</strong></td>
        <td><strong>Rp <?= number_format($total_item, 0, ',', '.') ?></strong></td>
    </tr>
</tbody>

            </tbody>
            
        </table>
    </div>

    <a href="index.php" class="btn btn-secondary">&laquo; Kembali ke Dashboard</a>
</div>
</body>
</html>
