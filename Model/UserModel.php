<?php
require_once("db.php");

function authuser($email, $password, $roleId) {
    $conn = get_db_connection();
    

    $sql = "SELECT * FROM seller WHERE email='$email' AND role='$roleId'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    
        if (password_verify($password, $user['password'])) {
            return $user; 
        }
    }
    return false;
}
?>