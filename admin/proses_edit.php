<?php
include "../config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_produk = $_POST['id_produk'];
    $nama_produk = $_POST['nama_produk'];
    $kategori_produk = $_POST['kategori_produk'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];
    $gambar_lama = $_POST['gambar_lama'];

    $gambar_name = $gambar_lama; // default gambar lama

    // Cek apakah ada file gambar yang diupload
    if (isset($_FILES['gambar_name']) && $_FILES['gambar_name']['error'] == 0) {
        $gambar_name = basename($_FILES['gambar_name']['name']);
        $tmp_name = $_FILES['gambar_name']['tmp_name'];
        $upload_dir = "uploads/";

        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Hapus gambar lama jika ada
        if (file_exists($upload_dir . $gambar_lama)) {
            unlink($upload_dir . $gambar_lama);
        }

        // Upload gambar baru
        move_uploaded_file($tmp_name, $upload_dir . $gambar_name);
    }

    // Query untuk update data produk
    $query = "UPDATE produk SET 
                nama_produk = '$nama_produk', 
                kategori_produk = '$kategori_produk', 
                gambar_name = '$gambar_name', 
                harga = '$harga', 
                deskripsi = '$deskripsi', 
                stok = '$stok' 
              WHERE id_produk = $id_produk";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('✅ Produk berhasil diupdate!');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('❌ Gagal mengupdate produk: " . mysqli_error($conn) . "');
                window.history.back();
              </script>";
    }
}
?>
