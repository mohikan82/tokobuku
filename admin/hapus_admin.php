<?php
include "../config.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $delete = mysqli_query($conn, "DELETE FROM 'admin' WHERE id_admin = $id");

    if ($delete) {
        header("Location: index.php#admin");
        exit;
    } else {
        echo "Gagal menghapus admin!";
    }
} else {
    header("Location: index.php#admin");
    exit;
}
