<?php
session_start();
require_once("../../Model/cartModel.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header("Location: ../login/login.php");
    exit();
}

$cartItems = getCartItems($_SESSION['email']);

$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$shipping = count($cartItems) > 0 ? 4.99 : 0;
$tax      = round($subtotal * 0.08, 2);
$total    = round($subtotal + $shipping + $tax, 2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppon | View Cart</title>
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
            <a href="sellerHistory.php"><i class="fa-regular fa-clock"></i>My History</a>

        </nav>

        <div class="bottom-section">
            <div class="account-info">
                <p>Buyer Account</p>
                <strong style="color: #3b71fe;"><?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?></strong><br>
                <small><?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></small>
            </div>
            <a href="../../Controller/logoutController.php" class="logout-link">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </aside>

    <main class="main-content">

        <div class="cart-header">
            <h1><span>Your Cart</span></h1>
            <p>
                <?php echo count($cartItems); ?> item<?php echo count($cartItems) != 1 ? 's' : ''; ?> ready for checkout
            </p>
        </div>

        <?php if (isset($_SESSION['cart_msg'])): ?>
            <div class="flash-msg">
                <i class="fa-solid fa-circle-check"></i>
                <?php echo htmlspecialchars($_SESSION['cart_msg']); unset($_SESSION['cart_msg']); ?>
            </div>
        <?php endif; ?>

        <div class="cart-layout">

            <div class="cart-items-wrap">
                <?php if (!empty($cartItems)): ?>
                    <?php foreach ($cartItems as $item): ?>
                    <div class="cart-card" id="card-<?php echo $item['cart_id']; ?>">

                        <img src="../uploads/<?php echo htmlspecialchars($item['image_path']); ?>" alt="Product">

                        <div class="cart-item-info">
                            <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                            <p class="by-seller">by <?php echo htmlspecialchars($item['seller_name'] ?? 'Shoppon Seller'); ?></p>
                        </div>

                        <div class="qty-controls">
                            <a href="../../Controller/cartActionsController.php?action=update&cart_id=<?php echo $item['cart_id']; ?>&qty=<?php echo $item['quantity'] - 1; ?>"
                               class="qty-btn">−</a>
                            <span class="qty-num"><?php echo $item['quantity']; ?></span>
                            <a href="../../Controller/cartActionsController.php?action=update&cart_id=<?php echo $item['cart_id']; ?>&qty=<?php echo $item['quantity'] + 1; ?>"
                               class="qty-btn <?php echo ($item['quantity'] >= $item['stock']) ? 'qty-disabled' : ''; ?>">+</a>
                        </div>

                        <span class="item-price">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>

                        <a href="../../Controller/cartActionsController.php?action=remove&cart_id=<?php echo $item['cart_id']; ?>"
                           class="btn-remove" title="Remove item">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </div>
                    <?php endforeach; ?>

                <?php else: ?>
                    <div class="empty-cart">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <h3>Your cart is empty</h3>
                        <p>Browse the dashboard and add items you love!</p>
                        <a href="buyerDashboard.php" class="btn-shop">Start Shopping</a>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($cartItems)): ?>
            <div class="order-summary">
                <h3>Order Summary</h3>

                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>$<?php echo number_format($subtotal, 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span>$<?php echo number_format($shipping, 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Tax (8%)</span>
                    <span>$<?php echo number_format($tax, 2); ?></span>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-total">
                    <span>Total</span>
                    <span class="total-price">$<?php echo number_format($total, 2); ?></span>
                </div>

                <form action="../../Controller/paymentController.php" method="POST" id="paymentForm">
                    <input type="hidden" name="action" value="select_method">
                    <input type="hidden" name="payment_method" id="hiddenMethod" value="">

                    <div class="payment-section">
                        <h4 class="payment-section-title">
                            <i class="fa-solid fa-wallet"></i> Choose Payment Method
                        </h4>

                        <div class="pay-method-label" id="label-online">
                            <div class="pay-method-box" onclick="selectOnline()">
                                <i class="fa-solid fa-globe"></i>
                                <span>Online Payment</span>
                                <i class="fa-solid fa-chevron-down pay-chevron" id="chevronOnline"></i>
                            </div>
                        </div>

                        <div class="pay-sub-options" id="onlineSubOptions">
                            <div class="pay-sub-label" id="label-card">
                                <div class="pay-sub-box" onclick="selectMethod('card', 'label-card')">
                                    <i class="fa-solid fa-credit-card"></i>
                                    <span>Pay by Card <small>(ATM / Debit / Credit)</small></span>
                                </div>
                            </div>
                            <div class="pay-sub-label" id="label-bkash">
                                <div class="pay-sub-box" onclick="selectMethod('bkash', 'label-bkash')">
                                    <span class="bkash-icon-small">b</span>
                                    <span>Pay by bKash</span>
                                </div>
                            </div>
                        </div>

                        <div class="pay-method-label" id="label-cod">
                            <div class="pay-method-box" onclick="selectMethod('cod', 'label-cod')">
                                <i class="fa-solid fa-money-bill-wave"></i>
                                <span>Cash on Delivery</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-checkout" id="proceedBtn" disabled>
                        <i class="fa-solid fa-arrow-right"></i> Proceed to Checkout
                    </button>
                </form>

                <a href="../../Controller/cartActionsController.php?action=clear"
                   class="btn-clear"
                   onclick="return confirm('Clear your entire cart?');">Clear Cart</a>

                <div class="checkout-trust">
                    <div class="trust-badge">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Secure<br>Payment</span>
                    </div>
                    <div class="trust-badge">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span>Easy<br>Returns</span>
                    </div>
                    <div class="trust-badge">
                        <i class="fa-solid fa-truck-fast"></i>
                        <span>Fast<br>Delivery</span>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </main>
</div>

<script>
var onlineOpen = false;

function clearAllSelections() {
    ['label-online','label-card','label-bkash','label-cod'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.classList.remove('selected');
    });
    document.getElementById('hiddenMethod').value = '';
    document.getElementById('proceedBtn').disabled = true;
}

function selectOnline() {
    onlineOpen = !onlineOpen;

    clearAllSelections();

    var subOptions = document.getElementById('onlineSubOptions');
    var chevron    = document.getElementById('chevronOnline');
    var labelOnline = document.getElementById('label-online');

    if (onlineOpen) {
        subOptions.style.display = 'flex';
        chevron.style.transform  = 'rotate(180deg)';
        labelOnline.classList.add('selected');
    } else {
        subOptions.style.display = 'none';
        chevron.style.transform  = 'rotate(0deg)';
    }
}

function selectMethod(method, labelId) {
    clearAllSelections();

    document.getElementById('hiddenMethod').value = method;
    document.getElementById('proceedBtn').disabled = false;

    if (method === 'card' || method === 'bkash') {
        onlineOpen = true;
        document.getElementById('onlineSubOptions').style.display = 'flex';
        document.getElementById('chevronOnline').style.transform  = 'rotate(180deg)';
        document.getElementById('label-online').classList.add('selected');
    } else {
        onlineOpen = false;
        document.getElementById('onlineSubOptions').style.display = 'none';
        document.getElementById('chevronOnline').style.transform  = 'rotate(0deg)';
    }

    document.getElementById(labelId).classList.add('selected');
}

document.getElementById('onlineSubOptions').style.display = 'none';
</script>
</body>
</html>