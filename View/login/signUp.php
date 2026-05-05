<?php 
session_start();

$old = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);

function old($key, $default = '') {
    global $old;
    return htmlspecialchars($old[$key] ?? $default);
}
function oldSelected($key, $value) {
    global $old;
    return isset($old[$key]) && $old[$key] === $value ? 'selected' : '';
}
function oldChecked($key, $value) {
    global $old;
    return isset($old[$key]) && $old[$key] === $value ? 'checked' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up | SHOPPON</title>
    <link rel="stylesheet" href="../../View/css/signUp.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="login-body">
    <div class="signup-card">
        <div class="signup-header">
            <div class="logo-icon">
                <a href="../home/home.php" class="back-home-signup">
                    <i class="fa-solid fa-house"></i>
                </a>
            </div>
            <h1 id ="logo-head">Welcome to Sign Up</h1>
            <p>Log in to your seller account or create a new one</p>
        </div>

        <form action="../../Controller/signUpController.php" method="POST">

            <?php if(isset($_SESSION['signErr'])): ?>
                <span class="error-text"><i class="fa-solid fa-circle-exclamation"></i> <?php echo $_SESSION['signErr']; unset($_SESSION['signErr']); ?></span><br><br>
            <?php endif; ?>
            
             <div class="signup-input-group">
                <label>Register as</label>
                <select name="role" id="usertype" required>
                    <option value="">select your role</option>
                    <option value="1" <?php echo oldSelected('role','1'); ?>>Seller</option>
                    <option value="2" <?php echo oldSelected('role','2'); ?>>Buyer</option>
                    <option value="3" <?php echo oldSelected('role','3'); ?>>Admin</option>
                </select>
            </div> 

            <div class="signup-input-group">
                <label>Full Name</label>
                <input type="text" name="fullname" value="<?php echo old('fullname'); ?>" required>
                <?php if(isset($_SESSION['nameErr'])): ?>
                    <span class="error-text"><?php echo $_SESSION['nameErr']; unset($_SESSION['nameErr']); ?></span>
                <?php endif; ?>
            </div>

            <div class="signup-input-group">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo old('username'); ?>" required>
                <?php if(isset($_SESSION['userErr'])): ?>
                    <span class="error-text"><?php echo $_SESSION['userErr']; unset($_SESSION['userErr']); ?></span>
                <?php endif; ?>
            </div>

            <div class="signup-input-group">
                <label>Email</label>
                <input type="text" name="email" value="<?php echo old('email'); ?>" required>
                <?php if(isset($_SESSION['emailErr'])): ?>
                    <span class="error-text"><?php echo $_SESSION['emailErr']; unset($_SESSION['emailErr']); ?></span>
                <?php endif; ?>
            </div>

            <div class="signup-input-group">
                <label>Gender</label>
                <div class="gender-options">
                    <label><input type="radio" name="gender" value="Male" <?php echo oldChecked('gender','Male'); ?> required> Male</label>
                    <label><input type="radio" name="gender" value="Female" <?php echo oldChecked('gender','Female'); ?>> Female</label>
                    <label><input type="radio" name="gender" value="Other" <?php echo oldChecked('gender','Other'); ?>> Other</label>
                </div>
            </div>

            <div class="signup-input-group">
                <label>Address</label>
                <textarea name="address" required><?php echo old('address'); ?></textarea>
                <?php if(isset($_SESSION['addressErr'])): ?>
                    <span class="error-text"><?php echo $_SESSION['addressErr']; unset($_SESSION['addressErr']); ?></span>
                <?php endif; ?>
            </div>

            <div class="signup-input-group">
                <label>Password</label>
                <div class="password-container">
                    <input type="password" name="password" id="password" required>
                    <i class="fa-solid fa-eye toggle-eye" onclick="toggleVisibility('password', this)"></i>
                </div>
            </div>

            <div class="signup-input-group">
                <label>Confirm Password</label>
                <div class="password-container">
                    <input type="password" name="confirm_password" id="confirm_password" required>
                    <i class="fa-solid fa-eye toggle-eye" onclick="toggleVisibility('confirm_password', this)"></i>
                </div>
                <?php if(isset($_SESSION['passErr'])): ?>
                    <span class="error-text"><?php echo $_SESSION['passErr']; unset($_SESSION['passErr']); ?></span>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn-signup">Create Account</button>
            
            <p class="login-link">Already have an account? <a href="login.php">Log In</a></p>
        </form>
    </div>
    <script src="../js/signUp.js"></script>
</body>
</html>