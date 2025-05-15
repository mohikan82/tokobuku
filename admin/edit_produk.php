<?php
include "../config.php";

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan!";
    exit;
}

$id = $_GET['id'];
$query = "SELECT * FROM produk WHERE id_produk = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Cek jika data tidak ditemukan
if (!$data) {
    echo "Produk tidak ditemukan!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
</head>

<body>

    <h2>Edit Produk</h2>

    <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_produk" value="<?php echo $data['id_produk']; ?>">
        <input type="hidden" name="gambar_lama" value="<?php echo $data['gambar']; ?>">


        <label>Nama Produk:</label><br>
        <input type="text" name="nama_produk" value="<?php echo $data['nama_produk']; ?>"><br><br>
        <label>Kategori:</label><br>

        <select name="kategori_produk" required>
            <option value="Agama" <?php echo ($data['kategori_produk'] == 'Agama') ? 'selected' : ''; ?>>Agama</option>
            <option value="Mapel" <?php echo ($data['kategori_produk'] == 'Mapel') ? 'selected' : ''; ?>>Mapel</option>
            <option value="Novel" <?php echo ($data['kategori_produk'] == 'Novel') ? 'selected' : ''; ?>>Novel</option>
        </select><br><br>


        <label>Harga:</label><br>
        <input type="number" name="harga" value="<?php echo $data['harga']; ?>"><br><br>

        <label>Deskripsi:</label><br>
        <textarea name="deskripsi"><?php echo $data['deskripsi']; ?></textarea><br><br>

        <label>Stok:</label><br>
        <input type="number" name="stok" value="<?php echo $data['stok']; ?>"><br><br>

        <!-- Menampilkan gambar lama -->
        <label>Gambar Saat Ini:</label><br>
        <img src="uploads/<?php echo $data['gambar']; ?>" alt="Gambar Produk" width="100"><br><br>

        <!-- Opsi untuk upload gambar baru -->
        <label>Gambar Baru:</label><br>
        <input type="file" name="gambar"><br><br>

        <button type="submit">Simpan Perubahan</button>
    </form>

</body>

</html>