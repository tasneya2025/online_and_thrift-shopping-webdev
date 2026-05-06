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

$errors = $_SESSION['bkash_errors'] ?? [];
unset($_SESSION['bkash_errors']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppon | bKash Payment</title>
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
                <span class="active">bKash Payment</span>
            </div>

            <div class="pay-form-box">
                <h2 class="pay-form-title bkash-title">
                    <span class="bkash-badge-sm">b</span> Confirm bKash Payment
                </h2>

                <?php if (!empty($errors)): ?>
                <div class="pay-errors">
                    <?php foreach ($errors as $e): ?>
                        <div class="pay-error-item"><i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($e); ?></div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <form action="../../Controller/paymentController.php" method="POST">
                    <input type="hidden" name="action" value="process_bkash">
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">

                    <div class="pay-field">
                        <label for="bkash_number">Your bKash Number</label>
                        <div class="input-icon-wrap">
                            <span class="field-bkash-icon">b</span>
                            <input type="tel" id="bkash_number" name="bkash_number"
                                   placeholder="01XXXXXXXXX" maxlength="11" required>
                        </div>
                    </div>

                    <div class="pay-field">
                        <label for="transaction_id">Transaction ID (TxnID)</label>
                        <div class="input-icon-wrap">
                            <i class="fa-solid fa-receipt field-icon"></i>
                            <input type="text" id="transaction_id" name="transaction_id"
                                   placeholder="e.g. ABC1234XYZ" required>
                        </div>
                    </div>

                    <div class="pay-total-row">
                        <span>Order Total:</span>
                        <strong>$<?php echo number_format($order['total_amount'], 2); ?></strong>
                    </div>

                    <button type="submit" class="btn-pay btn-pay-bkash">
                        <span class="bkash-badge-sm">b</span>
                        Confirm bKash Payment
                    </button>

                    <a href="buyerViewCart.php" class="btn-pay-cancel">
                        <i class="fa-solid fa-arrow-left"></i> Back to Cart
                    </a>
                </form>

                <div class="pay-secure-note">
                    <i class="fa-solid fa-shield-halved" style="color:#e2136e"></i>
                    Secured by bKash encryption
                </div>
            </div>

        </div>
    </main>
</div>

</body>
</html>