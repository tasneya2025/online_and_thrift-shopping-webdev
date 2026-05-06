<?php
session_start();
require_once("../Model/UserModel.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../View/Seller/sellerSettings.php");
    exit();
}

if (!isset($_SESSION['email'])) {
    header("Location: ../View/login/login.php");
    exit();
}

$email = $_SESSION['email'];

if (isset($_POST['update_profile'])) {
    $username = trim($_POST['username']);
    $address  = trim($_POST['address']);

    if (updateUserInfo($email, $username, $address)) {
        $_SESSION['name']    = $username;
        $_SESSION['address'] = $address;
        header("Location: ../View/Seller/sellerSettings.php?success=Profile updated");
    } else {
        header("Location: ../View/Seller/sellerSettings.php?error=Update failed");
    }
    exit();
}

if (isset($_POST['update_pass'])) {
    $current = $_POST['current_password'];
    $new     = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    $user    = getUserDataByEmail($email);

    if ($user) {
        if (password_verify($current, $user['password'])) {
            if ($new === $confirm) {
                updatePasswordInDb($email, password_hash($new, PASSWORD_DEFAULT));
                header("Location: ../View/Seller/sellerSettings.php?success=Password updated");
            } else {
                header("Location: ../View/Seller/sellerSettings.php?error=New passwords do not match");
            }
        } else {
            header("Location: ../View/Seller/sellerSettings.php?error=Wrong current password");
        }
    } else {
        header("Location: ../View/Seller/sellerSettings.php?error=User record not found");
    }
    exit();
}

if (isset($_POST['toggle_notification'])) {
    $status = (int)($_POST['notification'] ?? 0);
    updateNotificationStatus($email, $status);
    exit();
}

if (isset($_POST['mark_all_read'])) {
    markAllNotificationsRead($email);
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit();
}

header("Location: ../View/Seller/sellerSettings.php");
exit();
?>