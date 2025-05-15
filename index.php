<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login_user.php");
    exit();
}

include 'config.php';

// Konfigurasi Pagination
$limit = 4; // jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Hitung total data
$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM buku");
$total_row = mysqli_fetch_assoc($total_result);
$total_books = $total_row['total'];
$total_pages = ceil($total_books / $limit);

// Query buku dengan pagination
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

if ($search !== '') {
    $query = "SELECT * FROM buku WHERE judul LIKE '%$search%' OR kategori LIKE '%$search%' LIMIT $limit OFFSET $offset";

    // Hitung ulang total data untuk pencarian
    $total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM buku WHERE judul LIKE '%$search%' OR kategori LIKE '%$search%'");
} else {
    $query = "SELECT * FROM buku LIMIT $limit OFFSET $offset";

    // Total data keseluruhan
    $total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM buku");
}

$total_row = mysqli_fetch_assoc($total_result);
$total_books = $total_row['total'];
$total_pages = ceil($total_books / $limit);

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda - Toko Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        h2 {
            margin: 30px 0;
            text-align: center;
        }

        .product-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-bottom: 40px;
        }

        .product, .product-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            width: 220px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .product img, .product-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        button {
            margin-top: 10px;
            background-color: #007bff;
            color: #fff;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        footer {
            background-color: #2c3e50;
            color: white;
            padding: 15px 0;
            text-align: center;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-light py-3" style="background-color: #87CEFA;">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="Img Buku/logo bhumida bookstore.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
            Bhumida Bookstore</a>
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            </ul>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <nav class="navbar navbar-expand-lg navbar-light py-3" style="background-color: #87CEFA;">
    <div class="container">
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
                 
         </div>
    </div>
</nav>
                <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="keranjang.php">Keranjang</a></li>
                <li class="nav-item"><a class="nav-link" href="checkout.php">Checkout</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h2>Daftar Buku</h2>
    <form method="GET" action="index.php" class="mb-4">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Cari buku berdasarkan judul atau kategori..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button class="btn btn-primary" type="submit">Cari</button>
    </div>
</form>

    <!-- Buku Statis -->
    <div class="product-list">
        <div class="product">
            <img src="Img Buku/manajemen pondok pesantren.jpg" alt="Judul Buku">
            <h3>Manajemen Pondok Pesantren</h3>
            <p>Kategori: Agama</p>
            <p>Harga: Rp 80.000</p>
            <p>Stok: 8</p>
            <form action="add_to_cart.php" method="POST">
                <input type="hidden" name="id_produk" value="1">
                <button type="submit" class="btn btn-success btn-sm">Tambahkan ke Keranjang</button>
            </form>
        </div>
        <div class="product">
                <img src="Img Buku/diam dalam kesuksessan.jpg" alt="Judul Buku">
                <h3>Diam Dalam Kesuksessan</h3>
                <p>Kategori: Novel</p>
                <p>Harga: Rp 75.000</p>
                <p>Stok: 10</p>
                <form action="add_to_cart.php" method="POST">
        <input type="hidden" name="id_produk" value="1">
        <button type="submit" class="btn btn-success btn-sm">Tambahkan ke Keranjang</button>
    </form>
                           </div>
            <div class="product">
                <img src="Img Buku/Informatika X.png" alt="Judul Buku">
                <h3>Informatika X</h3>
                <p>Kategori: MaPel</p>
                <p>Harga: Rp 85.000</p>
                <p>Stok: 9</p>
                <form action="add_to_cart.php" method="POST">
        <input type="hidden" name="id_produk" value="1">
        <button type="submit" class="btn btn-success btn-sm">Tambahkan ke Keranjang</button>
    </form>
                          </div>
            <div class="product">
                <img src="Img Buku/Buku Islam/sejarah peradaban islam.jpg" alt="Judul Buku">
                <h3>Sejarah Peradaban Islam</h3>
                <p>Kategori: Agama</p>
                <p>Harga: Rp 86.000</p>
                <p>Stok: 6</p>
                <form action="add_to_cart.php" method="POST">
        <input type="hidden" name="id_produk" value="1">
        <button type="submit" class="btn btn-success btn-sm">Tambahkan ke Keranjang</button>
    </form>
                          </div> 
            <div class="product">
                <img src="Img Buku/Buku novel/awal dari perjuangan.jpg" alt="Judul Buku">
                <h3>Awal dari Perjuangan</h3>
                <p>Kategori: Novel</p>
                <p>Harga: Rp 70.000</p>
                <p>Stok: 6</p>
                <form action="add_to_cart.php" method="POST">
        <input type="hidden" name="id_produk" value="2">
        <button type="submit" class="btn btn-success btn-sm">Tambahkan ke Keranjang</button>
    </form>
                          </div>
            <div class="product">
                <img src="Img Buku/Buku Mapel/Dasar-dasar Otomotif X.png" alt="Judul Buku">
                <h3>Dasar-dasar Otomotif X</h3>
                <p>Kategori: MaPel</p>
                <p>Harga: Rp 78.000</p>
                <p>Stok: 9</p>
                <form action="add_to_cart.php" method="POST">
        <input type="hidden" name="id_produk" value="2">
        <button type="submit" class="btn btn-success btn-sm">Tambahkan ke Keranjang</button>
    </form>
                          </div>   
            <div class="product">
                <img src="Img Buku/Buku novel/be yourself.jpg" alt="Judul Buku">
                <h3>Be Yourself</h3>
                <p>Kategori: Novel</p>
                <p>Harga: Rp 66.000</p>
                <p>Stok: 7</p>
                <form action="add_to_cart.php" method="POST">
        <input type="hidden" name="id_produk" value="2">
        <button type="submit" class="btn btn-success btn-sm">Tambahkan ke Keranjang</button>
    </form>
                          </div> 
            <div class="product">
                <img src="Img Buku/Buku Islam/Irab Jurumiyah.jpg" alt="Judul Buku">
                <h3>Irab Jurumiyah</h3>
                <p>Kategori: Agama</p>
                <p>Harga: Rp 69.000</p>
                <p>Stok: 8</p>
                <form action="add_to_cart.php" method="POST">
        <input type="hidden" name="id_produk" value="2">
        <button type="submit" class="btn btn-success btn-sm">Tambahkan ke Keranjang</button>
    </form>
            </div>
            <div class="product">
                <img src="Img Buku/Buku Mapel/Dasar-dasar Bangunan Sipil X.png" alt="Judul Buku">
                <h3>Dasar-dasar Bangunan Sipil X</h3>
                <p>Kategori: MaPel</p>
                <p>Harga: Rp 75.000</p>
                <p>Stok: 10</p>
                <form action="add_to_cart.php" method="POST">
        <input type="hidden" name="id_produk" value="3">
        <button type="submit" class="btn btn-success btn-sm">Tambahkan ke Keranjang</button>
    </form>
            </div>
            <div class="product">
                <img src="Img Buku/Buku novel/apa kabar kenangan.jpg" alt="Judul Buku">
                <h3>Apa Kabar Kenangan</h3>
                <p>Kategori: MaPel</p>
                <p>Harga: Rp 55.000</p>
                <p>Stok: 6</p>
                <form action="add_to_cart.php" method="POST">
        <input type="hidden" name="id_produk" value="3">
        <button type="submit" class="btn btn-success btn-sm">Tambahkan ke Keranjang</button>
    </form>
            </div>
            <div class="product">
                <img src="Img Buku/Buku Islam/metode silat QU.jpg" alt="Judul Buku">
                <h3>Metode Silat QU</h3>
                <p>Kategori: Agama</p>
                <p>Harga: Rp 67.000</p>
                <p>Stok: 9</p>
                <form action="add_to_cart.php" method="POST">
        <input type="hidden" name="id_produk" value="3">
        <button type="submit" class="btn btn-success btn-sm">Tambahkan ke Keranjang</button>
    </form>
            </div>
            <div class="product">
                <img src="Img Buku/Buku Mapel/IPAS X.png" alt="Judul Buku">
                <h3>IPAS X</h3>
                <p>Kategori: MaPel</p>
                <p>Harga: Rp 79.000</p>
                <p>Stok: 10</p>
                <form action="add_to_cart.php" method="POST">
        <input type="hidden" name="id_produk" value="3">
        <button type="submit" class="btn btn-success btn-sm">Tambahkan ke Keranjang</button>
    </form>
            </div>
            <div class="product">
                <img src="Img Buku/Buku novel/aku dan sepenggal.jpg" alt="Judul Buku">
                <h3>Aku dan Sepenggal</h3>
                <p>Kategori: MaPel</p>
                <p>Harga: Rp 79.000</p>
                <p>Stok: 10</p>
                <form action="add_to_cart.php" method="POST">
        <input type="hidden" name="id_produk" value="4">
        <button type="submit" class="btn btn-success btn-sm">Tambahkan ke Keranjang</button>
    </form>
            </div>
    <!-- Buku dari Database -->
    <div class="product-list">
        <?php
        if (!$result) {
            echo "<p>Gagal mengambil data buku.</p>";
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <div class="product-card">
                <img src="uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['judul']); ?>" />
                <h3><?php echo htmlspecialchars($row['judul']); ?></h3>
                <p>Kategori: <?php echo htmlspecialchars($row['kategori']); ?></p>
                <p>Harga: Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                <p>Stok: <?php echo $row['stok']; ?></p>
                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="id_produk" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="btn btn-success btn-sm">Add to Cart</button>
                </form>
            </div>
        <?php
            }
        }
        ?>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        <nav>
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>">&laquo;</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>">&raquo;</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</div>

<footer>
    <div class="container">
        <p>© 2025 Bhumida Bookstore. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
