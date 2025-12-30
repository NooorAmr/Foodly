<?php
session_start();
require_once '../PHP/config.php';

// التحقق من تسجيل الدخول
if (!is_logged_in()) {
    header("Location: ../PHP/Login.php?error=not_logged_in");
    exit();
}

// التحقق من وجود بيانات الطلب
if (!isset($_SESSION['current_order_data']) || !isset($_SESSION['current_order_total'])) {
    header("Location: ../HTML/ordering.html");
    exit();
}

$total_price = $_SESSION['current_order_total'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visa Payment</title>
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

        .payment-container {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .payment-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .payment-header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .payment-header .amount {
            font-size: 2.5rem;
            font-weight: bold;
            margin-top: 15px;
        }

        .card-visual {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            margin: 30px;
            padding: 25px;
            border-radius: 15px;
            color: white;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            position: relative;
            min-height: 200px;
        }

        .card-visual::before {
            content: '';
            position: absolute;
            top: 15px;
            right: 20px;
            width: 50px;
            height: 50px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white"><circle cx="9" cy="12" r="8"/><circle cx="15" cy="12" r="8" fill="%23f79e1b"/></svg>');
            background-size: contain;
        }

        .card-chip {
            width: 50px;
            height: 40px;
            background: linear-gradient(135deg, #f4d03f 0%, #f7971e 100%);
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .card-number {
            font-size: 1.5rem;
            letter-spacing: 3px;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
        }

        .card-details {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
        }

        .card-holder, .card-expiry {
            font-size: 0.9rem;
        }

        .card-holder label, .card-expiry label {
            display: block;
            font-size: 0.7rem;
            opacity: 0.7;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .payment-form {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }

        .form-group label i {
            color: #667eea;
            margin-right: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .submit-btn {
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
            margin-top: 10px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .security-note {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            color: #666;
            font-size: 0.85rem;
            border-top: 1px solid #e0e0e0;
        }

        .security-note i {
            color: #28a745;
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <div class="payment-container">
        <div class="payment-header">
            <h1><i class="fas fa-credit-card"></i> Visa Payment</h1>
            <div class="amount"><?php echo number_format($total_price, 2); ?> EGP</div>
        </div>

        <div class="card-visual" id="cardVisual">
            <div class="card-chip"></div>
            <div class="card-number" id="displayCardNumber">•••• •••• •••• ••••</div>
            <div class="card-details">
                <div class="card-holder">
                    <label>Card Holder</label>
                    <div id="displayCardHolder">YOUR NAME</div>
                </div>
                <div class="card-expiry">
                    <label>Expires</label>
                    <div id="displayExpiry">MM/YY</div>
                </div>
            </div>
        </div>

        <form action="../PHP/complete_visa_payment.php" method="POST" class="payment-form" id="paymentForm">
            <div class="form-group">
                <label for="cardNumber">
                    <i class="fas fa-credit-card"></i> Card Number
                </label>
                <input type="text" id="cardNumber" name="card_number" placeholder="1234 5678 9012 3456" 
                       maxlength="19" required pattern="[0-9\s]{13,19}">
            </div>

            <div class="form-group">
                <label for="cardHolder">
                    <i class="fas fa-user"></i> Card Holder Name
                </label>
                <input type="text" id="cardHolder" name="card_holder" placeholder="JOHN DOE" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="expiry">
                        <i class="fas fa-calendar"></i> Expiry Date
                    </label>
                    <input type="text" id="expiry" name="expiry" placeholder="MM/YY" 
                           maxlength="5" required pattern="[0-9]{2}/[0-9]{2}">
                </div>
                <div class="form-group">
                    <label for="cvv">
                        <i class="fas fa-lock"></i> CVV
                    </label>
                    <input type="text" id="cvv" name="cvv" placeholder="123" 
                           maxlength="3" required pattern="[0-9]{3}">
                </div>
            </div>

            <button type="submit" class="submit-btn">
                <i class="fas fa-check-circle"></i> Pay Now
            </button>

            <a href="invoice.php" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Invoice
            </a>
        </form>

        <div class="security-note">
            <i class="fas fa-shield-alt"></i> Your payment information is secure and encrypted
        </div>
    </div>

    <script>
        // Card number formatting and display
        const cardNumber = document.getElementById('cardNumber');
        const displayCardNumber = document.getElementById('displayCardNumber');
        
        cardNumber.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
            
            if (value.length > 0) {
                let displayValue = formattedValue.padEnd(19, '•');
                displayCardNumber.textContent = displayValue;
            } else {
                displayCardNumber.textContent = '•••• •••• •••• ••••';
            }
        });

        // Card holder name display
        const cardHolder = document.getElementById('cardHolder');
        const displayCardHolder = document.getElementById('displayCardHolder');
        
        cardHolder.addEventListener('input', function(e) {
            if (e.target.value.length > 0) {
                displayCardHolder.textContent = e.target.value.toUpperCase();
            } else {
                displayCardHolder.textContent = 'YOUR NAME';
            }
        });

        // Expiry date formatting and display
        const expiry = document.getElementById('expiry');
        const displayExpiry = document.getElementById('displayExpiry');
        
        expiry.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4);
            }
            e.target.value = value;
            
            if (value.length > 0) {
                displayExpiry.textContent = value;
            } else {
                displayExpiry.textContent = 'MM/YY';
            }
        });

        // CVV - numbers only
        const cvv = document.getElementById('cvv');
        cvv.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });

        // Form validation
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const cardNum = cardNumber.value.replace(/\s/g, '');
            if (cardNum.length < 13 || cardNum.length > 19) {
                e.preventDefault();
                alert('Please enter a valid card number');
                return;
            }
            
            if (cvv.value.length !== 3) {
                e.preventDefault();
                alert('Please enter a valid 3-digit CVV');
                return;
            }
        });
    </script>
</body>

</html>