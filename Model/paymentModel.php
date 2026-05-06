<?php
require_once(dirname(__FILE__) . "/db.php");
require_once(dirname(__FILE__) . "/cartModel.php");

function createOrder($buyer_email, $payment_method, $total) {
    $conn   = get_db_connection();
    $email  = mysqli_real_escape_string($conn, $buyer_email);
    $method = mysqli_real_escape_string($conn, $payment_method);
    $total  = (float)$total;

    mysqli_query($conn,
        "CREATE TABLE IF NOT EXISTS orders (
            id              INT AUTO_INCREMENT PRIMARY KEY,
            buyer_email     VARCHAR(255) NOT NULL,
            payment_method  VARCHAR(20)  NOT NULL,
            total_amount    DECIMAL(10,2) NOT NULL,
            status          VARCHAR(30)  NOT NULL DEFAULT 'pending',
            created_at      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
        )"
    );

    mysqli_query($conn,
        "INSERT INTO orders (buyer_email, payment_method, total_amount, status, created_at)
         VALUES ('$email', '$method', $total, 'pending', NOW())"
    );

    return (int)mysqli_insert_id($conn);
}

function confirmPayment($order_id, $buyer_email) {
    $conn     = get_db_connection();
    $order_id = (int)$order_id;

    mysqli_query($conn, "UPDATE orders SET status='paid' WHERE id=$order_id");

    checkoutCart($buyer_email, $order_id);
}

function getOrder($order_id) {
    $conn     = get_db_connection();
    $order_id = (int)$order_id;
    $result   = mysqli_query($conn, "SELECT * FROM orders WHERE id=$order_id");
    return $result ? mysqli_fetch_assoc($result) : null;
}
?>