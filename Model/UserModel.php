<?php
require_once("db.php");

function authuser($role,$email, $password) {
    $conn = get_db_connection() ;  
    

    $email = mysqli_real_escape_string($conn, $email);
    $role = mysqli_real_escape_string($conn, $role);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password' AND role='$role'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return false;
}
?>