<?php
session_start();
include "config.php";

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}

$id_user = $_SESSION['user_id'];
$alamat = $_POST['alamat'];
$metode = $_POST['metode_pembayaran']; // Pastikan ini dikirim dari form
$jumlah_total = 0;

// Ambil data keranjang
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

    // Simpan ke tabel pesanan
    $stmt = mysqli_prepare($conn, "
        INSERT INTO pesanan (id_user, alamat, metode_pembayaran, total, bukti_transfer, status_pesanan, created_at)
        VALUES (?, ?, ?, ?, ?, 'menunggu_verifikasi', NOW())
    ");

    if ($stmt === false) {
        die("Prepare statement gagal: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, 'issds', $id_user, $alamat, $metode, $jumlah_total, $filename);
    mysqli_stmt_execute($stmt);

    $id_pesanan = mysqli_insert_id($conn);

    // Simpan detail pesanan
    $stmt_detail = mysqli_prepare($conn, "
        INSERT INTO detail_pesanan (id_pesanan, id_produk, jumlah, harga)
        VALUES (?, ?, ?, ?)
    ");

    if ($stmt_detail === false) {
        die("Prepare statement detail gagal: " . mysqli_error($conn));
    }

    foreach ($cart_items as $item) {
        mysqli_stmt_bind_param($stmt_detail, 'iiid', $id_pesanan, $item['id_produk'], $item['jumlah'], $item['harga']);
        mysqli_stmt_execute($stmt_detail);
    }

    // Kosongkan keranjang
    mysqli_query($conn, "DELETE FROM keranjang WHERE id_user = $id_user");

    // Redirect ke halaman konfirmasi
    header("Location: konfirmasi.php?id=$id_pesanan");
    exit;

} else {
    echo "Gagal mengupload bukti transfer.";
}
?>
