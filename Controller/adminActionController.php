<?php
session_start();
require_once(dirname(__FILE__) . "/../Model/adminModel.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 3) {
    header("Location: ../View/login/login.php");
    exit();
}

$action = $_POST['action'] ?? '';
$table  = $_POST['table']  ?? '';
$id     = (int)($_POST['id'] ?? 0);

if ($action === 'ban') {
    banUser($table, $id);
    $_SESSION['admin_msg'] = "User banned successfully.";
} elseif ($action === 'unban') {
    unbanUser($table, $id);
    $_SESSION['admin_msg'] = "User unbanned successfully.";
} elseif ($action === 'delete_user') {
    deleteUser($table, $id);
    $_SESSION['admin_msg'] = "User deleted successfully.";
} elseif ($action === 'delete_product') {
    deleteProductAdmin($id);
    $_SESSION['admin_msg'] = "Product removed successfully.";
}

$redirect = $_POST['redirect'] ?? '../View/Admin/adminDashboard.php';
header("Location: $redirect");
exit();
?>