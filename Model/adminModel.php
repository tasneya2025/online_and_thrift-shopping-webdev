<?php
require_once(dirname(__FILE__) . "/db.php");

function getAdminStats() {
    $conn = get_db_connection();
    $stats = [];

    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM buyer"));
    $stats['total_buyers'] = (int)$r['c'];

    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM seller"));
    $stats['total_sellers'] = (int)$r['c'];

    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM products"));
    $stats['total_products'] = (int)$r['c'];

    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM orders WHERE status='paid'"));
    $stats['total_orders'] = (int)$r['c'];

    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(total_amount),0) AS rev FROM orders WHERE status='paid'"));
    $stats['total_revenue'] = (float)$r['rev'];

    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM orders WHERE status='pending'"));
    $stats['pending_orders'] = (int)$r['c'];

    return $stats;
}

function getAllBuyers() {
    $conn = get_db_connection();
    $result = mysqli_query($conn,
        "SELECT id, fullname, username, email, gender, address,
                IF(is_banned IS NULL, 0, is_banned) AS is_banned
         FROM buyer ORDER BY id DESC"
    );
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) $rows[] = $row;
    return $rows;
}

function getAllSellers() {
    $conn = get_db_connection();
    $result = mysqli_query($conn,
        "SELECT s.id, s.fullname, s.username, s.email, s.gender, s.address,
                IF(s.is_banned IS NULL, 0, s.is_banned) AS is_banned,
                COUNT(p.id) AS product_count
         FROM seller s
         LEFT JOIN products p ON p.seller_email = s.email
         GROUP BY s.id ORDER BY s.id DESC"
    );
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) $rows[] = $row;
    return $rows;
}

function getAllProductsAdmin() {
    $conn = get_db_connection();
    $result = mysqli_query($conn,
        "SELECT p.*, s.username AS seller_name
         FROM products p
         LEFT JOIN seller s ON p.seller_email = s.email
         ORDER BY p.id DESC"
    );
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) $rows[] = $row;
    return $rows;
}

function getAllOrdersAdmin() {
    $conn = get_db_connection();
    $result = mysqli_query($conn,
        "SELECT o.*, b.username AS buyer_name
         FROM orders o
         LEFT JOIN buyer b ON o.buyer_email = b.email
         ORDER BY o.id DESC"
    );
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) $rows[] = $row;
    return $rows;
}

function ensureBanColumn() {
    $conn = get_db_connection();
    foreach (['buyer', 'seller'] as $table) {
        $check = mysqli_query($conn,
            "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='$table' AND COLUMN_NAME='is_banned'"
        );
        if ($check && mysqli_num_rows($check) === 0) {
            mysqli_query($conn, "ALTER TABLE $table ADD COLUMN is_banned TINYINT(1) NOT NULL DEFAULT 0");
        }
    }
}

function banUser($table, $id) {
    ensureBanColumn();
    $conn = get_db_connection();
    $id   = (int)$id;
    $table = ($table === 'seller') ? 'seller' : 'buyer';
    mysqli_query($conn, "UPDATE $table SET is_banned=1 WHERE id=$id");
    return mysqli_affected_rows($conn) > 0;
}

function unbanUser($table, $id) {
    ensureBanColumn();
    $conn = get_db_connection();
    $id   = (int)$id;
    $table = ($table === 'seller') ? 'seller' : 'buyer';
    mysqli_query($conn, "UPDATE $table SET is_banned=0 WHERE id=$id");
    return mysqli_affected_rows($conn) > 0;
}

function deleteUser($table, $id) {
    $conn = get_db_connection();
    $id   = (int)$id;
    $table = ($table === 'seller') ? 'seller' : 'buyer';
    mysqli_query($conn, "DELETE FROM $table WHERE id=$id");
    return mysqli_affected_rows($conn) > 0;
}

function deleteProductAdmin($id) {
    $conn = get_db_connection();
    $id   = (int)$id;
    mysqli_query($conn, "DELETE FROM products WHERE id=$id");
    return mysqli_affected_rows($conn) > 0;
}

function getMonthlyRevenue() {
    $conn = get_db_connection();
    $result = mysqli_query($conn,
        "SELECT DATE_FORMAT(created_at, '%b') AS month,
                DATE_FORMAT(created_at, '%Y-%m') AS month_key,
                COALESCE(SUM(total_amount),0) AS revenue
         FROM orders
         WHERE status='paid' AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
         GROUP BY month_key, month
         ORDER BY month_key ASC"
    );
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) $rows[] = $row;
    return $rows;
}
?>