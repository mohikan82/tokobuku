<?php
session_start();
include "config.php";

// Validasi login
if (!isset($_SESSION['user'])) {
    header("Location: login_user.php");
    exit();
}

// Validasi ID
if (!isset($_GET['id'])) {
    echo "ID pesanan tidak ditemukan.";
    exit();
}

$id_pesanan = intval($_GET['id']);

// Ambil data pesanan
$query = mysqli_query($conn, "SELECT p.*, u.username FROM pesanan p JOIN user u ON p.id_user = u.id_user WHERE p.id_pesanan = $id_pesanan");

if (!$query) {
    die("Query error: " . mysqli_error($conn));
}

$data = mysqli_fetch_assoc($query);
if (!$data) {
    echo "Data pesanan tidak ditemukan.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice Pemesanan</title>
    <style>
        body { font-family:  Arial, sans-serif; padding: 20px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        .text-center { text-align: center; }
    </style>
</head>
<body>


<h2>INVOICE PEMESANAN</h2>

<p><strong>Nama Pemesan:</strong> <?php echo htmlspecialchars($data['username']); ?></p>
<p><strong>Tanggal Pesan:</strong> <?php echo htmlspecialchars($data['created_at']); ?></p>
<p><strong>Status:</strong> <?php echo htmlspecialchars($data['status_pesanan']); ?></p>

<h4>Detail Pesanan</h4>
<table>
    <tr>
        <th>No</th>
        <th>Nama Produk</th>
        <th>Jumlah</th>
        <th>Harga</th>
        <th>Total</th>
    </tr>
    <?php
    $no = 1;
    $sub_total = 0;

    $query_detail = mysqli_query($conn, "SELECT dp.*, pr.nama_produk, pr.harga 
        FROM detail_pesanan dp 
        JOIN produk pr ON dp.id_produk = pr.id_produk 
        WHERE dp.id_pesanan = $id_pesanan");

    if (!$query_detail) {
        die("Query detail gagal: " . mysqli_error($conn));
    }
    
    if (mysqli_num_rows($query_detail) == 0) {
        echo "<tr><td colspan='5'>Tidak ada detail pesanan ditemukan.</td></tr>";
    }
    
    while ($item = mysqli_fetch_assoc($query_detail)) {
        $total = $item['jumlah'] * $item['harga'];
        $sub_total += $total;
        echo "<tr>
        <td>$no</td>
        <td>{$item['nama_produk']}</td>
        <td>{$item['jumlah']}</td>
        <td>Rp " . number_format($item['harga_produk'], 0, ',', '.') . "</td>
        <td>Rp " . number_format($total, 0, ',', '.') . "</td>
      </tr>";
      $no++;
    }
    ?>
    <tr>
        <td colspan="4" class="text-center"><strong>Total Bayar</strong></td>
        <td><strong>Rp <?php echo number_format($sub_total, 0, ',', '.'); ?></strong></td>
    </tr>
</table>

<br><br>
<p>Terima kasih telah berbelanja di Bhumida Bookstore.</p>
    <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="index.php" class="btn btn-secondary">&larr; Kembali Belanja</a>
    </div>

<script>
    window.print();
</script>

</body>
</html>
