<?php
session_start();
require_once("../Model/productModel.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['product_name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    
    $conn = get_db_connection();

    if ($_FILES['product_image']['size'] > 0) {
        $filename = basename($_FILES['product_image']['name']);
        $target_path = "../view/uploads/" . $filename;
        move_uploaded_file($_FILES['product_image']['tmp_name'], $target_path);

        $sql = "UPDATE products SET name='$name', category='$category', price='$price', 
                stock='$stock', description='$description', image_path='$filename' 
                WHERE id='$id'";
    } else {
        
        $sql = "UPDATE products SET name='$name', category='$category', price='$price', 
                stock='$stock', description='$description' 
                WHERE id='$id'";
    }

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Product updated successfully!";
        header("Location: ../View/Seller/sellerDashboard.php");
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
    exit();
}
?>