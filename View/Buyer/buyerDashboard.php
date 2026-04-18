<?php
session_start();
require_once("../../Model/productModel.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header("Location: ../login/login.php");
    exit();
}

$products = getAllProducts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppon | Buyer Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/buyerDashboard.css">
</head>
<body>

<div class="dashboard-container">
    <aside class="sidebar">
        <div class="logo-section">
            <div class="logo-box"><i class="fa-solid fa-bag-shopping"></i></div>
            <div>
                <h2>Shoppon</h2>
                <p>Buyer Center</p>
            </div>
        </div>

        <nav class="side-nav">
            <a href="buyerDashboard.php" class="active"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="thriftShops.php"><i class="fa-solid fa-recycle"></i> Thrift Shops</a>
            <a href="viewCart.php"><i class="fa-solid fa-cart-shopping"></i> View Cart</a>
            
            <a href="postThriftItem.php"><i class="fa-solid fa-circle-plus"></i> Post Thrift Item</a>
            <a href="myThriftStore.php"><i class="fa-solid fa-shop"></i> My Thrift Store</a>
            
            <a href="buyerSettings.php"><i class="fa-solid fa-gear"></i> Settings</a>
        </nav>

        <div class="bottom-section">
            <div class="account-info">
                <p>Buyer Account</p>
                <strong style="color: #3b71fe;"><?php echo $_SESSION['name'] ?? "User"; ?></strong><br>
                <small><?php echo $_SESSION['email'] ?? "buyer@shoppon.com"; ?></small>
            </div>
            <a href="../../Controller/logoutController.php" class="logout-link">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </aside>

    <main class="main-content">
        <header class="content-header">
            <div class="header-flex">
                <div>
                    <h1>Discover</h1>
                    <p>Fresh picks from every Shoppon seller</p>
                </div>
                <div class="search-bar">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="productSearch" placeholder="Search products, sellers, or categories...">
                </div>
            </div>
        </header>

        <section class="product-grid" id="productGrid">
            <?php if (!empty($products)): ?>
                <?php foreach($products as $row): 
                    // FIXED: Changed 'quantity' to 'stock' to match your DB
                    $current_stock = $row['stock'] ?? 0; 
                    $isOutOfStock = ($current_stock <= 0);
                ?>
                <div class="product-card <?php echo $isOutOfStock ? 'oos-card' : ''; ?>">
                    <div class="image-container">
                        <img src="../uploads/<?php echo $row['image_path']; ?>" alt="Product">
                        <span class="category-tag"><?php echo htmlspecialchars($row['category']); ?></span>
                        
                        <?php if($isOutOfStock): ?>
                            <div class="oos-overlay">Sold Out</div>
                        <?php endif; ?>
                    </div>

                    <div class="product-details">
                        <h4 class="p-name"><?php echo htmlspecialchars($row['name']); ?></h4>
                        
                        <p class="seller-info">
                            <i class="fa-solid fa-circle-user"></i> Seller: <span><?php echo htmlspecialchars($row['seller_email']); ?></span>
                        </p>

                        <p class="stock-count <?php echo $isOutOfStock ? 'text-red' : 'text-green'; ?>">
                            <i class="fa-solid <?php echo $isOutOfStock ? 'fa-xmark' : 'fa-check'; ?>"></i>
                            <?php echo $isOutOfStock ? 'Out of Stock' : $current_stock . ' available'; ?>
                        </p>

                        <div class="price-row">
                            <span class="price">$<?php echo number_format($row['price'], 2); ?></span>
                        </div>
                        
                        <div class="button-group">
                            <button class="btn-buy" <?php echo $isOutOfStock ? 'disabled' : ''; ?>>
                                <?php echo $isOutOfStock ? 'Unavailable' : 'Buy Now'; ?>
                            </button>
                            
                            <a href="../../Controller/addToCartController.php?id=<?php echo $row['id']; ?>" 
                               class="btn-cart <?php echo $isOutOfStock ? 'btn-disabled' : ''; ?>">
                                <i class="fa-solid fa-cart-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-products"><p>No products found in database.</p></div>
            <?php endif; ?>
        </section>
    </main>
</div>

<script>
    document.getElementById('productSearch').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let cards = document.getElementsByClassName('product-card');
        for (let i = 0; i < cards.length; i++) {
            let name = cards[i].querySelector(".p-name").innerText;
            let seller = cards[i].querySelector(".seller-info span").innerText;
            let cat = cards[i].querySelector(".category-tag").innerText;
            if (name.toUpperCase().indexOf(filter) > -1 || seller.toUpperCase().indexOf(filter) > -1 || cat.toUpperCase().indexOf(filter) > -1) {
                cards[i].style.display = "";
            } else {
                cards[i].style.display = "none";
            }
        }
    });
</script>
</body>
</html>