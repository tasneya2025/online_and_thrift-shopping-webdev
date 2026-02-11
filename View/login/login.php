<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | SHOPPON</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body class="login-body">
    <div class="login-card">
        <div class="welcome-panel">
            <div class="welcome-content">
                <h1>Welcome to Log In!</h1>
                <p>Don't have an account?</p>
                <a href="../login/signUp.php" class="btn-signUp-outline" >Sign up here</a>
            </div>
        </div>

        <div class="form-panel">
            <form action="../../Controller/loginController.php" method="POST">
                <h2 id="logTag">Login to Dashboard</h2><br>
                    <select name="usertype" id="usertype">
                    <option id="none" value="">Select your role</option>
                    <option id="seller" value="seller">Seller</option>
                    <option id="buyer" value="buyer">Buyer</option>
                    <option id="admin" value="admin">Admin</option>
                </select><br><br>
                 
                <div class="input-group">
                    <input type="email" id="email" name="email" required placeholder=" ">
                    <label for="email">Enter email</label>
                </div>

                <div class="input-group">
                    <input type="password" id="password" name="password" required placeholder=" ">
                    <label for="password">Enter password</label>
                    <i class="fa-solid fa-eye" id="togglePassword"></i>
                </div>

                <div class="auth-options">
                    <label class="remember-me">
                    <input type="checkbox" name="remember"> Remember me
                    </label>
                    <a href="#" class="forgot-pass">Forgot Password?</a>
                </div>
                <button type="submit" class="btn-login-submit">Log In</button>
            </form>
        </div>
    </div>
    <script src="../js/login.js"></script>
</body>
</html>