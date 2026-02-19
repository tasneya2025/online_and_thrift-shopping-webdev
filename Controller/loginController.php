<?php
session_start();
require_once("../Model/UserModel.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hasErr = false;
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $usertype = $_POST['usertype'];
 

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['emailErr'] = "Please enter a valid email format!";
        $hasErr = true;
    }

    if (strlen($password) < 6) {
        $_SESSION['passErr'] = "Password must be at least 6 characters!";
        $hasErr = true;
    }    
  
    if (isset($_POST['remember'])) {
        setcookie("user_email", $email, time() + (86400 * 30), "/");
        setcookie("user_pass", $password, time() + (86400 * 30), "/");
        setcookie("user_role", $usertype, time() + (86400 * 30), "/");
    } else {
     
        setcookie("user_email", "", time() - 3600, "/");
        setcookie("user_pass", "", time() - 3600, "/");
        setcookie("user_role", "", time() - 3600, "/");
    }

    if ($hasErr) {
        header("Location: ../View/login/login.php");
        exit();
    } else {
    
        $roleMap = ["seller" => 1, "buyer" => 2, "admin" => 3];
        $roleId = isset($roleMap[$usertype]) ? $roleMap[$usertype] : 0;

        $user = authuser($email, $password, $roleId);

        if ($user) {
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];

        
            if ($user['role'] == 1) {
                header("Location: ../View/seller/dashboard.php");
            } elseif ($user['role'] == 2) {
                header("Location: ../View/buyer/dashboard.php");
            } elseif ($user['role'] == 3) {
                header("Location: ../View/admin/dashboard.php");
            }
            exit();
        } else {
            $_SESSION['genErr'] = "Invalid credentials for the selected role.";
            header("Location: ../View/login/login.php");
            exit();
        }
    }
}
?>