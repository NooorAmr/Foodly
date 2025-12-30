<?php
session_start();
require_once '../PHP/config.php';

// التحقق من تسجيل الدخول
if (!is_logged_in()) {
    header("Location: ../PHP/Login.php?error=not_logged_in");
    exit();
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$payment_method = isset($_GET['payment']) ? $_GET['payment'] : 'cash';

if ($order_id == 0) {
    header("Location: ../HTML/ordering.html");
    exit();
}

// جلب تفاصيل الطلب
$query = "SELECT o.*, GROUP_CONCAT(oi.product_name SEPARATOR ', ') as items
          FROM orders o
          LEFT JOIN order_items oi ON o.order_id = oi.order_id
          WHERE o.order_id = ? AND o.user_id = ?
          GROUP BY o.order_id";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ii", $order_id, $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header("Location: ../HTML/ordering.html");
    exit();
}

$order = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="../images/favicon.png" type="image/x-icon">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .success-container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 50px 30px;
            text-align: center;
        }

        .success-icon {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: scaleUp 0.5s ease 0.3s both;
        }

        @keyframes scaleUp {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        .success-icon i {
            font-size: 3rem;
            color: #28a745;
        }

        .success-header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .success-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .success-body {
            padding: 40px;
        }

        .order-details {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .order-details h3 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #666;
            font-weight: 600;
        }

        .detail-value {
            color: #333;
            font-weight: bold;
        }

        .payment-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .payment-badge.cash {
            background: #ffc107;
            color: #856404;
        }

        .payment-badge.visa {
            background: #007bff;
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .success-footer {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            color: #666;
        }

        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            background: #667eea;
            position: absolute;
            animation: confetti-fall 3s linear;
        }

        @keyframes confetti-fall {
            to {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }
    </style>
</head>

<body>
    <div class="success-container">
        <div class="success-header">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h1>Order Successful!</h1>
            <p>Your order has been placed successfully</p>
        </div>

        <div class="success-body">
            <div class="order-details">
                <h3><i class="fas fa-receipt"></i> Order Details</h3>
                <div class="detail-row">
                    <span class="detail-label">Order ID:</span>
                    <span class="detail-value">#<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total Amount:</span>
                    <span class="detail-value"><?php echo number_format($order['total_price'], 2); ?> EGP</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Method:</span>
                    <span class="detail-value">
                        <span class="payment-badge <?php echo $order['payment_method']; ?>">
                            <?php echo $order['payment_method'] == 'visa' ? 'Visa Card' : 'Cash on Delivery'; ?>
                        </span>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Order Status:</span>
                    <span class="detail-value" style="color: #ffc107;">Pending</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Items:</span>
                    <span class="detail-value" style="font-size: 0.9rem;"><?php echo htmlspecialchars($order['items']); ?></span>
                </div>
            </div>

            <div style="background: #e7f3ff; padding: 20px; border-radius: 10px; border-left: 4px solid #007bff;">
                <p style="margin: 0; color: #004085;">
                    <i class="fas fa-info-circle"></i>
                    <strong>What's Next?</strong><br>
                    Your order is being prepared and will be delivered soon. You can track your order status in your profile.
                </p>
            </div>

            <div class="action-buttons">
                <a href="../index.php" class="btn btn-primary">
                    <i class="fas fa-home"></i> Back to Home
                </a>
                <a href="user account.php" class="btn btn-secondary">
                    <i class="fas fa-user"></i> View Profile
                </a>
            </div>
        </div>

        <div class="success-footer">
            <p>Thank you for choosing Foodly! 🍕</p>
        </div>
    </div>

    <script>
        // Create confetti effect
        function createConfetti() {
            for (let i = 0; i < 50; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti';
                    confetti.style.left = Math.random() * 100 + '%';
                    confetti.style.background = ['#667eea', '#764ba2', '#28a745', '#ffc107', '#007bff'][Math.floor(Math.random() * 5)];
                    confetti.style.animationDelay = Math.random() * 0.5 + 's';
                    document.body.appendChild(confetti);

                    setTimeout(() => confetti.remove(), 3000);
                }, i * 30);
            }
        }

        // Run confetti on page load
        window.addEventListener('load', createConfetti);
    </script>
</body>

</html>
<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>