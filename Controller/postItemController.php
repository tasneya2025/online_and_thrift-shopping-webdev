<?php
session_start();
require_once '../Model/productModel.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $hasErr = false;

    
    $productName = $_POST['product_name'];
    $category    = $_POST['category'] ?? '';
    $productType = $_POST['product_type'] ?? '';
    $fabric      = $_POST['fabric'];
    $price       = $_POST['price'];
    $sizes       = $_POST['sizes'];
    $colors      = $_POST['colors'];
    $stock       = $_POST['stock'];
    $description = $_POST['description'];


    if (empty($productName)) {
        $hasErr = true;
        $_SESSION['nameErr'] = "Please enter the product name!";
    } else {
        if (!preg_match("/^[a-zA-Z- ']*$/", $productName)) {
            $hasErr = true;
            $_SESSION['nameErr'] = "Only Letters and Spaces are allowed!";
        }
    }


    if (empty($category)) {
        $hasErr = true;
        $_SESSION['catErr'] = "Please select a category!";
    }

    
    if (empty($productType)) {
        $hasErr = true;
        $_SESSION['typeErr'] = "Please select a product type!";
    }

    
    if (empty($price)) {
        $hasErr = true;
        $_SESSION['priceErr'] = "Price is required!";
    } else {
        if (!is_numeric($price) || $price <= 0) {
            $hasErr = true;
            $_SESSION['priceErr'] = "Please enter a valid price!";
        }
    }

    
    if (empty($stock)) {
        $hasErr = true;
        $_SESSION['stockErr'] = "Please enter the stock quantity!";
    } else {
        if ($stock < 0) {
            $hasErr = true;
            $_SESSION['stockErr'] = "Stock cannot be negative!";
        }
    }

    
    if ($_FILES['product_image']['error'] == 4) {
        $_SESSION['imgErr'] = "Product image is required!";
        $hasErr = true;
    } else {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        if (!in_array($_FILES['product_image']['type'], $allowedTypes)) {
            $_SESSION['imgErr'] = "Only JPG, PNG, JPEG and WEBP files are allowed!";
            $hasErr = true;
        }

        $maxSize = 5 * 1024 * 1024;
        if ($_FILES['product_image']['size'] > $maxSize) {
            $_SESSION['imgErr'] = "File size must be less than 5MB!";
            $hasErr = true;
        }
    }


    if (empty($description)) {
        $hasErr = true;
        $_SESSION['descErr'] = "Please enter a description!";
    } else {
        if (str_word_count($description) < 3) {
            $hasErr = true;
            $_SESSION['descErr'] = "Description is too short!";
        }
    }

    
    if ($hasErr) {
        header("Location: ../View/Seller/sellerPostItems.php");
        exit();
    } 
    
    else {

    $filename = basename($_FILES['product_image']['name']); 
    $dir = "../view/uploads/";
    
  
    if(!is_dir($dir)){
        mkdir($dir, 0777, true);
    }

    $target_path = $dir . $filename; 


    if(move_uploaded_file($_FILES['product_image']['tmp_name'], $target_path)) {
        
        $seller_email = $_SESSION['email']; 
        
  
        $result = insert_item($productName, $category, $productType, $fabric, $price, $sizes, $colors, $stock, $description, $filename, $seller_email);
        
        if ($result) {
            $_SESSION['success'] = "Product posted successfully!";
            header("Location: ../View/Seller/sellerDashboard.php");
            exit();
        } else {
            $_SESSION['dbErr'] = "Database insertion failed.";
            header("Location: ../View/Seller/sellerPostItems.php");
            exit();
        }
    } else {
        $fileErr = "File upload failed";
    }
}
}

?>