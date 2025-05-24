<?php
session_start();
include "../config.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// === Proses Tambah Produk ===
if (isset($_POST['submit'])) {
    $nama_produk = $_POST['nama_produk'];
    $kategori_produk = $_POST['kategori_produk'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];

    // Upload gambar
    $gambar_name = $_FILES['gambar_name']['name'];
    $gambar_tmp = $_FILES['gambar_name']['tmp_name'];
    $upload_dir = "uploads/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $gambar_path = $upload_dir . basename($gambar_name);

    if (move_uploaded_file($gambar_tmp, $gambar_path)) {
        $stmt = mysqli_prepare($conn, "INSERT INTO produk (nama_produk, kategori_produk, gambar_name, harga, deskripsi, stok) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssisi", $nama_produk, $kategori_produk, $gambar_name, $harga, $deskripsi, $stok);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Gagal upload gambar');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; }
        .sidebar {
            height: 100vh;
            background-color: #2c3e50;
            padding-top: 20px;
        }
        .sidebar a {
            display: block;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #1abc9c;
        }
        .main-content {
            padding: 20px;
        }
        .table img {
            width: 60px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar">
            <h4 class="text-white text-center mb-4">CpaneL Admin</h4>
            <div class="text-left mb-3 text-white">👤 <?php echo $_SESSION['username']; ?></div>
            <a href="#index.php">Dashboard</a>
            <a href="#produk">Data Produk</a>
            <a href="#admin">Data Admin</a>
            <a href="#user">Data User</a>
            <a href="#pesanan">Data Pesanan</a>
            <form action="logout.php" method="POST" class="text-left mt-4">
                <button type="submit" class="btn btn-danger btn-sm">Log Out</button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 main-content">
            <h3>Dashboard Admin</h3>
            <hr>

            <!-- Form Tambah Produk -->
            <section id="produk">
                <h4>Tambah Produk</h4>
                <form method="POST" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col">
                            <input type="text" name="nama_produk" class="form-control" placeholder="Nama Produk" required>
                        </div>
                        <div class="col">
                            <select name="kategori_produk" class="form-select" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Agama">Agama</option>
                                <option value="MaPel">MaPel</option>
                                <option value="Novel">Novel</option>
                            </select>
                        </div>
                        <div class="col">
                            <input type="file" name="gambar_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="number" name="harga" class="form-control" placeholder="Harga" required>
                    </div>
                    <div class="mb-3">
                        <textarea name="deskripsi" class="form-control" placeholder="Deskripsi" required></textarea>
                    </div>
                    <div class="mb-3">
                        <input type="number" name="stok" class="form-control" placeholder="Stok" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-success">Tambah Produk</button>
                </form>

                <hr>
                <h4>Daftar Produk</h4>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Gambar</th>
                            <th>Harga</th>
                            <th>Deskripsi</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 1;
                    $result_produk = mysqli_query($conn, "SELECT * FROM produk");
                    if (!$result_produk) {
                        echo "<tr><td colspan='8'>Error: " . mysqli_error($conn) . "</td></tr>";
                    } else 
                        while ($row = mysqli_fetch_assoc($result_produk)) :
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['nama_produk'] ?></td>
                            <td><?= $row['kategori_produk'] ?></td>
                            <td><img src="uploads/<?= $row['gambar_name'] ?>" alt="img"></td>
                            <td><?= number_format($row['harga'], 0, ',', '.') ?></td>
                            <td><?= $row['deskripsi'] ?></td>
                            <td><?= $row['stok'] ?></td>
                            <td>
                                <a href="edit_produk.php?id=<?= $row['id_produk'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="hapus_produk.php?id=<?= $row['id_produk'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </section>

            <!-- Data Admin -->
            <section id="admin" class="mt-5">
                <h4>Data Admin</h4>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Nama Lengkap</th>
                            <th>No Telp</th>
                            <th>Alamat</th>
                            <th>Dibuat Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $result_admin = mysqli_query($conn, "SELECT * FROM admin");
                    if (!$result_admin) {
                        echo "<tr><td colspan='7'>Error: " . mysqli_error($conn) . "</td></tr>";
                    } else 
                        while ($row = mysqli_fetch_assoc($result_admin)) :      
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['username'] ?></td>
                            <td><?= $row['nama_lengkap'] ?></td>
                            <td><?= $row['no_telepon'] ?></td>
                            <td><?= $row['alamat'] ?></td>
                            <td><?= isset($row['created_at']) ? $row['created_at'] : '-' ?></td>
                            <td>
                                <a href="edit_admin.php?id=<?= $row['id_admin'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="hapus_admin.php?id=<?= $row['id_admin'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus admin ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </section>

            <!-- Data User -->
            <section id="user" class="mt-5">
                <h4>Data User</h4>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Nama Lengkap</th>
                            <th>No Telp</th>
                            <th>Alamat</th>
                            <th>Dibuat Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 1;
                    $result_user = mysqli_query($conn, "SELECT * FROM user");
                    if (!$result_user) {
                        echo "<tr><td colspan='7'>Error: " . mysqli_error($conn) . "</td></tr>";
                    } else 
                        while ($row = mysqli_fetch_assoc($result_user)) :
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['username'] ?></td>
                            <td><?= $row['nama_lengkap'] ?></td>
                            <td><?= $row['no_telepon'] ?></td>
                            <td><?= $row['alamat'] ?></td>
                            <td><?= isset($row['created_at']) ? $row['created_at'] : '-' ?></td>
                            <td>
                                <a href="edit_user.php?id=<?= $row['id_user'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="hapus_user.php?id=<?= $row['id_user'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus user ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </section>

            <!-- Data Pesanan -->
            <section id="pesanan" class="mt-5">
                <h4>Data Pesanan</h4>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $query = "SELECT p.*, u.username FROM pesanan p JOIN user u ON p.id_user = u.id_user ORDER BY p.created_at DESC";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) :
                    ?>
                        <tr>
                            <td><?= $row['id_pesanan'] ?></td>
                            <td><?= $row['username'] ?></td>
                            <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                            <td>

                                <form method="POST" action="update_status.php">
                                    <input type="hidden" name="id_pesanan" value="<?= $row['id_pesanan'] ?>">
                                    <select name="status_pesanan" onchange="this.form.submit()">
                                        <option <?= $row['status_pesanan'] == 'pending' ? 'selected' : '' ?> value="pending">Pending</option>
                                        <option <?= $row['status_pesanan'] == 'diproses' ? 'selected' : '' ?> value="diproses">Diproses</option>
                                        <option <?= $row['status_pesanan'] == 'dikirim' ? 'selected' : '' ?> value="dikirim">Dikirim</option>
                                        <option <?= $row['status_pesanan'] == 'selesai' ? 'selected' : '' ?> value="selesai">Selesai</option>
                                    </select>
                                </form>
                            </td>
                            <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                            <td><a href="detail_pesanan.php?id=<?= $row['id_pesanan'] ?>" class="btn btn-info btn-sm">Detail</a>
                            <a href="hapus_pesanan.php?id=<?= $row['id_pesanan'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus pesanan ini?')">Hapus</a></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </section>

            
        </div>
    </div>
</div>
</body>
</html>
