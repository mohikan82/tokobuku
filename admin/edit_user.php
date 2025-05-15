<?php
include "../config.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM user WHERE id_user = $id");
$user = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {
    $username = $_POST['username'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $no_telepon = $_POST['no_telepon'];
    $alamat = $_POST['alamat'];

    $update = mysqli_query($conn, "UPDATE user SET 
        username = '$username',
        nama_lengkap = '$nama_lengkap',
        no_telepon = '$no_telepon',
        alamat = '$alamat'
        WHERE id_user = $id
    ");

    if ($update) {
        header("Location: index.php#user");
        exit;
    } else {
        echo "Gagal update data user!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h3>Edit Data User</h3>
    <form method="POST">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" value="<?= $user['username'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" value="<?= $user['nama_lengkap'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>No Telepon</label>
            <input type="text" name="no_telepon" value="<?= $user['no_telepon'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required><?= $user['alamat'] ?></textarea>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update</button>
        <a href="index.php#user" class="btn btn-secondary">Batal</a>
    </form>
</body>
</html>
