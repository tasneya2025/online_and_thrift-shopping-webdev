<?php
session_start();
require_once("../../Model/paymentModel.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header("Location: ../login/login.php");
    exit();
}

$order_id = (int)($_GET['order_id'] ?? 0);
$order    = $order_id ? getOrder($order_id) : null;

if (!$order || $order['buyer_email'] !== $_SESSION['email']) {
    header("Location: buyerViewCart.php");
    exit();
}

$errors = $_SESSION['card_errors'] ?? [];
unset($_SESSION['card_errors']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppon | Card Payment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/viewCart.css">
    <link rel="stylesheet" href="../css/payment.css">
</head>
<body>

<div class="dashboard-container">

    <aside class="sidebar">
        <div class="logo-section">
            <div class="logo-box"><i class="fa-solid fa-bag-shopping"></i></div>
            <div>
                <h2>Shoppon</h2>
                <p>Buyer Center</p>
            </div>
        </div>
        <nav class="side-nav">
            <a href="buyerDashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="buyerViewCart.php" class="active"><i class="fa-solid fa-cart-shopping"></i> View Cart</a>
            <a href="buyerSettings.php"><i class="fa-solid fa-gear"></i> Settings</a>
        </nav>
        <div class="bottom-section">
            <div class="account-info">
                <p>Buyer Account</p>
                <strong style="color:#3b71fe"><?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?></strong><br>
                <small><?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></small>
            </div>
            <a href="../../Controller/logoutController.php" class="logout-link">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </aside>

    <main class="main-content">
        <div class="pay-page-wrap">

            <div class="pay-breadcrumb">
                <a href="buyerViewCart.php"><i class="fa-solid fa-cart-shopping"></i> Cart</a>
                <i class="fa-solid fa-chevron-right sep"></i>
                <span class="active">Card Payment</span>
            </div>

            <div class="pay-form-box">
                <h2 class="pay-form-title">
                    <i class="fa-solid fa-credit-card"></i> Enter Card Details
                </h2>

                <?php if (!empty($errors)): ?>
                <div class="pay-errors">
                    <?php foreach ($errors as $e): ?>
                        <div class="pay-error-item"><i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($e); ?></div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <form action="../../Controller/paymentController.php" method="POST">
                    <input type="hidden" name="action" value="process_card">
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">

                    <div class="pay-field">
                        <label for="card_number">Card Number</label>
                        <div class="input-icon-wrap">
                            <i class="fa-solid fa-credit-card field-icon"></i>
                            <input type="text" id="card_number" name="card_number"
                                   placeholder="1234 5678 9012 3456"
                                   maxlength="19" autocomplete="cc-number" required>
                        </div>
                    </div>

                    <div class="pay-field">
                        <label for="card_name">Cardholder Name</label>
                        <div class="input-icon-wrap">
                            <i class="fa-solid fa-user field-icon"></i>
                            <input type="text" id="card_name" name="card_name"
                                   placeholder="Name as on card"
                                   autocomplete="cc-name" required>
                        </div>
                    </div>

                    <div class="pay-row">
                        <div class="pay-field">
                            <label for="expiry">Expiry Date</label>
                            <div class="input-icon-wrap">
                                <i class="fa-solid fa-calendar field-icon"></i>
                                <input type="text" id="expiry" name="expiry"
                                       placeholder="MM/YY" maxlength="5"
                                       autocomplete="cc-exp" required>
                            </div>
                        </div>
                        <div class="pay-field">
                            <label for="cvv">CVV / CVC</label>
                            <div class="input-icon-wrap">
                                <i class="fa-solid fa-lock field-icon"></i>
                                <input type="password" id="cvv" name="cvv"
                                       placeholder="•••" maxlength="4"
                                       autocomplete="cc-csc" required>
                            </div>
                        </div>
                    </div>

                    <div class="pay-total-row">
                        <span>Order Total:</span>
                        <strong>$<?php echo number_format($order['total_amount'], 2); ?></strong>
                    </div>

                    <button type="submit" class="btn-pay">
                        <i class="fa-solid fa-shield-halved"></i>
                        Pay $<?php echo number_format($order['total_amount'], 2); ?>
                    </button>

                    <a href="buyerViewCart.php" class="btn-pay-cancel">
                        <i class="fa-solid fa-arrow-left"></i> Back to Cart
                    </a>
                </form>

                <div class="pay-secure-note">
                    <i class="fa-solid fa-lock"></i> Your payment info is encrypted and secure
                </div>
            </div>

        </div>
    </main>
</div>

<script>
var expiryInp = document.getElementById('expiry');
expiryInp.addEventListener('input', function () {
    var v = this.value.replace(/\D/g, '').substring(0, 4);
    if (v.length > 2) v = v.substring(0, 2) + '/' + v.substring(2);
    this.value = v;
});

var numInput = document.getElementById('card_number');
numInput.addEventListener('input', function () {
    var v = this.value.replace(/\D/g, '').substring(0, 16);
    this.value = v.replace(/(.{4})/g, '$1 ').trim();
});
</script>

</body>
</html>