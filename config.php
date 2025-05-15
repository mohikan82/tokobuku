<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tokobuku";

// Buat koneksi
$conn = mysqli_connect($servername, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Opsional: Set karakter UTF-8 (penting jika ada teks non-ASCII)
mysqli_set_charset($conn, "utf8");
?>
