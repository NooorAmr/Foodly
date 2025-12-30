<?php
session_start();
require_once '../PHP/config.php';

// التحقق من تسجيل الدخول
if (!is_logged_in()) {
    header("Location: ../PHP/Login.php?error=not_logged_in");
    exit();
}

// الحصول على بيانات المستخدم
$user = get_logged_user();
$orders = get_user_orders($user['user_id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Account - <?php echo htmlspecialchars($user['username']); ?></title>

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
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="../CSS/User account.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <!-- favicon -->
    <link rel="shortcut icon" href="../images/favicon.png" type="image/x-icon">
    
    <style>
        /* Additional dynamic styles */
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 5px;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-delivered {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .payment-method {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 5px;
            font-size: 0.9rem;
            color: #666;
        }
        
        .payment-method i {
            color: #667eea;
        }
        
        .no-orders {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        
        .no-orders i {
            font-size: 4rem;
            margin-bottom: 15px;
            opacity: 0.3;
        }
        
        .logout-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 12px 25px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
    </style>
</head>

<body>

    <div class="user_information">

        <div class="profile">
            <img src="../images/bc9fd4bd-de9b-4555-976c-8360576c6708.jpg" class="photo" alt="User profile image">
            <h2 class="username"><?php echo htmlspecialchars($user['username']); ?></h2>
            <p class="email"><?php echo htmlspecialchars($user['email']); ?></p>
            <?php if (!empty($user['phone'])): ?>
                <p class="email">
                    <i class="fas fa-phone"></i> 
                    <?php echo htmlspecialchars($user['phone']); ?>
                </p>
            <?php endif; ?>
            <p style="color: #999; font-size: 0.9rem; margin-top: 10px;">
                <i class="fas fa-calendar"></i>
                Member since <?php echo date('M Y', strtotime($user['created_at'])); ?>
            </p>
        </div>

        <div class="history">
            <h3>
                <i class="fas fa-history"></i> Order History 
                <span style="background: #667eea; color: white; padding: 3px 10px; border-radius: 12px; font-size: 0.8rem; margin-left: 10px;">
                    <?php echo count($orders); ?>
                </span>
            </h3>
        </div>

        <div class="orders_list">
            
            <?php if (empty($orders)): ?>
                <div class="no-orders">
                    <i class="fas fa-shopping-bag"></i>
                    <h3>No Orders Yet</h3>
                    <p>Start ordering delicious meals now!</p>
                    <a href='../HTML/menu.html' style="display: inline-block; margin-top: 15px; padding: 10px 25px; background: #667eea; color: white; text-decoration: none; border-radius: 20px;">
                        Browse Menu
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                <div class="order">
                    <div style="display: flex; justify-content: space-between; align-items: start;">
                        <div>
                            <h4>
                                <i class="fas fa-receipt"></i>
                                Order #<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?>
                            </h4>
                            <p style="color: #666; font-size: 0.9rem; margin-top: 5px;">
                                <?php echo htmlspecialchars($order['items']); ?>
                            </p>
                            <p style="margin-top: 8px;">
                                <strong style="color: #667eea; font-size: 1.1rem;">
                                    <?php echo number_format($order['total_price'], 2); ?> EGP
                                </strong>
                            </p>
                        </div>
                        <div style="text-align: right;">
                            <span class="status-badge status-<?php echo $order['order_status']; ?>">
                                <?php 
                                    $status_text = [
                                        'pending' => 'Pending',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                        'delivered' => 'Delivered'
                                    ];
                                    echo $status_text[$order['order_status']]; 
                                ?>
                            </span>
                        </div>
                    </div>
                    <p style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #eee;">
                        <i class="far fa-calendar"></i>
                        <?php echo date('d M Y, h:i A', strtotime($order['order_date'])); ?>
                    </p>
                    <div class="payment-method">
                        <i class="fas fa-<?php echo $order['payment_method'] == 'visa' ? 'credit-card' : 'money-bill-wave'; ?>"></i>
                        <?php echo $order['payment_method'] == 'visa' ? 'Visa Card' : 'Cash on Delivery'; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>

    </div>

    <!-- Logout Button -->
    <a href="../PHP/logout.php" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>

    <!-- footer -->
    <footer class="footer">

        <div class="footer-col">
            <picture>
                <img class="logo" src="../images/favicon.png" alt="our icon">
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
                <li><a href="menu.html">Menu</a></li>
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

    <section>
        <a href="../index.php" class="back-home-btn">
            <i class="fa-solid fa-house"></i> Home
        </a>
    </section>

</body>

</html>
<?php mysqli_close($conn); ?>