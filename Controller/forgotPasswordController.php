<?php
session_start();
require_once(dirname(__FILE__) . "/../Model/forgotPasswordModel.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../View/login/forgotPassword.php");
    exit();
}

$step = $_POST['step'] ?? '';

if ($step === 'verify_email') {
    $email = trim($_POST['email'] ?? '');
    $role  = $_POST['role'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['fp_error'] = "Please enter a valid email address.";
        header("Location: ../View/login/forgotPassword.php");
        exit();
    }

    if (!in_array($role, ['buyer', 'seller'])) {
        $_SESSION['fp_error'] = "Please select a valid role.";
        header("Location: ../View/login/forgotPassword.php");
        exit();
    }

    $user = getUserByEmailAndRole($email, $role);

    if (!$user) {
        $_SESSION['fp_error'] = "No account found with this email for the selected role.";
        header("Location: ../View/login/forgotPassword.php");
        exit();
    }

    if (empty($user['secret_answer'])) {
        $_SESSION['fp_error'] = "This account has no security answer set. Please contact support.";
        header("Location: ../View/login/forgotPassword.php");
        exit();
    }

    $_SESSION['fp_email'] = $email;
    $_SESSION['fp_role']  = $role;
    $_SESSION['fp_step']  = 'answer';

    header("Location: ../View/login/forgotPassword.php");
    exit();
}

if ($step === 'verify_answer') {
    if (!isset($_SESSION['fp_email'], $_SESSION['fp_role'])) {
        header("Location: ../View/login/forgotPassword.php");
        exit();
    }

    $answer = strtolower(trim($_POST['secret_answer'] ?? ''));

    if (empty($answer)) {
        $_SESSION['fp_error'] = "Please enter your answer.";
        header("Location: ../View/login/forgotPassword.php");
        exit();
    }

    if (!preg_match('/^[a-zA-Z]+$/', $answer)) {
        $_SESSION['fp_error'] = "Answer must contain alphabets only.";
        header("Location: ../View/login/forgotPassword.php");
        exit();
    }

    $user = getUserByEmailAndRole($_SESSION['fp_email'], $_SESSION['fp_role']);

    if (!$user || strtolower(trim($user['secret_answer'])) !== $answer) {
        $_SESSION['fp_error'] = "Incorrect answer. Please try again.";
        header("Location: ../View/login/forgotPassword.php");
        exit();
    }

    $_SESSION['fp_step'] = 'reset';
    header("Location: ../View/login/forgotPassword.php");
    exit();
}

if ($step === 'reset_password') {
    if (!isset($_SESSION['fp_email'], $_SESSION['fp_role'], $_SESSION['fp_step']) || $_SESSION['fp_step'] !== 'reset') {
        header("Location: ../View/login/forgotPassword.php");
        exit();
    }

    $newPassword     = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (strlen($newPassword) < 6) {
        $_SESSION['fp_error'] = "Password must be at least 6 characters.";
        header("Location: ../View/login/forgotPassword.php");
        exit();
    }

    if ($newPassword !== $confirmPassword) {
        $_SESSION['fp_error'] = "Passwords do not match.";
        header("Location: ../View/login/forgotPassword.php");
        exit();
    }

    $done = updatePasswordByEmail($_SESSION['fp_email'], $_SESSION['fp_role'], $newPassword);

    unset($_SESSION['fp_email'], $_SESSION['fp_role'], $_SESSION['fp_step']);

    if ($done) {
        $_SESSION['fp_success'] = "Password reset successfully! You can now log in.";
    } else {
        $_SESSION['fp_error'] = "Something went wrong. Please try again.";
    }

    header("Location: ../View/login/forgotPassword.php");
    exit();
}

header("Location: ../View/login/forgotPassword.php");
exit();
?>