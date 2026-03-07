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

        else {
            return "WRONG_PASSWORD";
        }

    }
    return "EMAIL_NOT_FOUND";

}


function getUserDataByEmail($email) {
    $conn = get_db_connection();
    $sql = "SELECT * FROM seller WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function updateUserInfo($email, $fullname, $address) {
    $conn = get_db_connection();
    $sql = "UPDATE seller SET fullname=?, address=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $fullname, $address, $email);
    return $stmt->execute();
}

function updatePasswordInDb($email, $hashed_password) {
    $conn = get_db_connection();
    $sql = "UPDATE seller SET password=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashed_password, $email);
    return $stmt->execute();
}


?>