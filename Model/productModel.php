<?php
require_once("db.php");
function insert_item($name, $category, $type, $fabric, $price, $sizes, $colors, $stock, $description, $image_name, $seller_email)
{
    $conn = get_db_connection();
    $query = "INSERT INTO products (name, category, product_type, fabric, price, sizes, colors, stock, description, image_path, seller_email) 
              VALUES ('$name', '$category', '$type', '$fabric', '$price', '$sizes', '$colors', '$stock', '$description', '$image_name', '$seller_email')";

    mysqli_query($conn, $query);
    
    return mysqli_affected_rows($conn) > 0;
}

function getSellerProducts($email) {
    $conn = get_db_connection();
    $email = mysqli_real_escape_string($conn, $email);
    $sql = "SELECT * FROM products WHERE seller_email = '$email' ORDER BY id DESC";
    
    $result = mysqli_query($conn, $sql);
    $products = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
    }
    return $products;
}

function getAllProducts() {
    $conn = get_db_connection();
    
    $sql = "SELECT * FROM products ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);

    $products = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
    }

    return $products;
}

?>