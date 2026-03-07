<?php
session_start();
require_once("../Model/UserModel.php");


if (isset($_POST['update_profile'])) {
    if (updateUserInfo($_SESSION['email'], $_POST['fullname'], $_POST['address'])) {
        $_SESSION['name'] = $_POST['fullname'];
        $_SESSION['address'] = $_POST['address'];
        header("Location: ../View/Seller/sellerSettings.php?success=1");
    }
}


if (isset($_POST['update_pass'])) {
    if ($_POST['new_password'] !== $_POST['confirm_password']) {
        header("Location: ../View/Seller/sellerSettings.php?error=mismatch");
        exit();
    }
    
    $user = getUserDataByEmail($_SESSION['email']);
    if (password_verify($_POST['current_password'], $user['password'])) {
        $hashed = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        updatePasswordInDb($_SESSION['email'], $hashed);
        header("Location: ../View/Seller/sellerSettings.php?success=pass_updated");
    } else {
        header("Location: ../View/Seller/sellerSettings.php?error=wrong_pass");
    }
}
?>