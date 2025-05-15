<?php
include "config.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['username'];
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['role'] = 'user'; // opsional, jika ingin membedakan role

        header("Location: index.php");
        exit;
    } else {
        header("Location: login_user.php?error=1");
        exit;
    }
}
?>
