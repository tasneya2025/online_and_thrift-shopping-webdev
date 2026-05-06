<?php
session_start();
require_once(dirname(__FILE__) . "/../Model/cartModel.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header("Location: ../View/login/login.php");
    exit();
}

$action = $_GET['action'] ?? '';

if ($action === 'remove' && isset($_GET['cart_id'])) {
    removeFromCart($_GET['cart_id']);
    header("Location: ../View/Buyer/buyerViewCart.php");

} elseif ($action === 'update' && isset($_GET['cart_id'], $_GET['qty'])) {
    updateCartQty($_GET['cart_id'], (int)$_GET['qty']);
    header("Location: ../View/Buyer/buyerViewCart.php");

} elseif ($action === 'clear') {
    clearCart($_SESSION['email']);
    header("Location: ../View/Buyer/buyerViewCart.php");

} elseif ($action === 'checkout') {
    checkoutCart($_SESSION['email']);
    $_SESSION['checkout_msg'] = "Your order has been placed! Your product will be sent to your location.";
    header("Location: ../View/Buyer/buyerDashboard.php");

} else {
    header("Location: ../View/Buyer/buyerViewCart.php");
}
exit();
?>