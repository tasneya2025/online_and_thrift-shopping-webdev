<?php
session_start();
require_once("../../Model/paymentModel.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header("Location: ../login/login.php");
    exit();
}

$order_id = (int)($_GET['order_id'] ?? 0);
$method   = $_GET['method'] ?? 'cod';
$order    = $order_id ? getOrder($order_id) : null;

if (!$order || $order['buyer_email'] !== $_SESSION['email']) {
    header("Location: buyerDashboard.php");
    exit();
}

$method_labels = [
    'card'  => 'Credit / Debit Card',
    'bkash' => 'bKash',
    'cod'   => 'Cash on Delivery',
];
$method_label = $method_labels[$method] ?? 'Unknown';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppon | Order Confirmed</title>
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
            <a href="buyerViewCart.php"><i class="fa-solid fa-cart-shopping"></i> View Cart</a>
            <a href="buyerSettings.php"><i class="fa-solid fa-gear"></i> Settings</a>
            <a href="buyerHistory.php"><i class="fa-regular fa-clock"></i>My History</a>

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
        <div class="confirm-page-wrap">

            <div class="confirm-icon-wrap">
                <div class="confirm-circle">
                    <i class="fa-solid fa-check"></i>
                </div>
            </div>

            <h1 class="confirm-heading">Your item is on the way!</h1>
            <p class="confirm-sub">Thank you for your purchase. Your order has been placed successfully.</p>

            <div class="confirm-detail-card">
                <div class="confirm-detail-row">
                    <span><i class="fa-solid fa-hashtag"></i> Order ID</span>
                    <strong>#<?php echo $order_id; ?></strong>
                </div>
                <div class="confirm-detail-row">
                    <span><i class="fa-solid fa-wallet"></i> Payment Method</span>
                    <strong><?php echo htmlspecialchars($method_label); ?></strong>
                </div>
                <div class="confirm-detail-row">
                    <span><i class="fa-solid fa-dollar-sign"></i> Total Paid</span>
                    <strong class="confirm-total">$<?php echo number_format($order['total_amount'], 2); ?></strong>
                </div>
                <div class="confirm-detail-row">
                    <span><i class="fa-solid fa-circle-check"></i> Status</span>
                    <?php if ($method === 'cod'): ?>
                        <span class="status-badge status-cod">Cash on Delivery</span>
                    <?php else: ?>
                        <span class="status-badge status-paid">Paid</span>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($method === 'cod'): ?>
            <div class="confirm-notice">
                <i class="fa-solid fa-money-bill-wave"></i>
                Please have <strong>$<?php echo number_format($order['total_amount'], 2); ?></strong> ready to pay the delivery person.
            </div>
            <?php endif; ?>

            <div class="confirm-actions">
                <a href="buyerDashboard.php" class="btn-confirm-shop">
                    <i class="fa-solid fa-bag-shopping"></i> Continue Shopping
                </a>
                <a href="buyerViewCart.php" class="btn-confirm-cart">
                    <i class="fa-solid fa-cart-shopping"></i> View Cart
                </a>
            </div>

            <div class="delivery-track">
                <div class="track-step track-done">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Order Placed</span>
                </div>
                <div class="track-line"></div>
                <div class="track-step track-active">
                    <i class="fa-solid fa-box"></i>
                    <span>Processing</span>
                </div>
                <div class="track-line"></div>
                <div class="track-step">
                    <i class="fa-solid fa-truck-fast"></i>
                    <span>On the Way</span>
                </div>
                <div class="track-line"></div>
                <div class="track-step">
                    <i class="fa-solid fa-house"></i>
                    <span>Delivered</span>
                </div>
            </div>

        </div>
    </main>
</div>

</body>
</html>