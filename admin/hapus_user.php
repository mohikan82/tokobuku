<?php
include "../config.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $delete = mysqli_query($conn, "DELETE FROM user WHERE id_user = $id");

    if ($delete) {
        header("Location: index.php#user");
        exit;
    } else {
        echo "Gagal menghapus user!";
    }
} else {
    header("Location: index.php#user");
    exit;
}
