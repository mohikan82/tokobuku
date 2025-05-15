<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}

if (isset($_GET['id_produk'])) {
    $id_user = $_SESSION['user_id'];
    $id_produk = (int)$_GET['id_produk'];

    // Cek apakah item ada di keranjang user
    $cek = mysqli_query($conn, "
        SELECT * FROM keranjang 
        WHERE id_user = $id_user AND id_produk = $id_produk
    ");

    if (mysqli_num_rows($cek) > 0) {
        // Hapus item
        $delete = mysqli_query($conn, "
            DELETE FROM keranjang 
            WHERE id_user = $id_user AND id_produk = $id_produk
        ");
    }
}

header("Location: keranjang.php");
exit;
