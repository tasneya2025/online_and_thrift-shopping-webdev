<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: ../../View/login/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shoppon | Post Item</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/postItem.css">
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
                <a href="sellerDashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a>
                <a href="sellerPostItems.php" class="active"><i class="fa-solid fa-plus"></i> Post Item</a>
            </nav>
            <div class="account-info">
                <p>Seller Account</p>
                <strong><?php echo isset($_SESSION['name']) ? $_SESSION['name'] : "Guest Seller"; ?></strong>
                <br>
                <small style="font-size: 11px; color: #666;">
                    <?php echo isset($_SESSION['email']) ? $_SESSION['email'] : "seller@shoppon.com"; ?>
                </small>
            </div>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>Post New Item</h1>
                <p>Add a new product to your store</p>
            </div>

            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert success"><i class="fa-solid fa-circle-check"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <?php if(isset($_SESSION['dbErr'])): ?>
                <div class="alert error"><i class="fa-solid fa-circle-exclamation"></i> <?php echo $_SESSION['dbErr']; unset($_SESSION['dbErr']); ?></div>
            <?php endif; ?>

            <form action="../../Controller/postItemController.php" method="POST" enctype="multipart/form-data" class="post-card">
                
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="product_name" placeholder="e.g. Silk Midi Dress">
                    <?php if(isset($_SESSION['nameErr'])): ?>
                        <span class="err-msg"><?php echo $_SESSION['nameErr']; unset($_SESSION['nameErr']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <div class="radio-group">
                        <label><input type="radio" name="category" value="Women"> Women</label>
                        <label><input type="radio" name="category" value="Men"> Men</label>
                        <label><input type="radio" name="category" value="Children"> Children</label>
                    </div>
                    <?php if(isset($_SESSION['catErr'])): ?>
                        <span class="err-msg"><?php echo $_SESSION['catErr']; unset($_SESSION['catErr']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Product Type</label>
                    <select name="product_type" id="p-type">
                        <option value="">Select product type</option>
                        <option value="Clothing">Clothing</option>
                        <option value="Shoes">Shoes</option>
                        <option value="Accessories">Accessories</option>
                    </select>
                    <?php if(isset($_SESSION['typeErr'])): ?>
                        <span class="err-msg"><?php echo $_SESSION['typeErr']; unset($_SESSION['typeErr']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label>Fabric / Material</label>
                        <input type="text" name="fabric" placeholder="e.g. Pure Silk">
                    </div>
                    <div class="form-group">
                        <label>Price ($)</label>
                        <input type="number" name="price" step="0.01" placeholder="89.99">
                        <?php if(isset($_SESSION['priceErr'])): ?>
                            <span class="err-msg"><?php echo $_SESSION['priceErr']; unset($_SESSION['priceErr']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label>Available Sizes</label>
                        <input type="text" name="sizes" placeholder="S, M, L, XL">
                    </div>
                    <div class="form-group">
                        <label>Available Colors</label>
                        <input type="text" name="colors" placeholder="Red, Blue, Black">
                    </div>
                </div>

                <div class="form-group">
                    <label>Stock Quantity</label>
                    <input type="number" name="stock" placeholder="50">
                    <?php if(isset($_SESSION['stockErr'])): ?>
                        <span class="err-msg"><?php echo $_SESSION['stockErr']; unset($_SESSION['stockErr']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Product Image</label>
                    <div class="upload-container" id="uploadBtn">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <p id="file-text">Click to upload image</p>
                        <small>PNG, JPG or WEBP</small>
                        <input type="file" name="product_image" id="file-input" hidden>
                    </div>
                    <?php if(isset($_SESSION['imgErr'])): ?>
                        <span class="err-msg"><?php echo $_SESSION['imgErr']; unset($_SESSION['imgErr']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="desc" rows="3" placeholder="Describe your product..."></textarea>
                    <?php if(isset($_SESSION['descErr'])): ?>
                        <span class="err-msg"><?php echo $_SESSION['descErr']; unset($_SESSION['descErr']); ?></span>
                    <?php endif; ?>
                </div>

                <button type="submit" class="submit-btn">Post Item</button>
            </form>
        </main>
    </div>
    <script src="../js/postItem.js"></script>
</body>
</html>