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
    $sql = "SELECT username, email, address, password FROM seller WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc(); 
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


function getNotificationStatus($email) {
    $conn = get_db_connection();
    $sql = "SELECT is_enabled FROM notification_settings WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result) return $result['is_enabled'];

    // default ON if not set
    $sql = "INSERT INTO notification_settings (email, is_enabled) VALUES (?,1)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    return 1;
}

function updateNotificationStatus($email, $status) {
    $conn = get_db_connection();
    $sql = "UPDATE notification_settings SET is_enabled=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $status, $email);
    return $stmt->execute();
}

function getRecentHistory($seller_email) {
    $conn = get_db_connection();
    $sql = "SELECT * FROM purchase_history WHERE seller_email='$seller_email' ORDER BY id DESC LIMIT 5";
    return $conn->query($sql);
}

function getAllHistory($email) {
    $conn = get_db_connection();
    $sql = "SELECT * FROM purchase_history WHERE seller_email=? ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    return $stmt->get_result();
}


?>