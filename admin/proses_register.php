<?php
include "../config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama_lengkap = $_POST['nama_lengkap'];
    $no_telepon = $_POST['no_telepon'];
    $alamat = $_POST['alamat'];

    // Gunakan backtick (`) atau tanpa kutip untuk nama tabel dan kolom
    $cek = mysqli_query($conn, "SELECT * FROM `admin` WHERE username = '$username'");
    if (!$cek) {
        die("Query Error: " . mysqli_error($conn)); // Menampilkan error MySQL jika query gagal
    }

    if (mysqli_num_rows($cek) > 0) {
        echo "Username sudah digunakan!";
    } else {
        $query = "INSERT INTO `admin` (username, password, nama_lengkap, no_telepon, alamat) 
                  VALUES ('$username', '$password', '$nama_lengkap', '$no_telepon', '$alamat')";

        if (mysqli_query($conn, $query)) {
            echo "Registrasi berhasil! <a href='login.php'>Login di sini</a>.";
        } else {
            echo "Registrasi gagal! Error: " . mysqli_error($conn);
        }
    }
}
