<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Bhumida Bookstore</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: white;
            color: #333;
        }

        /* Navbar */
        .navbar {
            background-color: #2c2f33;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar .brand {
            font-weight: bold;
            font-size: 20px;
            color: #fff;
            text-decoration: none;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        .navbar ul li a {
            color: #ccc;
            text-decoration: none;
        }

        .navbar ul li a:hover,
        .navbar ul li a.active {
            color: #fff;
            text-decoration: underline;
        }

        /* Header Blue */
        .header {
            background-color: #0d6efd;
            color: white;
            text-align: center;
            padding: 40px 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 36px;
        }

        .header p {
            margin-top: 10px;
            font-style: italic;
        }

        /* Content Section */
        .content {
            padding: 40px 20px;
            text-align: center;
        }

        .vision-mission {
            display: flex;
            justify-content: center;
            gap: 80px;
            flex-wrap: wrap;
            margin-bottom: 50px;
        }

        .vision-mission div {
            max-width: 400px;
        }

        .vision-mission h3 {
            color: #0d6efd;
        }

        /* Team Section */
        .team h2 {
            margin-bottom: 30px;
        }

        .team-members {
            display: flex;
            justify-content: center;
            gap: 60px;
            flex-wrap: wrap;
        }

        .team-member {
            text-align: center;
        }

        .team-member img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #f1c40f;
            padding: 10px;
        }

        .team-member h4 {
            margin-top: 15px;
            margin-bottom: 5px;
        }

        .team-member p {
            margin: 0;
            color: #777;
        }

        /* Footer */
        .footer {
            background-color: #2c2f33;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="index.php" class="brand">BHUMIDA BOOKSTORE</a>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php" class="active">About</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </div>

    <!-- Header -->
    <div class="header">
        <h1>About us</h1>
        <p>"Buku Adalah Jendela, Kami Kuncinya."</p>
    </div>

    <!-- Content -->
    <div class="content">
        <!-- Visi & Misi -->
        <div class="vision-mission">
            <div>
                <h3>Visi Kami</h3>
                <p>Menjadi toko buku terdepan yang menginspirasi dan mencerdaskan masyarakat melalui penyediaan buku berkualitas, pelayanan terbaik, dan pengalaman membaca yang menyenangkan.</p>
            </div>
            <div>
                <h3>Misi Kami</h3>
                <ul style="text-align: left;">
                    <li>Menyediakan berbagai jenis buku yang berkualitas, terbarui, dan relevan untuk semua kalangan.</li>
                    <li>Memberikan layanan pelanggan yang ramah, cepat, dan profesional.</li>
                    <li>Menumbuhkan minat baca masyarakat melalui promosi edukasi, diskon, dan program literasi.</li>
                </ul>
            </div>
        </div>

        <!-- Tim Kami -->
        <div class="team">
            <h2>Tim Kami</h2>
            <div class="team-members">
                <div class="team-member">
                    <img src="Img Buku/team1.png " alt="Mochammad">
                    <h4>Mochammad</h4>
                    <p>CEO & Founder</p>
                </div>
                <div class="team-member">
                    <img src="Img Buku/team2.png" alt="Ikhsan">
                    <h4>Ikhsan</h4>
                    <p>CTO</p>
                </div>
                <div class="team-member">
                    <img src="Img Buku/team3.png" alt="Rahadian">
                    <h4>Rahadian</h4>
                    <p>Marketing Lead</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; 2025 | BHUMIDA BOOKSTORE | Manual | All rights reserved.
    </div>
</body>

</html>
