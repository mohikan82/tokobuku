<?php
session_start();
include "../config.php";



$id_pesanan = (int)$_POST['id_pesanan'];
$status_pesanan = mysqli_real_escape_string($conn, $_POST['status_pesanan']);

mysqli_query($conn, "
    UPDATE pesanan 
    SET status_pesanan = '$status_pesanan' 
    WHERE id_pesanan = $id_pesanan
");

header("Location: index.php");  // Kembali ke halaman admin
