<?php
require_once("db.php");

function authuser($email, $password, $roleId) {
    $conn  = get_db_connection();
    $table = ($roleId == 1) ? "seller" : "buyer";
    $res   = mysqli_query($conn, "SELECT * FROM $table WHERE email='$email'");
    if ($res && mysqli_num_rows($res) > 0) {
        $user = mysqli_fetch_assoc($res);
        return password_verify($password, $user['password']) ? $user : "WRONG_PASSWORD";
    }
    return "EMAIL_NOT_FOUND";
}

/* USER DATA */
function getUserDataByEmail($email) {
    $conn = get_db_connection();
    $stmt = $conn->prepare("SELECT username, email, address, password FROM seller WHERE email=?");
    $stmt->bind_param("s", $email); $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getBuyerDataByEmail($email) {
    $conn = get_db_connection();
    $stmt = $conn->prepare("SELECT username, email, address, password FROM buyer WHERE email=?");
    $stmt->bind_param("s", $email); $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function updateUserInfo($email, $username, $address) {
    $conn = get_db_connection();
    $stmt = $conn->prepare("UPDATE seller SET username=?, address=? WHERE email=?");
    $stmt->bind_param("sss", $username, $address, $email);
    return $stmt->execute();
}

function updateBuyerInfo($email, $username, $address) {
    $conn = get_db_connection();
    $stmt = $conn->prepare("UPDATE buyer SET username=?, address=? WHERE email=?");
    $stmt->bind_param("sss", $username, $address, $email);
    return $stmt->execute();
}

function updatePasswordInDb($email, $hashed_password) {
    $conn = get_db_connection();
    $stmt = $conn->prepare("UPDATE seller SET password=? WHERE email=?");
    $stmt->bind_param("ss", $hashed_password, $email);
    return $stmt->execute();
}

function updateBuyerPasswordInDb($email, $hashed_password) {
    $conn = get_db_connection();
    $stmt = $conn->prepare("UPDATE buyer SET password=? WHERE email=?");
    $stmt->bind_param("ss", $hashed_password, $email);
    return $stmt->execute();
}

/* NOTIFICATION TOGGLE */
function getNotificationStatus($email) {
    $conn = get_db_connection();
    $stmt = $conn->prepare("SELECT is_enabled FROM notification_settings WHERE email=?");
    $stmt->bind_param("s", $email); $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row) return (int)$row['is_enabled'];
    $ins = $conn->prepare("INSERT IGNORE INTO notification_settings (email, is_enabled) VALUES (?, 1)");
    $ins->bind_param("s", $email); $ins->execute();
    return 1;
}

function updateNotificationStatus($email, $status) {
    $conn = get_db_connection();
    $stmt = $conn->prepare("UPDATE notification_settings SET is_enabled=? WHERE email=?");
    $stmt->bind_param("is", $status, $email);
    return $stmt->execute();
}

/* PURCHASE HISTORY */
function getRecentHistory($seller_email) {
    $conn = get_db_connection();
    $stmt = $conn->prepare(
        "SELECT product_name, buyer_email, price, purchase_date
         FROM purchase_history WHERE seller_email=? ORDER BY id DESC LIMIT 5"
    );
    $stmt->bind_param("s", $seller_email); $stmt->execute();
    return $stmt->get_result();
}

function getAllHistory($email) {
    $conn = get_db_connection();
    $stmt = $conn->prepare(
        "SELECT product_name, buyer_email, price, purchase_date
         FROM purchase_history WHERE seller_email=? ORDER BY id DESC"
    );
    $stmt->bind_param("s", $email); $stmt->execute();
    return $stmt->get_result();
}

/* NOTIFICATION FEED (settings page) */
function _ensureIsReadColumn() {
    $conn     = get_db_connection();
    $colCheck = $conn->query(
        "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
         WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='purchase_history' AND COLUMN_NAME='is_read'"
    );
    if ($colCheck && $colCheck->num_rows === 0) {
        $conn->query("ALTER TABLE purchase_history ADD COLUMN is_read TINYINT(1) NOT NULL DEFAULT 0");
    }
}

function getSellerNotifications($seller_email, $limit = 20) {
    _ensureIsReadColumn();
    $conn  = get_db_connection();
    $limit = (int)$limit;
    $stmt  = $conn->prepare(
        "SELECT id, product_name, buyer_email, price, purchase_date, is_read
         FROM purchase_history WHERE seller_email=? ORDER BY id DESC LIMIT $limit"
    );
    $stmt->bind_param("s", $seller_email); $stmt->execute();
    $rows = [];
    $res  = $stmt->get_result();
    while ($row = $res->fetch_assoc()) $rows[] = $row;
    return $rows;
}

function countUnreadNotifications($seller_email) {
    $conn     = get_db_connection();
    $colCheck = $conn->query(
        "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
         WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='purchase_history' AND COLUMN_NAME='is_read'"
    );
    if (!$colCheck || $colCheck->num_rows === 0) return 0;
    $stmt = $conn->prepare(
        "SELECT COUNT(*) AS cnt FROM purchase_history WHERE seller_email=? AND is_read=0"
    );
    $stmt->bind_param("s", $seller_email); $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return (int)($row['cnt'] ?? 0);
}

function markAllNotificationsRead($seller_email) {
    $conn = get_db_connection();
    $stmt = $conn->prepare(
        "UPDATE purchase_history SET is_read=1 WHERE seller_email=? AND is_read=0"
    );
    $stmt->bind_param("s", $seller_email);
    return $stmt->execute();
}
?>