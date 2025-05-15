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
$jumlah_total = 0;

// Ambil isi keranjang
$cart_query = mysqli_query($conn, "
    SELECT keranjang.*, produk.harga, produk.id_produk
    FROM keranjang 
    JOIN produk ON keranjang.id_produk = produk.id_produk 
    WHERE keranjang.id_user = $id_user
");

$cart_items = [];
while ($row = mysqli_fetch_assoc($cart_query)) {
    $row['subtotal'] = $row['harga'] * $row['jumlah'];
    $jumlah_total += $row['subtotal'];
    $cart_items[] = $row;
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

    // 1. Simpan ke tabel pesanan
    $stmt = mysqli_prepare($conn, "
        INSERT INTO pesanan (id_user, nama_lengkap, no_telepon, alamat, total, bukti_transfer, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, 'menunggu_verifikasi', NOW())
    ");
    mysqli_stmt_bind_param($stmt, 'isssss', $id_user, $nama, $telepon, $alamat, $jumlah_total, $filename);
    mysqli_stmt_execute($stmt);

    $id_pesanan = mysqli_insert_id($conn);

    // 2. Simpan ke detail_pesanan
    $stmt_detail = mysqli_prepare($conn, "
        INSERT INTO detail_pesanan (id_pesanan, id_produk, jumlah, harga_satuan)
        VALUES (?, ?, ?, ?)
    ");

    foreach ($cart_items as $item) {
        mysqli_stmt_bind_param($stmt_detail, 'iiid', $id_pesanan, $item['id_produk'], $item['jumlah'], $item['harga']);
        mysqli_stmt_execute($stmt_detail);
    }

    // 3. Kosongkan keranjang
    mysqli_query($conn, "DELETE FROM keranjang WHERE id_user = $id_user");

    // Redirect ke halaman sukses / konfirmasi
    header("Location: konfirmasi.php?id=$id_pesanan");
    exit;

} else {
    echo "Gagal mengupload bukti transfer.";
}
?>
