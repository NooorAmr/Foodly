<?php
session_start();
require_once 'config.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = clean_input($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        header("Location: ../HTML/Login.php?error=empty");
        exit();
    }

    $query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['phone'] = $user['phone'];

            header("Location: ../index.php");
            exit();

        } else {
            header("Location: ../PHP/Login.php?error=invalid");
            exit();
        }

    } else {
        header("Location: ../PHP/Login.php?error=invalid");
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
  <link rel="stylesheet" href="../CSS/login.css" />
  <title>Sign in & Sign up Form</title>
  <link rel="shortcut icon" href="../images/favicon.png" type="image/x-icon">
  <style>
    /* Alert Styles */
    .alert {
        padding: 12px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 0.9rem;
        animation: slideDown 0.3s ease;
        text-align: center;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="forms-container">
      <div class="signin-signup">

        <!-- Sign In Section -->
        <form action="../PHP/login.php" method="POST" class="sign-in-form">
          <h2 class="title">Sign in</h2>
          
          <!-- Alert Messages -->
          <div id="alertBox"></div>

          <!-- 1st Input Field UserName : -->
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" name="username" placeholder="Username or Email" required />
          </div>
          <!-- 2nd Input Field Password : -->
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required />
          </div>
          <!-- Button -->
          <input type="submit" value="Login" class="btn solid" />

          <!-- Social Media Section -->
          <p class="social-text">Or Sign in with social platforms</p>
          <div class="social-media">
            <a href="#" class="social-icon">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="social-icon">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="social-icon">
              <i class="fab fa-google"></i>
            </a>
            <a href="#" class="social-icon">
              <i class="fab fa-linkedin-in"></i>
            </a>
          </div>
        </form>

        <!-- Sign Up Section -->
        <form action="../PHP/register.php" method="POST" class="sign-up-form">
          <h2 class="title">Sign up</h2>

          <!-- Alert Messages -->
          <div id="alertBoxSignup"></div>

          <!-- 1st Input Field UserName : -->
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" name="username" placeholder="Username" required />
          </div>
          <!-- 2nd Input Field Email : -->
          <div class="input-field">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Email" required />
          </div>
          <!-- 3rd Input Field Password : -->
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required minlength="6" />
          </div>

          <!-- Button -->
          <input type="submit" class="btn" value="Sign up" />

          <!-- Social Media Section -->
          <p class="social-text">Or Sign up with social platforms</p>
          <div class="social-media">
            <a href="#" class="social-icon">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="social-icon">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="social-icon">
              <i class="fab fa-google"></i>
            </a>
            <a href="#" class="social-icon">
              <i class="fab fa-linkedin-in"></i>
            </a>
          </div>
        </form>
      </div>
    </div>

    <!-- Animation Section -->
    <div class="panels-container">
      <!-- Sign In Section -->
      <div class="panel left-panel">
        <div class="content">
          <h3>New here ?</h3>
          <p>
            Join us today and enjoy delicious meals delivered to your doorstep!
          </p>
          <button class="btn transparent" id="sign-up-btn">
            Sign up
          </button>
        </div>
        <img src="../images/log.svg" class="image" alt="a man sitting beside him some flowers with a Rocket">
      </div>

      <!-- Sign Up Section -->
      <div class="panel right-panel">
        <div class="content">
          <h3>One of us ?</h3>
          <p>
            Welcome back! Sign in to continue your culinary journey with us.
          </p>
          <button class="btn transparent" id="sign-in-btn">
            Sign in
          </button>
        </div>
        <img src="../images/register.svg" class="image"
          alt="a woman sitting on a chair infront of her a big Moniter with in a table a plant and under here there is also a plant" />
      </div>
    </div>
  </div>

  <script>
    // Handle URL parameters for alerts
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    const success = urlParams.get('success');
    
    const alertBox = document.getElementById('alertBox');
    const alertBoxSignup = document.getElementById('alertBoxSignup');
    
    if (error) {
      let message = '';
      switch(error) {
        case 'empty':
          message = 'Please fill all fields!';
          break;
        case 'invalid':
          message = 'Invalid username or password!';
          break;
        case 'not_logged_in':
          message = 'Please login first!';
          break;
        case 'empty_signup':
          message = 'Please fill all fields!';
          alertBoxSignup.innerHTML = `<div class="alert alert-danger">${message}</div>`;
          document.querySelector('.container').classList.add('sign-up-mode');
          break;
        case 'invalid_email':
          message = 'Invalid email address!';
          alertBoxSignup.innerHTML = `<div class="alert alert-danger">${message}</div>`;
          document.querySelector('.container').classList.add('sign-up-mode');
          break;
        case 'user_exists':
          message = 'Username or email already exists!';
          alertBoxSignup.innerHTML = `<div class="alert alert-danger">${message}</div>`;
          document.querySelector('.container').classList.add('sign-up-mode');
          break;
        case 'registration_failed':
          message = 'Registration failed. Please try again!';
          alertBoxSignup.innerHTML = `<div class="alert alert-danger">${message}</div>`;
          document.querySelector('.container').classList.add('sign-up-mode');
          break;
        default:
          message = 'An error occurred!';
      }
      
      if (!error.includes('signup') && error !== 'empty_signup' && error !== 'invalid_email' && error !== 'user_exists' && error !== 'registration_failed') {
        alertBox.innerHTML = `<div class="alert alert-danger">${message}</div>`;
      }
    }
    
    if (success === 'registered') {
      alertBox.innerHTML = '<div class="alert alert-success">Registration successful! Please login.</div>';
    }
  </script>

  <script src="../JS/app.js"></script>
</body>

</html>
