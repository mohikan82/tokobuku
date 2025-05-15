<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Contact Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #1c1f23;
        }
        .navbar-brand {
            color: #ff4ec7;
            font-weight: bold;
        }
        .navbar-nav .nav-link {
            color: #fff;
        }
        .navbar-nav .nav-link.active {
            color: #ff4ec7;
        }
        .hero {
            background-color: #0d6efd;
            color: white;
            padding: 60px 0;
            text-align: center;
        }
        .contact-section {
            padding: 60px 0;
        }
        .footer {
            background-color: #1c1f23;
            color: white;
            text-align: center;
            padding: 20px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">BHUMIDA BOOK STORE</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
                <li class="nav-item"><a href="contact.php" class="nav-link active">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="container">
        <h1>Contact us</h1>
        <p>"Buku Adalah Jendela, Kami Kuncinya."</p>
    </div>
</section>

<!-- Contact Section -->
<section class="contact-section">
    <div class="container">
        <div class="text-center mb-5">
            <h3>Contact Us</h3>
            <p>We'd love to hear from you!</p>
        </div>
        <div class="row">
            <!-- Form -->
            <div class="col-md-6">
                <form>
                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name *</label>
                        <input type="text" class="form-control" id="name" value="Mohikan">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control" id="email" placeholder="example@email.com">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Your Message *</label>
                        <textarea class="form-control" id="message" rows="4" placeholder="Write your message here..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
            <!-- Info -->
            <div class="col-md-6">
                <h5>Store Address</h5>
                <p>Jl. Buku No.123, Jakarta, Indonesia</p>
                <h5>Email</h5>
                <p>info@bookstore.com</p>
                <h5>Phone</h5>
                <p>+62 812 3456 7890</p>
                <h5>Working Hours</h5>
                <p>Monday - Friday: 9AM - 6PM<br>Saturday - Sunday: 10AM - 4PM</p>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        &copy; 2025 | BHUMIDA BOOKSTORE | Mohikan | All rights reserved.
    </div>
</footer>

</body>
</html>
