<?php
session_start();
include "config.php";

// Redirect jika belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}

$id_produk = (int)$_POST['id_produk'];
$id_user = (int)$_SESSION['user_id'];

// Proses tambah ke keranjang
$check = mysqli_query($conn, "SELECT * FROM keranjang WHERE id_user = $id_user AND id_produk = $id_produk");

if (mysqli_num_rows($check) > 0) {
    mysqli_query($conn, "UPDATE keranjang SET jumlah = jumlah + 1 WHERE id_user = $id_user AND id_produk = $id_produk");
} else {
    mysqli_query($conn, "INSERT INTO keranjang (id_user, id_produk, jumlah) VALUES ($id_user, $id_produk, 1)");
}

// Setelah sukses, tampilkan halaman dengan pesan dan tombol kembali
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Produk Ditambahkan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .success-box {
      background: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      text-align: center;
    }
    .btn-back {
      background-color: #0d6efd;
      color: white;
      font-weight: 500;
      border-radius: 30px;
      padding: 10px 25px;
      transition: 0.3s ease;
    }
    .btn-back:hover {
      background-color: #0b5ed7;
      transform: scale(1.05);
    }
    .checkmark {
      font-size: 64px;
      color: #28a745;
    }
  </style>
</head>
<body>

<div class="success-box">
  <div class="checkmark">✔</div>
  <h4 class="mt-3">Produk berhasil ditambahkan ke keranjang!</h4>
  <a href="index.php" class="btn btn-back mt-4">&larr; Kembali Belanja</a>
</div>

</body>
</html>
