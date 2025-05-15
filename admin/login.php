<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title >Login Admin</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
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
        <h2 class="text-center mb-4" style="color: black;">LOGIN ADMIN</h2>
        <div class="login-box p-4 shadow bg-light">
            <form action="proses_login.php" method="POST">
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
            <p class="text-center mt-3">Belum punya akun? <a href="register.php">Daftar dulu</a></p>
        </div>
    </div>


</body>

</html>