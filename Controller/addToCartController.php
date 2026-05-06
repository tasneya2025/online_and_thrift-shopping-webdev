<?php
session_start();
require_once(dirname(__FILE__) . "/../Model/cartModel.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header("Location: ../View/login/login.php");
    exit();
}

if (isset($_GET['id'])) {
    addToCart($_SESSION['email'], $_GET['id']);
    $_SESSION['cart_msg'] = "Item added to cart!";
}

if (isset($_GET['redirect']) && $_GET['redirect'] === 'cart') {
    header("Location: ../View/Buyer/buyerViewCart.php");
} else {
    header("Location: ../View/Buyer/buyerDashboard.php");
}
exit();
?>