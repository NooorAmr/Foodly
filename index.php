<?php
session_start();
require_once 'PHP/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Linking CSS -->
    <link rel="stylesheet" href="CSS/style.css">
    <!-- Linking FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Linking Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet">
    <title>Home Page</title>
    <!-- favicon -->
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
</head>

<body>

    <!-- NavBar Section -->
    <nav class="navbar">
        <div class="nav-container">

            <!-- Logo -->
            <div class="logo">
                <a href="index.php">Foodly</a>
            </div>

            <!-- Search Bar -->
            <div class="search-box">
                <input type="text" placeholder="What are you craving?">
                <button class="search-btn">Search</button>
            </div>

            <!-- Right Buttons -->
            <div class="nav-actions">

                <?php if (!isset($_SESSION['user_id']) && !isset($_SESSION['merchant_id'])): ?>
                    <button class="login btn" onclick="location.href='PHP/Login.php'">Login</button>
                    <button class="signup btn" onclick="location.href='PHP/merchant_login.php'">Login As Merchant</button>

                <?php elseif (isset($_SESSION['user_id'])): ?>
                    <span class="welcome">Welcome, <?php echo $_SESSION['username']; ?></span>
                    <button class="login btn" onclick="location.href='PHP/logout.php'">Logout</button>

                <?php elseif (isset($_SESSION['merchant_id'])): ?>
                    <span class="welcome">Merchant: <?php echo $_SESSION['merchant_username']; ?></span>
                    <button class="login btn" onclick="location.href='PHP/logout.php'">Logout</button>
                <?php endif; ?>

            </div>
        </div>
    </nav>

    <main>
        <!-- Header -->
        <header id="Home">
            <div class="container">
                <picture>
                    <img src="images/hero-img.png" alt="A black dish filled with a colorful fresh vegetables and bread">
                </picture>
                <div class="head-content">
                    <h2>Enjoy Your Healthy <br> Delicious Food</h2>
                    <p>Sed autem laudantium dolores. Voluptatem itaque ea consequatur eveniet.</p>
                    <div class="btn-content">
                        <a href="./HTML/menu.html">Check The Menu</a>
                        <a href="https://www.youtube.com/" class="watch-video">
                            <span class="play-btn">
                                <i class="fa-solid fa-play"></i>
                            </span>
                            <span>Watch a Video</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>


        <!-- Cards Section -->
        <section class="cards-section">
            <div class="container">
                <h2>Explore Our Services</h2>
                <div class="cards-wrapper">

                    <a href="HTML/ordering.html" class="card">
                        <img src="images/man-orders-food-lunch-online-using-smartphone_247622-29928.jpg"
                            alt="Contact Us">
                        <h3>Order Your Food</h3>
                        <p>You Can Choose alot of delicious food</p>
                    </a>
                    <a href="HTML/menu.html" class="card">
                        <img src="images/fried-chicken-with-tomato-slices-onions.jpg" alt="Menu">
                        <h3>Menu</h3>
                        <p>Check out our delicious meals and specials.</p>
                    </a>

                    <a href="HTML/contact.html" class="card">
                        <img src="images/office-desktop-with-laptop-business-man.jpg" alt="Contact Us">
                        <h3>Contact Us</h3>
                        <p>Get in touch with us for any queries or feedback.</p>
                    </a>
                    <a href="HTML/FAQ.html" class="card">
                        <img src="images/question-mark-query-information-support-service-graphic.jpg" alt="Contact Us">
                        <h3>FAQ</h3>
                        <p>Frequently Asked Questions</p>
                    </a>
                    <a href="PHP/user account.php" class="card">
                        <img src="images/secure-payment-protection-personal-financial-data-man-manages-finances-access-account-app_773844-573.jpg"
                            alt="Contact Us">
                        <h3>User Account</h3>
                        <p>You Can See the history of your orders</p>
                    </a>
                    <a href="HTML/about.html" class="card">
                        <img src="images/istockphoto-1402604850-612x612.jpg" alt="Contact Us">
                        <h3>About Us</h3>
                        <p>You can know all of details about our Story</p>
                    </a>
                </div>
            </div>
        </section>


        <!-- Testimonials Section -->
        <section class="testimonials">
            <div class="container">
                <h2>What Our Customers Say</h2>
                <div class="testimonial-slider">
                    <div class="testimonial active">
                        <img src="images/2.jpg" alt="Sarah M." class="testimonial-img">
                        <p>"The food was amazing and delivery was super fast! Highly recommended."</p>
                        <span>- Mohamed M.</span>
                    </div>
                    <div class="testimonial">
                        <img src="images/3.jpg" alt="John D." class="testimonial-img">
                        <p>"Healthy, delicious, and fresh. I love ordering from Foodly!"</p>
                        <span>- John D.</span>
                    </div>
                    <div class="testimonial">
                        <img src="images/work-7.jpg" alt="Emma W." class="testimonial-img">
                        <p>"Great variety and excellent taste. Perfect for family dinners."</p>
                        <span>- Emma W.</span>
                    </div>
                </div>
                <div class="testimonial-controls">
                    <button id="prev"><i class="fa-solid fa-chevron-left"></i></button>
                    <button id="next"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>
        </section>
    </main>

    <!-- footer -->
    <footer class="footer">

        <div class="footer-col">
            <picture>
                <img class="logo" src="images/favicon.png" alt="our icon">
            </picture>

            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ut perspiciatis unde omnis iste.</p>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>


        <div class="footer-col">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="HTML/menu.html">Menu</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>


        <div class="footer-col">
            <h3>Get in Touch</h3>
            <div class="contact-info">
                <p><i class="fas fa-map-marker-alt"></i> 123 Street, City</p>
                <p><i class="fas fa-phone"></i> +123 456 789</p>
                <p><i class="fas fa-envelope"></i> info@example.com</p>
            </div>
        </div>


        <div class="footer-col newsletter">
            <h3>Newsletter</h3>
            <p>Subscribe to our newsletter to get the latest updates.</p>
            <form>
                <input type="email" placeholder="Enter your email" required>
                <button type="submit">Subscribe</button>
            </form>
        </div>
    </footer>

    <script src="JS/testimonials.js"></script>
</body>

</html>