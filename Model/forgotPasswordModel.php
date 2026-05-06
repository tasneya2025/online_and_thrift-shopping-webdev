<?php
require_once(dirname(__FILE__) . "/db.php");

function getUserByEmailAndRole($email, $role) {
    $conn  = get_db_connection();
    $table = ($role === 'seller') ? 'seller' : 'buyer';
    $stmt  = $conn->prepare("SELECT * FROM $table WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

function updatePasswordByEmail($email, $role, $newPassword) {
    $conn   = get_db_connection();
    $table  = ($role === 'seller') ? 'seller' : 'buyer';
    $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt   = $conn->prepare("UPDATE $table SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed, $email);
    return $stmt->execute();
}
?>