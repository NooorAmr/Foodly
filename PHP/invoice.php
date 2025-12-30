<?php
session_start();
require_once '../PHP/config.php';

// التحقق من تسجيل الدخول
if (!is_logged_in()) {
    header("Location: Login.php?error=not_logged_in");
    exit();
}

// الحصول على بيانات الطلب من الجلسة
if (!isset($_SESSION['current_order_data']) || !isset($_SESSION['current_order_total'])) {
    header("../HTML/ordering.html");
    exit();
}

$order_data = $_SESSION['current_order_data'];
$total_price = $_SESSION['current_order_total'];
$user = get_logged_user();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Order Summary</title>
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
            padding: 20px;
        }

        .invoice-container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .invoice-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .invoice-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .invoice-body {
            padding: 40px;
        }

        .customer-info {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .customer-info h3 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        .customer-info p {
            margin: 8px 0;
            color: #555;
        }

        .customer-info i {
            color: #667eea;
            margin-right: 10px;
            width: 20px;
        }

        .order-items {
            margin-bottom: 30px;
        }

        .order-items h3 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 1.3rem;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }

        .item {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            margin-bottom: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            transition: transform 0.2s;
        }

        .item:hover {
            transform: translateX(5px);
            background: #e9ecef;
        }

        .item-name {
            font-weight: 600;
            color: #333;
        }

        .item-price {
            color: #667eea;
            font-weight: bold;
        }

        .total-section {
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .total-section h3 {
            color: white;
            font-size: 1.5rem;
            text-align: right;
        }

        .payment-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .payment-section h3 {
            color: #667eea;
            margin-bottom: 20px;
            text-align: center;
        }

        .payment-options {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 25px;
        }

        .payment-option {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .payment-option input[type="radio"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .payment-option label {
            font-size: 1.1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .payment-option i {
            font-size: 1.5rem;
            color: #667eea;
        }

        .buy-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        .buy-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .back-btn:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .invoice-footer {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h1><i class="fas fa-receipt"></i> Invoice</h1>
            <p>Order Summary & Payment</p>
        </div>

        <div class="invoice-body">
            <!-- Customer Information -->
            <div class="customer-info">
                <h3><i class="fas fa-user-circle"></i> Customer Information</h3>
                <p><i class="fas fa-user"></i> <strong>Name:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                <p><i class="fas fa-envelope"></i> <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><i class="fas fa-calendar"></i> <strong>Date:</strong> <?php echo date('d M Y, h:i A'); ?></p>
            </div>

            <!-- Order Items -->
            <div class="order-items">
                <h3><i class="fas fa-shopping-cart"></i> Order Items</h3>
                <?php foreach ($order_data as $item): ?>
                <div class="item">
                    <span class="item-name"><?php echo htmlspecialchars($item['name']); ?></span>
                    <span class="item-price"><?php echo number_format($item['price'], 2); ?> EGP</span>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Total -->
            <div class="total-section">
                <h3>Total: <?php echo number_format($total_price, 2); ?> EGP</h3>
            </div>

            <!-- Payment Method -->
            <form action="../PHP/process_payment.php" method="POST" id="paymentForm">
                <div class="payment-section">
                    <h3>Select Payment Method</h3>
                    <div class="payment-options">
                        <div class="payment-option">
                            <input type="radio" id="cash" name="payment_method" value="cash" checked>
                            <label for="cash">
                                <i class="fas fa-money-bill-wave"></i> Cash on Delivery
                            </label>
                        </div>
                        <div class="payment-option">
                            <input type="radio" id="visa" name="payment_method" value="visa">
                            <label for="visa">
                                <i class="fas fa-credit-card"></i> Visa Card
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="buy-btn">
                        <i class="fas fa-check-circle"></i> Confirm Order
                    </button>
                </div>
            </form>

            <center>
                <a href="ordering.html" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Menu
                </a>
            </center>
        </div>

        <div class="invoice-footer">
            <p>&copy; 2025 Foodly Restaurant. All rights reserved.</p>
        </div>
    </div>
</body>

</html>