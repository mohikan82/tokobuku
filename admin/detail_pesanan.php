
<?php
session_start();
include "../config.php";

// Validasi parameter id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID pesanan tidak valid.</div>";
    exit;
}
$id_pesanan = (int)$_GET['id'];

//Query pesanan
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

$sql_items = "
    SELECT detail_pesanan.*, produk.nama_produk, produk.gambar_name
    FROM detail_pesanan
    JOIN produk ON detail_pesanan.id_produk = produk.id_produk
    WHERE detail_pesanan.id_pesanan = $id_pesanan
";
$query_items = mysqli_query($conn, $sql_items);

if (!$query_items) {
    echo "<div class='alert alert-danger'>Gagal mengambil data item pesanan: " . mysqli_error($conn) . "</div>";
    exit;
}

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
            max-width: 80px;
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
            <span class="<?= ($pesanan['status_pesanan'] == 'menunggu_verifikasi') ? 'status-pending' : 'status-verified' ?>">
                <?= ucfirst(str_replace('_', ' ', $pesanan['status_pesanan'])) ?>
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

    <div class="card">
        <div class="card-header bg-secondary text-white">Item Pesanan</div>
        <div class="card-body">
            <?php if (mysqli_num_rows($query_items) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Gambar</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            mysqli_data_seek($query_items, 0);
                            while ($item = mysqli_fetch_assoc($query_items)): ?>
                                <tr>
                                    <td><?= $item['nama_produk'] ?></td>
                                    <td><img src="./uploads/<?= htmlspecialchars($item['gambar_name']) ?>" width="80" height="80"></td>
                                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                    <td><?= $item['jumlah'] ?></td>
                                    <td>Rp <?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <?php  // Hitung total keseluruhan dan tampilkan
                            mysqli_data_seek($query_items, 0); // reset ulang pointer
                            $total_item = 0;
                            while ($item = mysqli_fetch_assoc($query_items)) {
                                $total_item += $item['harga'] * $item['jumlah'];
                            }
                            ?>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total Keseluruhan</strong></td>
                                <td><strong>Rp <?= number_format($total_item, 0, ',', '.') ?></strong></td>
                            </tr>   
                        </tfoot>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">Tidak ada item dalam pesanan ini.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="mt-4">
        <a href="index.php" class="btn btn-secondary">&laquo; Kembali ke Dashboard</a>
    </div>
</div>

</body>
</html>