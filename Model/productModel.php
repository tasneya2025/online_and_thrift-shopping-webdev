<?php
require_once("db.php");
function insert_item($name, $category, $type, $fabric, $price, $sizes, $colors, $stock, $description, $image_name, $seller_email, $item_condition, $used_duration, $condition_details, $defects)
{
    $conn = get_db_connection();
    
    $query = "INSERT INTO products (name, category, product_type, fabric, price, sizes, colors, stock, description, image_path, seller_email, item_condition, used_duration, condition_details, defects) 
              VALUES ('$name', '$category', '$type', '$fabric', '$price', '$sizes', '$colors', '$stock', '$description', '$image_name', '$seller_email', '$item_condition', '$used_duration', '$condition_details', '$defects')";

    mysqli_query($conn, $query);
    
    return mysqli_affected_rows($conn) > 0;
}

function getAllProducts() {
    $conn = get_db_connection(); 
    
    $sql = "SELECT * FROM products ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    $products = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $email = $row['seller_email']; 
        
        $user_sql = "SELECT username FROM seller WHERE email = '$email'";
        $user_result = mysqli_query($conn, $user_sql);
        
        if ($user_result && mysqli_num_rows($user_result) > 0) {
            $user_row = mysqli_fetch_assoc($user_result);
            $row['seller_username'] = $user_row['username'];
        } else {
            $row['seller_username'] = "Shoppon Seller";
        }
        
        $products[] = $row;
    }
    return $products;
}

function getSellerProducts($email) {
    $conn = get_db_connection();
    $email = mysqli_real_escape_string($conn, $email);
    
    $sql = "SELECT * FROM products WHERE seller_email = '$email' ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    $products = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $user_sql = "SELECT name FROM users WHERE email = '$email'";
        $user_result = mysqli_query($conn, $user_sql);
        $user_row = mysqli_fetch_assoc($user_result);
        
        $row['seller_username'] = $user_row['name'] ?? 'Shoppon Seller';
        $products[] = $row;
    }
    return $products;
}

function getAllThriftProducts() {
    $conn = get_db_connection(); //
    
    $sql = "SELECT * FROM thrift_products ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    $products = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $email = $row['buyer_email']; 
        
        $user_sql = "SELECT name FROM users WHERE email = '$email'";
        $user_result = mysqli_query($conn, $user_sql);
        
        if ($user_result && mysqli_num_rows($user_result) > 0) {
            $user_row = mysqli_fetch_assoc($user_result);
            $row['owner_username'] = $user_row['name'];
        } else {
            $row['owner_username'] = "Shoppon Member";
        }
        
        $products[] = $row;
    }
    return $products;
}

function insert_thrift_item($name, $cat, $condition, $fabric, $price, $sizes, $colors, $stock, $desc, $img, $email) {
    $conn = get_db_connection();
    
    $sql = "INSERT INTO thrift_products (name, category, item_condition, fabric, price, sizes, colors, stock, description, image_path, buyer_email) 
            VALUES ('$name', '$cat', '$condition', '$fabric', '$price', '$sizes', '$colors', '$stock', '$desc', '$img', '$email')";
            
    return mysqli_query($conn, $sql);
}


?>