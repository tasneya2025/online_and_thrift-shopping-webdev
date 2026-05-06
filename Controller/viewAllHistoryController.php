<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header("Location: ../View/login/login.php");
    exit();
}

header("Location: ../View/Buyer/buyerHistory.php");
exit();
?>