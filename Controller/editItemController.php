<?php
session_start();
require_once("../Model/productModel.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../View/Seller/sellerDashboard.php");
    exit();
}

$conn        = get_db_connection();
$id          = (int)$_POST['id'];
$name        = mysqli_real_escape_string($conn, trim($_POST['product_name']));
$category    = mysqli_real_escape_string($conn, trim($_POST['category']));
$fabric      = mysqli_real_escape_string($conn, trim($_POST['fabric']      ?? ''));
$price       = (float)$_POST['price'];
$sizes       = mysqli_real_escape_string($conn, trim($_POST['sizes']       ?? ''));
$colors      = mysqli_real_escape_string($conn, trim($_POST['colors']      ?? ''));
$stock       = (int)$_POST['stock'];
$description = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));

if ($_FILES['product_image']['size'] > 0) {
    $filename    = basename($_FILES['product_image']['name']);
    $target_path = "../view/uploads/" . $filename;
    move_uploaded_file($_FILES['product_image']['tmp_name'], $target_path);

    $sql = "UPDATE products
            SET name='$name', category='$category', fabric='$fabric',
                price='$price', sizes='$sizes', colors='$colors',
                stock='$stock', description='$description', image_path='$filename'
            WHERE id=$id";
} else {
    $sql = "UPDATE products
            SET name='$name', category='$category', fabric='$fabric',
                price='$price', sizes='$sizes', colors='$colors',
                stock='$stock', description='$description'
            WHERE id=$id";
}

if (mysqli_query($conn, $sql)) {
    $_SESSION['success'] = "Product updated successfully!";
    header("Location: ../View/Seller/sellerDashboard.php");
} else {
    echo "Error updating record: " . mysqli_error($conn);
}
exit();
?>