<?php
session_start();
include "config.php";

// Cek login user
if (!isset($_SESSION['user'])) {
    header("Location: login_user.php");
    exit();
}

// Pagination
$limit = 6;
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
$riwayat_pesanan = [];
if (isset($_SESSION['user_id'])) {
    $query_pesanan = mysqli_query($conn, "SELECT * FROM pesanan WHERE id_user = {$_SESSION['user_id']} ORDER BY created_at DESC");
    while ($row = mysqli_fetch_assoc($query_pesanan)) {
        $riwayat_pesanan[] = $row;
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
        .product-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .status-pending { color: #f39c12; }
        .status-diproses { color: #3498db; }
        .status-dikirim { color: #2ecc71; }
        .status-selesai { color: #27ae60; }
    </style>
</head>
<body style="background-color: #f4f4f4;">

<nav class="navbar navbar-expand-lg navbar-light py-3" style="background-color: #87CEFA;">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="Img Buku/logo bhumida bookstore.png" alt="Logo" width="30" height="30"> Bhumida Bookstore
        </a>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="keranjang.php">Keranjang</a></li>
                <li class="nav-item"><a class="nav-link" href="checkout.php">Checkout</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
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

    <div class="product-list">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="product-card">
                    <img src="admin/uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['nama_produk']); ?>">
                    <h5><?php echo htmlspecialchars($row['nama_produk']); ?></h5>
                    <p>Kategori: <?php echo htmlspecialchars($row['kategori_produk']); ?></p>
                    <p>Harga: Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                    <p>Stok: <?php echo $row['stok']; ?></p>
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="id_produk" value="<?php echo $row['id_produk']; ?>">
                        <button onclick="addToCart(<?php echo $row['id_produk']; ?>)">Add to Cart</button>
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
    <?php if (!empty($riwayat_pesanan)): ?>
        <div class="mt-5">
            <h4>Riwayat Pemesanan Anda</h4>
            <table class="table table-bordered">
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
                    <?php foreach ($riwayat_pesanan as $pesanan): ?>
                        <tr>
                            <td><?php echo $pesanan['id_pesanan']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($pesanan['created_at'])); ?></td>
                            <td>Rp <?php echo number_format($pesanan['total'], 0, ',', '.'); ?></td>
                            <td class="status-<?php echo $pesanan['status']; ?>">
                                <?php
                                $status_text = [
                                    'pending' => 'Pending',
                                    'diproses' => 'Diproses',
                                    'dikirim' => 'Dikirim',
                                    'selesai' => 'Selesai'
                                ];
                                echo $status_text[$pesanan['status']] ?? $pesanan['status'];
                                ?>
                            </td>
                            <td><a href="detail_pesanan_user.php?id=<?= $pesanan['id_pesanan'] ?>" class="btn btn-sm btn-info">Lihat Detail</a></td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
