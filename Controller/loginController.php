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
  
    if ($hasErr) {
        header("Location: ../View/login/login.php");
        exit();
    } 
    
    else {
        $roleMap = ["seller" => 1, "buyer" => 2, "admin" => 3];
        $roleId = isset($roleMap[$usertype]) ? $roleMap[$usertype] : 0;

        $user = authuser($email, $password, $roleId);


        if (is_array($user)) {
       
            $_SESSION['name'] = $user['username']; 
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];

       
            if (isset($_POST['remember'])) {
                setcookie("user_email", $email, time() + (86400 * 30), "/");
                setcookie("user_pass", $password, time() + (86400 * 30), "/");
                setcookie("user_role", $usertype, time() + (86400 * 30), "/");
            }

        
            $roleName = ucfirst($usertype); 
            $_SESSION['welcome_msg'] = "Welcome back, $roleName " . $user['username'] . "!";
        

            if ($user['role'] == 1) {
                header("Location: ../View/Seller/sellerDashboard.php");
            } elseif ($user['role'] == 2) {
                header("Location: ../View/buyer/dashboard.php");
            } elseif ($user['role'] == 3) {
                header("Location: ../View/admin/dashboard.php");
            }
            exit();
        } 
        elseif ($user === "WRONG_PASSWORD") {

            $_SESSION['passErr'] = "The password you entered is incorrect.";
            header("Location: ../View/login/login.php");
            exit();
        } 
        elseif ($user === "EMAIL_NOT_FOUND") {
            
            $_SESSION['emailErr'] = "This email is not registered for the selected role.";
            header("Location: ../View/login/login.php");
            exit();
        }
        
            $roleName = ucfirst($usertype); 
            $_SESSION['welcome_msg'] = "Welcome back, $roleName " . $user['username'] . "!";
        
            if ($user['role'] == 1) {
                header("Location: ../View/Seller/sellerDashboard.php");
            } elseif ($user['role'] == 2) {
                header("Location: ../View/buyer/dashboard.php");
            } elseif ($user['role'] == 3) {
                header("Location: ../View/admin/dashboard.php");
            }
            exit();
        } 
        
}
?>