<?php
include "../config.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']); // pastikan id adalah angka

// Ambil data admin berdasarkan ID menggunakan prepared statement
$stmt = mysqli_prepare($conn, "SELECT * FROM admin WHERE id_admin = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    die("Data admin dengan ID $id tidak ditemukan.");
}

$admin = mysqli_fetch_assoc($result);

// Proses update data jika form disubmit
if (isset($_POST['update'])) {
    $username = $_POST['username'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $no_telepon = $_POST['no_telepon'];
    $alamat = $_POST['alamat'];

    // Prepared statement untuk update data
    $stmt = mysqli_prepare($conn, "UPDATE admin SET username=?, nama_lengkap=?, no_telepon=?, alamat=? WHERE id_admin=?");
    mysqli_stmt_bind_param($stmt, "ssssi", $username, $nama_lengkap, $no_telepon, $alamat, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php#admin");
        exit;
    } else {
        echo "Gagal update data admin!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h3>Edit Data Admin</h3>
    <form method="POST">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" value="<?= htmlspecialchars($admin['username']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($admin['nama_lengkap']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>No Telepon</label>
            <input type="text" name="no_telepon" value="<?= htmlspecialchars($admin['no_telepon']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required><?= htmlspecialchars($admin['alamat']) ?></textarea>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update</button>
        <a href="index.php#admin" class="btn btn-secondary">Batal</a>
    </form>
</body>
</html>
