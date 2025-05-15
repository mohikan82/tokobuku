<?php
session_start();
include "config.php";

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}

$id_user = $_SESSION['user_id'];
$nama = $_POST['nama_lengkap'];
$telepon = $_POST['no_telepon'];
$alamat = $_POST['alamat'];
$jumlah = 0;

// Ambil total harga dari keranjang
$cart_query = mysqli_query($conn, "
    SELECT produk.harga, keranjang.jumlah 
    FROM keranjang 
    JOIN produk ON keranjang.id_produk = produk.id_produk 
    WHERE keranjang.id_user = $id_user
");
while ($row = mysqli_fetch_assoc($cart_query)) {
    $jumlah += $row['harga'] * $row['jumlah'];
}

// Upload bukti transfer
$upload_dir = "bukti_transfer/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$bukti = $_FILES['bukti_transfer'];
$filename = time() . '_' . basename($bukti['name']);
$target_file = $upload_dir . $filename;

if (move_uploaded_file($bukti["tmp_name"], $target_file)) {
    // Simpan data ke database
    $query = "INSERT INTO konfirmasi_pembayaran 
              (id_user, nama_lengkap, no_telepon, alamat, jumlah, bukti_transfer)
              VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'isssis', $id_user, $nama, $telepon, $alamat, $jumlah, $filename);
    mysqli_stmt_execute($stmt);

    // Hapus keranjang user
    mysqli_query($conn, "DELETE FROM keranjang WHERE id_user = $id_user");

    // Redirect ke halaman konfirmasi
    header("Location: konfirmasi.php");
    exit;
} else {
    echo "Gagal mengupload bukti transfer.";
}
?>
