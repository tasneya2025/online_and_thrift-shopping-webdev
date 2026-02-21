<?php
session_start();
require_once("../Model/signUpModel.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $address = trim($_POST['address']);
    $isValid = true;


    if (!preg_match("/^[a-zA-Z\s]*$/", $fullname)) {
        $_SESSION['nameErr'] = "Full Name must only contain letters and spaces!";
        $isValid = false;
    }

    
    if (str_word_count($username) > 4) {
        $_SESSION['userErr'] = "Username cannot be more than 5 words!";
        $isValid = false;
    }

    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['emailErr'] = "Please enter a valid email address!";
        $isValid = false;
    }

    
    if (strlen($password) == 6) {
        $_SESSION['passErr'] = "Password must be at least 6 characters long!";
        $isValid = false;
    }

    
    if ($password !== $confirm_password) {
        $_SESSION['passErr'] = "Passwords do not match!";
        $isValid = false;
    }

    
    if (str_word_count($address) <= 4) {
        $_SESSION['addressErr'] = "Address must be at least 5 words!";
        $isValid = false;
    }

    
    if (!$isValid) {
        header("Location: ../View/login/signUp.php");
        exit();
    }

    
    $isCreated = insertUser($_POST['role'],$fullname, $username, $email, $_POST['gender'], $address, $password);

    if ($isCreated) {
        header("Location: ../View/login/login.php?success=1");
        exit();
    } else {
        $_SESSION['signErr'] = "Registration failed. Username or Email already exists.";
        header("Location: ../View/login/signUp.php");
        exit();
    }
}
?>