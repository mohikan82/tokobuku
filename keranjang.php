<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Keranjang Belanja</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .cart-box {
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 20px;
      background-color: #fff;
    }
    .cart-icon {
      font-size: 24px;
      margin-right: 10px;
    }
    .btn-delete {
      background-color: #dc3545;
      color: white;
    }
    .btn-delete:hover {
      background-color: #c82333;
    }
    .btn-checkout {
      background-color: #198754;
      color: white;
    }
    .btn-checkout:hover {
      background-color: #157347;
    }
  </style>
</head>
<body>

<div class="container mt-5">
  <h3 class="text-center"><i class="bi bi-cart3 cart-icon"></i>Keranjang Belanja</h3>

  <div class="cart-box mt-4">
    <h5>Manajemen Pondok Pesantren</h5>
    <p>Harga: <strong>Rp 80.000</strong></p>
    <p>Jumlah: <strong>1</strong></p>
    <button class="btn btn-delete"><i class="bi bi-trash"></i> Hapus</button>
  </div>

  <div class="d-flex justify-content-between align-items-center mt-4">
    <a href="index.php" class="btn btn-secondary">&larr; Kembali Belanja</a>
    <div>
      <h5>Total: <span class="text-success">Rp 80.000</span></h5>
      <a href="checkout.php" button class="btn btn-checkout"><i class="bi bi-cart-check"></i> Checkout</a></button>
    </div>
  </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>
