<?php
session_start();
include "config.php";

// Cek login user
if (!isset($_SESSION['user'])) {
    header("Location: login_user.php");
    exit();
}

// Pagination
$limit = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search keyword
$keyword = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$whereClause = "";
if (!empty($keyword)) {
    $whereClause = "WHERE nama_produk LIKE '%$keyword%' OR kategori_produk LIKE '%$keyword%'";
}

// Ambil total produk
$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM produk $whereClause");
$total_row = mysqli_fetch_assoc($total_result);
$total_produk = $total_row['total'];
$total_pages = ceil($total_produk / $limit);

// Ambil data produk
$query = "SELECT * FROM produk $whereClause LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

// Ambil riwayat pesanan
$riwayat_pesanan_user = [];
if (isset($_SESSION['user_id'])) {
    $query_pesanan = mysqli_query($conn, "SELECT * FROM pesanan WHERE id_user = {$_SESSION['user_id']} ORDER BY created_at DESC");
    while ($row = mysqli_fetch_assoc($query_pesanan)) {
        $riwayat_pesanan_user[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bhumida Bookstore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-card img { width: 100%; height: 150px; object-fit: cover; }
        .product-card { border: 1px solid #ddd; padding: 15px; border-radius: 8px; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center; }
        .product-wrapper { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .status-pending { color: orange; font-weight: bold; }
        .status-diproses { color: blue; font-weight: bold; }
        .status-dikirim { color: green; font-weight: bold; }
        .status-selesai { color: gray; font-weight: bold; } 
        .riwayat-pesanan { margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px; }  
    </style>
    <style>
table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  border: 1px solid #000;
  padding: 8px;
  text-align: left;
}
</style>
</head>
<body style="background-color: very light gray;">

<nav class="navbar navbar-expand-lg navbar-light py-3" style="background-color: #87CEFA;">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="Img Buku/logo bhumida bookstore.png" alt="Logo" width="50" height="50">  Bhumida Bookstore</a>
        <strong><ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="about.php" style= >About US</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        </ul></strong>

  <div class="d-flex collapse justify-content-end align-items-right">
    <b><div>
        <?php if (isset($_SESSION['user'])): ?>
            <span>Welcome <?php echo htmlspecialchars($_SESSION['user']); ?></span>
            <a href="keranjang.php" class="mx-2 text-dark text-decoration-none">Keranjang</a>
            <a href="checkout.php" class="mx-2 text-dark text-decoration-none">Checkout</a>
            <a href="logout.php" class="mx-2 text-dark text-decoration-none">Logout</a>
        <?php else: ?>
            <a href="login_user.php" class="mx-2 text-dark text-decoration-none">Keranjang</a>
            <a href="login_user.php" class="mx-2 text-dark text-decoration-none">Checkout</a>
            <a href="login_user.php" class="mx-2 text-dark text-decoration-none">Logout</a>
        <?php endif; ?>
    </div></b>    
  </div>
</nav>

<div class="container py-4">
    <h2 class="text-center">Daftar Produk</h2>

    <form method="GET" action="index.php" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama atau kategori..." value="<?php echo htmlspecialchars($keyword); ?>">
            <button class="btn btn-primary" type="submit">Cari</button>
        </div>
    </form>

    <div class="product-wrapper">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="product-card">
                    <img src="admin/uploads/<?php echo htmlspecialchars($row['gambar_name']); ?>" alt="<?php echo htmlspecialchars($row['nama_produk']); ?>">
                    <h5><?php echo htmlspecialchars($row['nama_produk']); ?></h5>
                    <p>Kategori: <?php echo htmlspecialchars($row['kategori_produk']); ?></p>
                    <p>Harga: Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                    <p>Stok: <?php echo $row['stok']; ?></p>
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="id_produk" value="<?php echo $row['id_produk']; ?>">
                        <button class="btn btn-sm btn-success" type="submit">Add to Cart</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Tidak ada produk ditemukan.</p>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($keyword); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

     <!-- Riwayat Pemesanan -->
     <?php if (isset($_SESSION['user_id']) && !empty($riwayat_pesanan_user)): ?>
        <div class="riwayat-pesanan">
            <h2>Riwayat Pemesanan Anda</h2>
            <table border="1" cellpadding="10" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($riwayat_pesanan_user as $pesanan): ?>
                        <tr>
                            <td><?php echo $pesanan['id_pesanan']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($pesanan['created_at'])); ?></td>
                            <td>Rp <?php echo number_format($pesanan['total'], 0, ',', '.'); ?></td>
                            <td class="status-<?php echo str_replace('_', '-', $pesanan['status_pesanan']); ?>">
                                <?php
                                $status = [
                                    'pending' => 'pending',
                                    'diproses' => 'Diproses',
                                    'dikirim' => 'Dikirim',
                                    'selesai' => 'Selesai'
                                ];
                                echo $status[$pesanan['status_pesanan']] ?? $pesanan['status_pesanan'];
                                ?>
                            </td>
                            <td>
                            <a href="detail_pesanan_user.php?id=<?php echo $pesanan['id_pesanan']; ?>" class="btn btn-sm btn-primary">Detail</a>
                                <?php if ($pesanan['status_pesanan'] === 'selesai'): ?>
                                    <a href="hapus_pesanan_user.php?id=<?= $row['id_pesanan'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pesanan ini?')">Hapus</a>
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

     
    <script>
        function addToCart(productId){
            <?php if (!isset($_SESSION['user_id'])): ?>
            alert("Silahkan login terlebih dahulu!");
            window.Location.href = "login_user.php";
        <?php else: ?>
            fetch("add_to_cart.php", {
                    method: "POST",
                    headers: {
                      "Content-Type": "application/x-ww-form-urlencoded"
                    },
                    body: "id_produk=" + productId
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === "success") {
                        window.location.reload();
                    }
                });
            <?php endif; ?>
        }
    </script>
</body>
</html>