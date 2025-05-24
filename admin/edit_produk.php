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
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        label {
            font-weight: 500;
            display: block;
            margin-bottom: 6px;
            color: #444;
        }

        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            background: #f9f9f9;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #007bff;
            background: #fff;
        }

        textarea {
            min-height: 100px;
        }

        img {
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 16px; /* 👉 jarak antara file input dan tombol */
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Produk</h2>
        <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_produk" value="<?= $data['id_produk']; ?>">
            <input type="hidden" name="gambar_lama" value="<?= $data['gambar_name']; ?>">

            <label>Nama Produk:</label>
            <input type="text" name="nama_produk" value="<?= htmlspecialchars($data['nama_produk']); ?>" required>

            <label>Kategori:</label>
            <select name="kategori_produk" required>
                <option value="Agama" <?= ($data['kategori_produk'] == 'Agama') ? 'selected' : ''; ?>>Agama</option>
                <option value="Mapel" <?= ($data['kategori_produk'] == 'Mapel') ? 'selected' : ''; ?>>Mapel</option>
                <option value="Novel" <?= ($data['kategori_produk'] == 'Novel') ? 'selected' : ''; ?>>Novel</option>
            </select>

            <label>Harga:</label>
            <input type="number" name="harga" value="<?= $data['harga']; ?>" required>

            <label>Deskripsi:</label>
            <textarea name="deskripsi" required><?= htmlspecialchars($data['deskripsi']); ?></textarea>

            <label>Stok:</label>
            <input type="number" name="stok" value="<?= $data['stok']; ?>" required>

            <label>Gambar Saat Ini:</label>
            <img src="uploads/<?= $data['gambar_name']; ?>" alt="Gambar Produk" width="120">

            <label>Gambar Baru:</label>
            <input type="file" name="gambar_name" accept="image/*">

            <button type="submit">💾 Simpan Perubahan</button>
        </form>
    </div>

</body>

</html>
