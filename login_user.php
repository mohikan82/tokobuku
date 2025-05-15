<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login User</title>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <style>
        .main {
            height: 100vh;
            background: linear-gradient(to right, #82c60b, #134afd);
            display: flex;
            justify-content: center;
            align-items: center;
            
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
        }
    </style>
</head>

<body>

    <div class="main d-flex flex-column justify-content-center align-items-center">
        <h2 class="text-center mb-4">LOGIN USER</h2>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger text-center w-100" style="max-width: 400px;">
                Username atau password salah!
            </div>
        <?php endif; ?>
        <div class="login-box p-4 shadow bg-light">
            <form action="proses_login_user.php" method="POST">
                <div class="text-center mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>


                <div class="text-center mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="text" id="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <p class="text-center mt-3">Belum punya akun? <a href="register_user.php">Daftar dulu</a></p>
        </div>
    </div>


</body>

</html>