<?php
session_start();
require_once("../Model/UserModel.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_SESSION['email'])) {
        header("Location: ../View/login/login.php");
        exit();
    }
    $email = $_SESSION['email'];

    if (isset($_POST['update_profile'])) {
        $username = $_POST['username'];
        $address = $_POST['address'];

        if (updateUserInfo($email, $username, $address)) {
            $_SESSION['name'] = $username;
            $_SESSION['address'] = $address;
            header("Location: ../View/Seller/sellerSettings.php?success=Profile updated");
        } else {
            header("Location: ../View/Seller/sellerSettings.php?error=Update failed");
        }
        exit();
    }

    if (isset($_POST['update_pass'])) {
        $current = $_POST['current_password'];
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];

        $user = getUserDataByEmail($email);

        if ($user) {
            $db_pass = $user['password'];
            $is_valid = password_verify($current, $db_pass);

            if ($is_valid) {
                if ($new === $confirm) {
                    $hashed = password_hash($new, PASSWORD_DEFAULT);
                    updatePasswordInDb($email, $hashed);
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
    $status = isset($_POST['notification']) ? 1 : 0;
    updateNotificationStatus($email, $status);
    header("Location: ../View/Seller/sellerSettings.php?success=Notification updated");
    exit();
}
}
?>