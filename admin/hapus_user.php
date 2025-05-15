<?php
include "../config.php";

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];  // casting ke int agar aman

    $delete = mysqli_query($conn, "DELETE FROM user WHERE id_user = $id");

    if ($delete) {
        header("Location: index.php#user");
        exit;
    } else {
        echo "Gagal menghapus user! Error: " . mysqli_error($conn);
    }
} else {
    header("Location: index.php#user");
    exit;
}

