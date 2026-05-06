<?php
require_once(dirname(__FILE__) . "/db.php");

function addToCart($buyer_email, $product_id) 
{
    $conn  = get_db_connection();
    $email = mysqli_real_escape_string($conn, $buyer_email);
    $pid   = (int)$product_id;

    $stockRes = mysqli_query($conn, "SELECT stock FROM products WHERE id=$pid");
    $stockRow = mysqli_fetch_assoc($stockRes);
    $stock    = (int)($stockRow['stock'] ?? 0);

    $check = mysqli_query($conn, "SELECT id, quantity FROM cart WHERE buyer_email='$email' AND product_id=$pid");
    if ($check && mysqli_num_rows($check) > 0) {
        $row    = mysqli_fetch_assoc($check);
        $newQty = $row['quantity'] + 1;
        if ($newQty <= $stock) {
            mysqli_query($conn, "UPDATE cart SET quantity=$newQty WHERE id={$row['id']}");
        }
    } else {
        if ($stock > 0) {
            mysqli_query($conn, "INSERT INTO cart (buyer_email, product_id, quantity) VALUES ('$email', $pid, 1)");
        }
    }
}

function getCartItems($buyer_email) {
    $conn  = get_db_connection();
    $email = mysqli_real_escape_string($conn, $buyer_email);

    $sql = "SELECT c.id as cart_id, c.quantity,
                   p.id as product_id, p.name, p.price, p.image_path, p.stock,
                   p.seller_email,
                   s.username as seller_name
            FROM cart c
            JOIN products p ON c.product_id = p.id
            LEFT JOIN seller s ON p.seller_email = s.email
            WHERE c.buyer_email = '$email'
            ORDER BY c.id DESC";

    $result = mysqli_query($conn, $sql);
    $items  = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
    }
    return $items;
}

function updateCartQty($cart_id, $qty) {
    $conn = get_db_connection();
    $id   = (int)$cart_id;
    $qty  = (int)$qty;

    if ($qty <= 0) {
        mysqli_query($conn, "DELETE FROM cart WHERE id=$id");
        return;
    }

    $res   = mysqli_query($conn, "SELECT p.stock FROM cart c JOIN products p ON c.product_id = p.id WHERE c.id=$id");
    $row   = mysqli_fetch_assoc($res);
    $stock = (int)($row['stock'] ?? 0);
    if ($qty > $stock) $qty = $stock;

    mysqli_query($conn, "UPDATE cart SET quantity=$qty WHERE id=$id");
}

function removeFromCart($cart_id) {
    $conn = get_db_connection();
    $id   = (int)$cart_id;
    mysqli_query($conn, "DELETE FROM cart WHERE id=$id");
}

function clearCart($buyer_email) {
    $conn  = get_db_connection();
    $email = mysqli_real_escape_string($conn, $buyer_email);
    mysqli_query($conn, "DELETE FROM cart WHERE buyer_email='$email'");
}

function checkoutCart($buyer_email) {
    $conn  = get_db_connection();
    $email = mysqli_real_escape_string($conn, $buyer_email);

    $items = getCartItems($buyer_email);

    foreach ($items as $item) {
        $pid          = (int)$item['product_id'];
        $qty          = (int)$item['quantity'];
        $product_name = mysqli_real_escape_string($conn, $item['name']);
        $price        = (float)$item['price'];
        $seller_email = mysqli_real_escape_string($conn, $item['seller_email']);

        mysqli_query($conn, "UPDATE products SET stock = GREATEST(stock - $qty, 0) WHERE id=$pid");

        for ($i = 0; $i < $qty; $i++) {
            mysqli_query($conn,
                "INSERT INTO purchase_history
                    (product_name, buyer_email, seller_email, price, purchase_date, is_read)
                 VALUES
                    ('$product_name', '$email', '$seller_email', $price, NOW(), 0)"
            );
        }
    }

    mysqli_query($conn, "DELETE FROM cart WHERE buyer_email='$email'");
}
?>