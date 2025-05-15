<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}

// Ambil data keranjang
$query = mysqli_query($conn, "
    SELECT produk.id_produk, produk.nama_produk, produk.harga, keranjang.jumlah 
    FROM keranjang 
    JOIN produk ON keranjang.id_produk = produk.id_produk 
    WHERE keranjang.id_user = {$_SESSION['user_id']}
");

$total = 0;
$item_count = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Keranjang Belanja</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body { background-color: #f8f9fa; }
    .cart-box {
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 20px;
      background-color: #fff;
      margin-bottom: 15px;
    }
    .cart-icon { font-size: 24px; margin-right: 10px; }
    .btn-delete { background-color: #dc3545; color: white; }
    .btn-delete:hover { background-color: #c82333; }
    .btn-checkout { background-color: #198754; color: white; }
    .btn-checkout:hover { background-color: #157347; }
  </style>
  <script>
    function validateCheckout() {
      <?php if ($item_count == 0): ?>
        alert("Keranjang kosong! Tambahkan produk terlebih dahulu.");
        window.location.href = "index.php";
        return false;
      <?php else: ?>
        return true;
      <?php endif; ?>
    }
  </script>
</head>
<body>
<div class="container mt-5">
  <h3 class="text-center"><i class="bi bi-cart3 cart-icon"></i>Keranjang Belanja</h3>

  <?php while ($row = mysqli_fetch_assoc($query)): ?>
    <div class="cart-box">
      <h5><?php echo htmlspecialchars($row['nama_produk']); ?></h5>
      <p>Harga: <strong>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></strong></p>
      <p>Jumlah: <strong><?php echo $row['jumlah']; ?></strong></p>
      <a href="hapus_item.php?id_produk=<?php echo $row['id_produk']; ?>" class="btn btn-delete"><i class="bi bi-trash"></i> Hapus</a>
    </div>
    <?php $total += $row['harga'] * $row['jumlah']; ?>
  <?php endwhile; ?>

  <div class="d-flex justify-content-between align-items-center mt-4">
    <a href="index.php" class="btn btn-secondary">&larr; Kembali Belanja</a>
    <div>
      <h5>Total: <span class="text-success">Rp <?php echo number_format($total, 0, ',', '.'); ?></span></h5>
      <a href="checkout.php" onclick="return validateCheckout()" class="btn btn-checkout"><i class="bi bi-cart-check"></i> Checkout</a>
    </div>
  </div>
</div>
</body>
</html>
