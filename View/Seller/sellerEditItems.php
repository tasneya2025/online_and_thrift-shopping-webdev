<?php
session_start();

require_once("../../Model/productModel.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: ../../View/login/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location:sellerDashboard.php");
    exit();
}

$id = $_GET['id'];
$conn = get_db_connection();
$sql = "SELECT * FROM products WHERE id = '$id'";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    die("Product not found.");
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shoppon |Edit Items</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/editItems.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="edit-container">
        <h2>Edit Item</h2>
        <p>Update details for "<?php echo $product['name']; ?>"</p>

        <form action="../../Controller/editItemController.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

            <label>Product Name *</label>
            <input type="text" name="product_name" value="<?php echo $product['name']; ?>" required>

            <label>Category *</label><br>
            <input type="radio" name="category" value="Women" <?php if($product['category'] == 'Women') echo 'checked'; ?>> Women
            <input type="radio" name="category" value="Men" <?php if($product['category'] == 'Men') echo 'checked'; ?>> Men
            <input type="radio" name="category" value="Children" <?php if($product['category'] == 'Children') echo 'checked'; ?>> Children

            <br><label>Price ($) *</label>
            <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>

            <label>Stock Quantity *</label>
            <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required>

            <label>Current Image</label><br>
            <img src="../uploads/<?php echo $product['image_path']; ?>" width="100">
            <br><label>Change Product Image (optional)</label>
            <input type="file" name="product_image">

            <label>Description</label>
            <textarea name="description"><?php echo $product['description']; ?></textarea>

            <button type="submit" class="btn-update">Update Item</button>
            <a href="sellerDashboard.php" class="btn-cancel">Cancel</a>
        </form>
    </div>     
    </div>
    
</body>
</html>