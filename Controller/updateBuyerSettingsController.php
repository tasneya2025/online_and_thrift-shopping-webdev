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

        if (updateBuyerInfo($email, $username, $address)) {
            $_SESSION['name'] = $username;
            $_SESSION['address'] = $address;
            header("Location: ../View/Buyer/buyerSettings.php?success=Profile updated");
        } else {
            header("Location: ../View/Buyer/buyerSettings.php?error=Update failed");
        }
        exit();
    }

    if (isset($_POST['update_pass'])) {
        $current = $_POST['current_password'];
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];

        $user = getBuyerDataByEmail($email);

        if ($user) {
            $db_pass = $user['password'];
            $is_valid = password_verify($current, $db_pass);

            if ($is_valid) {
                if ($new === $confirm) {
                    $hashed = password_hash($new, PASSWORD_DEFAULT);
                    updateBuyerPasswordInDb($email, $hashed);
                    header("Location: ../View/Buyer/buyerSettings.php?success=Password updated");
                } else {
                    header("Location: ../View/Buyer/buyerSettings.php?error=New passwords do not match");
                }
            } else {
                header("Location: ../View/Buyer/buyerSettings.php?error=Wrong current password");
            }
        } else {
            header("Location: ../View/Buyer/buyerSettings.php?error=User record not found");
        }
        exit();
    }

}
?>