<?php
session_start();

require_once("../../Model/productModel.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: ../../View/login/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: sellerDashboard.php");
    exit();
}

$id      = (int)$_GET['id'];
$conn    = get_db_connection();
$result  = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
$product = mysqli_fetch_assoc($result);

if (!$product) {
    die("Product not found.");
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shoppon | Edit Item</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/editItems.css">

</head>
<body>
<div class="dashboard-container">

    <!-- <aside class="sidebar">
        <div class="logo-section">
            <div class="logo-box"><i class="fa-solid fa-bag-shopping"></i></div>
            <div><h2>Shoppon</h2><p>Seller Center</p></div>
        </div>
        <nav class="side-nav">
            <a href="sellerDashboard.php" class="active"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="sellerPostItems.php"><i class="fa-solid fa-plus"></i> Post Item</a>
            <a href="sellerSettings.php"><i class="fa-solid fa-gear"></i> Settings</a>
        </nav>
        <div class="account-info">
            <p>Seller Account</p>
            <strong style="color:#3b71fe;"><?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?></strong><br>
            <small><?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></small>
        </div>
    </aside> -->

    <div class="edit-container">
        <h2>Edit Item</h2>
        <p>Update details for "<?php echo htmlspecialchars($product['name']); ?>"</p>

        <form action="../../Controller/editItemController.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

            <label>Product Name *</label>
            <input type="text" name="product_name"
                   value="<?php echo htmlspecialchars($product['name']); ?>" required>

            <label>Category *</label><br>
            <input type="radio" name="category" value="Women"
                   <?php echo $product['category'] == 'Women' ? 'checked' : ''; ?>> Women &nbsp;
            <input type="radio" name="category" value="Men"
                   <?php echo $product['category'] == 'Men' ? 'checked' : ''; ?>> Men &nbsp;
            <input type="radio" name="category" value="Children"
                   <?php echo $product['category'] == 'Children' ? 'checked' : ''; ?>> Children
            <br><br>

            <div class="field-row">
                <div class="field-group">
                    <label>Fabric / Material</label>
                    <input type="text" name="fabric"
                           value="<?php echo htmlspecialchars($product['fabric'] ?? ''); ?>"
                           placeholder="e.g. Pure Silk">
                </div>
                <div class="field-group">
                    <label>Price ($) *</label>
                    <input type="number" step="0.01" name="price"
                           value="<?php echo htmlspecialchars($product['price']); ?>" required>
                </div>
            </div><br>

            <div class="field-row">
                <div class="field-group">
                    <label>Available Sizes</label>
                    <input type="text" name="sizes"
                           value="<?php echo htmlspecialchars($product['sizes'] ?? ''); ?>"
                           placeholder="S, M, L, XL">
                </div>
                <div class="field-group">
                    <label>Available Colors</label>
                    <input type="text" name="colors"
                           value="<?php echo htmlspecialchars($product['colors'] ?? ''); ?>"
                           placeholder="Red, Blue, Black">
                </div>
            </div>

            <label>Stock Quantity *</label>
            <input type="number" name="stock"
                   value="<?php echo htmlspecialchars($product['stock']); ?>" required>

            <label>Current Image</label><br>
            <img src="../uploads/<?php echo htmlspecialchars($product['image_path']); ?>"
                 width="100" style="margin-bottom:10px;">
            <br>
            <label>Change Product Image (optional)</label>
            <input type="file" name="product_image">

            <label>Description</label>
            <textarea name="description"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>

            <button type="submit" class="btn-update">
                <i class="fa-solid fa-floppy-disk"></i> Update Item
            </button>
            <a href="sellerDashboard.php" class="btn-cancel">Cancel</a>
        </form>
    </div>
</div>
</body>
</html>