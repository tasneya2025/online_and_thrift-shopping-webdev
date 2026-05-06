<?php
session_start();

$step    = $_SESSION['fp_step'] ?? 'email';
$error   = $_SESSION['fp_error'] ?? '';
$success = $_SESSION['fp_success'] ?? '';
unset($_SESSION['fp_error'], $_SESSION['fp_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password | SHOPPON</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/forgotPassword.css">
</head>
<body class="login-body">
<div class="fp-card">

    <div class="fp-header">
        <a href="login.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i></a>
        <div class="fp-logo"><i class="fa-solid fa-bag-shopping"></i></div>
        <h1>Shoppon</h1>
        <?php if ($step === 'email'): ?>
            <p>Enter your email to recover your account</p>
        <?php elseif ($step === 'answer'): ?>
            <p>Answer your security question</p>
        <?php else: ?>
            <p>Set your new password</p>
        <?php endif; ?>
    </div>

    <?php if ($error): ?>
        <div class="fp-alert fp-alert-error">
            <i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="fp-alert fp-alert-success">
            <i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($success); ?>
            <br><a href="login.php" class="fp-login-link">Go to Login</a>
        </div>

    <?php elseif ($step === 'email'): ?>

        <div class="fp-steps">
            <div class="fp-step active">1</div>
            <div class="fp-step-line"></div>
            <div class="fp-step">2</div>
            <div class="fp-step-line"></div>
            <div class="fp-step">3</div>
        </div>

        <form action="../../Controller/forgotPasswordController.php" method="POST">
            <input type="hidden" name="step" value="verify_email">
            <div class="fp-input-group">
                <label>Role</label>
                <select name="role" required>
                    <option value="">Select your role</option>
                    <option value="buyer">Buyer</option>
                    <option value="seller">Seller</option>
                </select>
            </div>
            <div class="fp-input-group">
                <label>Email Address</label>
                <div class="fp-input-wrap">
                    <i class="fa-regular fa-envelope"></i>
                    <input type="email" name="email" placeholder="Enter your registered email" required>
                </div>
            </div>
            <button type="submit" class="fp-btn">Continue <i class="fa-solid fa-arrow-right"></i></button>
        </form>

    <?php elseif ($step === 'answer'): ?>

        <div class="fp-steps">
            <div class="fp-step done">✓</div>
            <div class="fp-step-line active-line"></div>
            <div class="fp-step active">2</div>
            <div class="fp-step-line"></div>
            <div class="fp-step">3</div>
        </div>

        <form action="../../Controller/forgotPasswordController.php" method="POST">
            <input type="hidden" name="step" value="verify_answer">
            <div class="fp-question-box">
                <i class="fa-solid fa-shield-halved"></i>
                <span>What is your nickname?</span>
            </div>
            <div class="fp-input-group">
                <label>Your Answer <small>(alphabets only)</small></label>
                <div class="fp-input-wrap">
                    <i class="fa-solid fa-key"></i>
                    <input type="text" name="secret_answer" placeholder="Enter your nickname" pattern="[a-zA-Z]+" title="Alphabets only" required>
                </div>
            </div>
            <button type="submit" class="fp-btn">Verify Answer <i class="fa-solid fa-arrow-right"></i></button>
        </form>

    <?php else: ?>

        <div class="fp-steps">
            <div class="fp-step done">✓</div>
            <div class="fp-step-line active-line"></div>
            <div class="fp-step done">✓</div>
            <div class="fp-step-line active-line"></div>
            <div class="fp-step active">3</div>
        </div>

        <form action="../../Controller/forgotPasswordController.php" method="POST">
            <input type="hidden" name="step" value="reset_password">
            <div class="fp-input-group">
                <label>New Password</label>
                <div class="fp-input-wrap">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="new_password" id="new_password" placeholder="Min 6 characters" required>
                    <i class="fa-solid fa-eye fp-eye" onclick="toggleEye('new_password', this)"></i>
                </div>
            </div>
            <div class="fp-input-group">
                <label>Confirm New Password</label>
                <div class="fp-input-wrap">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Re-enter password" required>
                    <i class="fa-solid fa-eye fp-eye" onclick="toggleEye('confirm_password', this)"></i>
                </div>
            </div>
            <button type="submit" class="fp-btn">Reset Password <i class="fa-solid fa-check"></i></button>
        </form>

    <?php endif; ?>

    <p class="fp-footer">Remember your password? <a href="login.php">Log In</a></p>
</div>

<script>
function toggleEye(id, icon) {
    var input = document.getElementById(id);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
</body>
</html>