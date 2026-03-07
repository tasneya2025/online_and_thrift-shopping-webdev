<?php 
session_start(); 

$cookie_email = isset($_COOKIE['user_email']) ? $_COOKIE['user_email'] : "";
$cookie_pass = isset($_COOKIE['user_pass']) ? $_COOKIE['user_pass'] : "";
$cookie_role = isset($_COOKIE['user_role']) ? $_COOKIE['user_role'] : "";
?>

<!DOCTYPE html>
<html>
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

                <?php if(isset($_SESSION['genErr'])): ?>
                    <span class="error-text"><?php echo $_SESSION['genErr']; unset($_SESSION['genErr']); ?></span>
                <?php endif; ?>

                <div class="select-group">
                    <select name="usertype" id="usertype" required>
                        <option value="">Select your role</option>
                        <option value="seller" <?php echo ($cookie_role == 'seller') ? 'selected' : ''; ?>>Seller</option>
                        <option value="buyer" <?php echo ($cookie_role == 'buyer') ? 'selected' : ''; ?>>Buyer</option>
                        <option value="admin" <?php echo ($cookie_role == 'admin') ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
                 
                <div class="input-group">
                    <input type="text" id="email" name="email" value="<?php echo $cookie_email; ?>" required placeholder=" ">
                    <label for="email">Enter email</label>
                </div>
                <?php if(isset($_SESSION['emailErr'])): ?>
                    <span class="error-text">
                        <?php echo $_SESSION['emailErr']; unset($_SESSION['emailErr']); ?>
                    </span>
                <?php endif; ?>

                <div class="input-group">
                    <input type="password" id="password" name="password" value="<?php echo $cookie_pass; ?>" required placeholder=" ">
                    <label for="password">Enter password</label>
                    <i class="fa-solid fa-eye" id="togglePassword"></i>
                </div>
                <?php if(isset($_SESSION['passErr'])): ?>
                    <span class="error-text">
                        <?php echo $_SESSION['passErr']; unset($_SESSION['passErr']); ?>
                    </span>
                <?php endif; ?>

                <div class="auth-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" <?php echo ($cookie_email != "") ? "checked" : ""; ?>> Remember me
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