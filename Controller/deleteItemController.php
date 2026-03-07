<?php
session_start();
require_once("../Model/productModel.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn = get_db_connection();
    $query = "SELECT image_path FROM products WHERE id = '$id'";
    $res = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($res);
    if ($row) {
        unlink("../view/uploads/" . $row['image_path']);
    }

    $sql = "DELETE FROM products WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Post deleted successfully!";
    } else {
        $_SESSION['dbErr'] = "Failed to delete post.";
    }
}

header("Location: ../View/Seller/sellerDashboard.php");
exit();
?>