<?php
session_start();

require_once("../../Model/productModel.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: ../../View/login/login.php");
    exit();
}

$seller_email = $_SESSION['email'];
$productList = getSellerProducts($seller_email);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shoppon | Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/sellerDashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="logo-section">
                <div class="logo-box"><i class="fa-solid fa-bag-shopping"></i></div>
                <div>
                    <h2>Shoppon</h2>
                    <p>Seller Center</p>
                </div>
            </div>

            <nav class="side-nav">
                <a href="sellerDashboard.php" class="active"><i class="fa-solid fa-house"></i> Dashboard</a>
                <a href="sellerPostItems.php"><i class="fa-solid fa-plus"></i> Post Item</a>
            </nav>

            <div class="bottom-section">
                <div class="account-info">
                    <p>Seller Account</p>
                    <strong><?php echo isset($_SESSION['name']) ? $_SESSION['name'] : "Guest Seller"; ?></strong>
                    <br>
                    <small><?php echo isset($_SESSION['email']) ? $_SESSION['email'] : "seller@shoppon.com"; ?></small>
            </div>

            <a href="../../Controller/logoutController.php" class="logout-link">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
            </div>
        </aside>
        <main class="main-content">
            <header class="content-header">
                <h1>Dashboard</h1>
                <p>Overview of your store performance</p>
            </header>

            <section class="stats-grid">
                <div class="stat-card">
                    <div class="stat-info">
                        <p>Total Posts</p>
                        <h3><?php echo count($productList); ?></h3>
                    </div>
                    <div class="stat-icon blue"><i class="fa-solid fa-box"></i></div>
                </div>
            </section>

            <section class="product-grid">
                <?php foreach($productList as $product): ?>
                <div class="product-card">
                    <div class="image-container">
                        <img src="../uploads/<?php echo $product['image_path']; ?>" alt="Product">
                        <span class="category-tag"><?php echo $product['category']; ?></span>
                    </div>
                    <div class="product-details">
                        <h4><?php echo $product['name']; ?></h4>
                        <p>Fabric: <?php echo $product['fabric']; ?></p>
                        <div class="price-row">
                            <span class="price">$<?php echo $product['price']; ?></span>
                            <span class="sold-count">0 sold</span>
                        </div>
                        <div class="card-actions">
                            <a href="sellerEditItems.php?id=<?php echo $product['id']; ?>" class="btn-edit">Edit</a>
                            
                            <a href="../../Controller/deleteItemController.php?id=<?php echo $product['id']; ?>" 
                            class="btn-delete" 
                            onclick="return confirm('Are you sure you want to delete this post? This action cannot be undone.');">
                            Delete
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </section>
        </main>

        
    </div>
    
</body>
</html>