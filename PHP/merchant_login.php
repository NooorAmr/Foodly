<?php
      include 'config.php';

      // معالجة تسجيل دخول التاجر
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = clean_input($_POST['username']);
        $password = $_POST['password'];

        // التحقق من البيانات
        if (empty($username) || empty($password)) {
          header("Location: ../PHP/merchant_login.php?error=empty");
          exit();
        }

        // البحث عن التاجر في قاعدة البيانات
        $query = "SELECT * FROM merchants WHERE username = ? OR email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $username, $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
          $merchant = mysqli_fetch_assoc($result);

          // التحقق من كلمة المرور
          if ($password == $merchant['password']) {

            // تسجيل الدخول ناجح
            $_SESSION['merchant_id'] = $merchant['merchant_id'];
            $_SESSION['merchant_username'] = $merchant['username'];
            $_SESSION['merchant_email'] = $merchant['email'];
            $_SESSION['merchant_business_name'] = $merchant['business_name'];

            // التوجيه لصفحة CRUD
            header("Location: ../HTML/CRUD.html");
            exit();
          } else {
            header("Location: ../PHP/merchant_login.php?error=invalid");
            exit();
          }
        } else {
          header("Location: ../PHP/merchant_login.php?error=invalid");
          exit();
        }

        mysqli_stmt_close($stmt);
      }

      mysqli_close($conn);
      ?>
      
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="shortcut icon" href="../images/favicon.png" type="image/x-icon">
  <title>Merchant Login - Foodly</title>

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

    .merchant-login-container {
      max-width: 450px;
      width: 100%;
      background: white;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
      overflow: hidden;
    }

    .merchant-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 40px 30px;
      text-align: center;
    }

    .merchant-header i {
      font-size: 3rem;
      margin-bottom: 15px;
    }

    .merchant-header h1 {
      font-size: 2rem;
      margin-bottom: 10px;
    }

    .merchant-header p {
      opacity: 0.9;
      font-size: 1rem;
    }

    .login-form {
      padding: 40px;
    }

    .alert {
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 0.9rem;
    }

    .alert-danger {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .form-group {
      margin-bottom: 25px;
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

    .input-wrapper {
      position: relative;
    }

    .input-wrapper i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #667eea;
    }

    .form-group input {
      width: 100%;
      padding: 12px 15px 12px 45px;
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

    .submit-btn {
      width: 100%;
      padding: 15px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1.1rem;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s;
      margin-top: 10px;
    }

    .submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    .form-footer {
      text-align: center;
      margin-top: 25px;
      padding-top: 20px;
      border-top: 1px solid #e0e0e0;
    }

    .form-footer a {
      color: #667eea;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s;
    }

    .form-footer a:hover {
      text-decoration: underline;
    }

    .info-note {
      background: #e7f3ff;
      padding: 15px;
      border-radius: 8px;
      border-left: 4px solid #007bff;
      margin-top: 20px;
      font-size: 0.9rem;
      color: #004085;
    }

    .info-note i {
      margin-right: 8px;
    }
  </style>
</head>

<body>
  <div class="merchant-login-container">
    <div class="merchant-header">
      <i class="fas fa-store"></i>
      <h1>Merchant Portal</h1>
      <p>Access your business dashboard</p>
    </div>

    <form action="../PHP/merchant_login.php" method="POST" class="login-form">

      <!-- Error Messages -->

      <div class="form-group">
        <label for="username">
          <i class="fas fa-user"></i> Username or Email
        </label>
        <div class="input-wrapper">
          <i class="fas fa-user"></i>
          <input type="text" id="username" name="username" placeholder="Enter your username" required>
        </div>
      </div>

      <div class="form-group">
        <label for="password">
          <i class="fas fa-lock"></i> Password
        </label>
        <div class="input-wrapper">
          <i class="fas fa-lock"></i>
          <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>
      </div>

      <button type="submit" class="submit-btn" href="CRUD.html">
        <i class="fas fa-sign-in-alt"></i> Login to Dashboard
      </button>

      <div class="info-note">
        <i class="fas fa-info-circle"></i>
        <strong>Demo Credentials:</strong><br>
        Username: merchant1<br>
        Password: merchant123
      </div>

      <div class="form-footer">
        <a href="../index.html">
          <i class="fas fa-arrow-left"></i> Back to Home
        </a>
      </div>
    </form>
  </div>
</body>

</html>