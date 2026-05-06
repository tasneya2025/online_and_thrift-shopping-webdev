<?php
session_start();
require_once(dirname(__FILE__) . "/../Model/paymentModel.php");
require_once(dirname(__FILE__) . "/../Model/cartModel.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header("Location: ../View/login/login.php");
    exit();
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'select_method') {
    $method = $_POST['payment_method'] ?? '';

    if (!in_array($method, ['card', 'bkash', 'cod'])) {
        $_SESSION['cart_msg'] = "Please select a valid payment method.";
        header("Location: ../View/Buyer/buyerViewCart.php");
        exit();
    }

    $cartItems = getCartItems($_SESSION['email']);
    if (empty($cartItems)) {
        $_SESSION['cart_msg'] = "Your cart is empty.";
        header("Location: ../View/Buyer/buyerViewCart.php");
        exit();
    }

    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $shipping = 4.99;
    $tax      = round($subtotal * 0.08, 2);
    $total    = round($subtotal + $shipping + $tax, 2);

    $order_id = createOrder($_SESSION['email'], $method, $total);
    $_SESSION['pending_order_id'] = $order_id;

    if ($method === 'cod') {
        confirmPayment($order_id, $_SESSION['email']);
        unset($_SESSION['pending_order_id']);
        header("Location: ../View/Buyer/orderConfirmation.php?order_id=$order_id&method=cod");
    } elseif ($method === 'card') {
        header("Location: ../View/Buyer/cardPayment.php?order_id=$order_id");
    } elseif ($method === 'bkash') {
        header("Location: ../View/Buyer/bkashPayment.php?order_id=$order_id");
    }
    exit();
}

if ($action === 'process_card') {
    $order_id   = (int)($_POST['order_id'] ?? 0);
    $cardNumber = preg_replace('/\s+/', '', $_POST['card_number'] ?? '');
    $cardName   = trim($_POST['card_name'] ?? '');
    $expiry     = trim($_POST['expiry'] ?? '');
    $cvv        = trim($_POST['cvv'] ?? '');

    $errors = [];
    if (strlen($cardNumber) < 13 || strlen($cardNumber) > 19 || !ctype_digit($cardNumber)) {
        $errors[] = "Invalid card number.";
    }
    if (empty($cardName)) {
        $errors[] = "Cardholder name is required.";
    }
    if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiry)) {
        $errors[] = "Expiry must be MM/YY format.";
    }
    if (!preg_match('/^\d{3,4}$/', $cvv)) {
        $errors[] = "CVV must be 3 or 4 digits.";
    }
    if ($order_id <= 0) {
        $errors[] = "Invalid order.";
    }

    if (!empty($errors)) {
        $_SESSION['card_errors'] = $errors;
        header("Location: ../View/Buyer/cardPayment.php?order_id=$order_id");
        exit();
    }

    confirmPayment($order_id, $_SESSION['email']);
    unset($_SESSION['pending_order_id']);
    unset($_SESSION['card_errors']);
    header("Location: ../View/Buyer/orderConfirmation.php?order_id=$order_id&method=card");
    exit();
}

if ($action === 'process_bkash') {
    $order_id      = (int)($_POST['order_id'] ?? 0);
    $bkashNumber   = preg_replace('/\s+/', '', $_POST['bkash_number'] ?? '');
    $transactionId = trim($_POST['transaction_id'] ?? '');

    $errors = [];
    if (!preg_match('/^01[3-9]\d{8}$/', $bkashNumber)) {
        $errors[] = "Enter a valid bKash number (e.g. 01XXXXXXXXX).";
    }
    if (strlen($transactionId) < 6) {
        $errors[] = "Transaction ID must be at least 6 characters.";
    }
    if ($order_id <= 0) {
        $errors[] = "Invalid order.";
    }

    if (!empty($errors)) {
        $_SESSION['bkash_errors'] = $errors;
        header("Location: ../View/Buyer/bkashPayment.php?order_id=$order_id");
        exit();
    }

    confirmPayment($order_id, $_SESSION['email']);
    unset($_SESSION['pending_order_id']);
    unset($_SESSION['bkash_errors']);
    header("Location: ../View/Buyer/orderConfirmation.php?order_id=$order_id&method=bkash");
    exit();
}

header("Location: ../View/Buyer/buyerViewCart.php");
exit();
?>