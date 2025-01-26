<?php
session_start();
include '../components/connection.php'; // পাথ ঠিক করুন

// যদি ইউজার লগইন করা থাকে, তাহলে তাকে ড্যাশবোর্ডে রিডাইরেক্ট করুন
if (isset($_SESSION['user_id'])) {
    header('location: home.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Coffee - Home</title>
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="admin_style.css?v=<?php echo time(); ?>">
</head>
<body>
    <header class="header">
        <div class="flex">
            <a href="index.php" class="logo"><img src="../img/logo.jpg" alt="Green Coffee Logo"></a>
            <nav class="navbar">
                <a href="index.php">Home</a>
                <a href="about.php">About</a>
                <a href="products.php">Products</a>
                <a href="contact.php">Contact</a>
            </nav>
            <div class="icons">
                <a href="login.php" class="btn">Login</a>
                <a href="register.php" class="btn">Register</a>
            </div>
        </div>
    </header>

    <div class="main">
        <div class="banner">
            <h1>Welcome to Green Coffee</h1>
        </div>
        
        <div class="title2">
            <a href="index.php">Home</a><span> / Welcome</span>
        </div>

        <section class="dashboard">
            <h1 class="heading">Explore Our Products</h1>

            <div class="box-container">
                <div class="box">
                    <h3>Fresh Coffee Beans</h3>
                    <p>Our coffee beans are sourced from the best farms around the world.</p>
                    <a href="view_product.php" class="btn">View Products</a>
                </div>

                <div class="box">
                    <h3>Organic & Sustainable</h3>
                    <p>We are committed to organic and sustainable farming practices.</p>
                    <a href="about.php" class="btn">Learn More</a>
                </div>

                <div class="box">
                    <h3>Special Offers</h3>
                    <p>Check out our latest offers and discounts on premium coffee.</p>
                    <a href="products.php" class="btn">View Offers</a>
                </div>

                <div class="box">
                    <h3>Contact Us</h3>
                    <p>Have questions? Feel free to reach out to us.</p>
                    <a href="contact.php" class="btn">Contact</a>
                </div>
            </div>
        </section>
    </div>

    <footer class="footer">
        <div class="credit">
            &copy; <?php echo date("Y"); ?> Green Coffee | All Rights Reserved
        </div>
    </footer>

    <!-- SweetAlert CDN link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <!-- Custom JS link -->
    <script type="text/javascript" src="script.js"></script>
</body>
</html>