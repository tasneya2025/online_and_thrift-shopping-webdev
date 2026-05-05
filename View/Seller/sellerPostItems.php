<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: ../../View/login/login.php");
    exit();
}

$old = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);

function old($key, $default = '') {
    global $old;
    return htmlspecialchars($old[$key] ?? $default);
}
function oldSelected($key, $value) {
    global $old;
    return isset($old[$key]) && $old[$key] === $value ? 'selected' : '';
}
function oldChecked($key, $value) {
    global $old;
    return isset($old[$key]) && $old[$key] === $value ? 'checked' : '';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shoppon | Post Item</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/postItem.css">
    <link rel="stylesheet" href="../css/logout.css">

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
                <a href="sellerSettings.php"><i class="fa-solid fa-gear"></i> Settings</a>
                <a href="sellerHistory.php"><i class="fa-regular fa-clock"></i>History</a>


            </nav>

            <div class="bottom-section">
                <div class="account-info">
                    <p>Seller Account</p>
                    <strong style="color: #3b71fe;"><?php echo isset($_SESSION['name']) ? $_SESSION['name'] : "Guest Seller"; ?></strong>
                    <br>
                    <small><?php echo isset($_SESSION['email']) ? $_SESSION['email'] : "seller@shoppon.com"; ?></small>
                </div>

                <a href="../../Controller/logoutController.php" class="logout-link">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
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
                    <input type="text" name="product_name" placeholder="e.g. Silk Midi Dress" value="<?php echo old('product_name'); ?>">
                    <?php if(isset($_SESSION['nameErr'])): ?>
                        <span class="err-msg"><?php echo $_SESSION['nameErr']; unset($_SESSION['nameErr']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <div class="radio-group">
                        <label><input type="radio" name="category" value="Women" <?php echo oldChecked('category','Women'); ?>> Women</label>
                        <label><input type="radio" name="category" value="Men" <?php echo oldChecked('category','Men'); ?>> Men</label>
                        <label><input type="radio" name="category" value="Children" <?php echo oldChecked('category','Children'); ?>> Children</label>
                    </div>
                    <?php if(isset($_SESSION['catErr'])): ?>
                        <span class="err-msg"><?php echo $_SESSION['catErr']; unset($_SESSION['catErr']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Product Type</label>
                    <select name="product_type" id="p-type">
                        <option value="">Select product type</option>
                        <option value="Clothing" <?php echo oldSelected('product_type','Clothing'); ?>>Clothing</option>
                        <option value="Shoes" <?php echo oldSelected('product_type','Shoes'); ?>>Shoes</option>
                        <option value="Accessories" <?php echo oldSelected('product_type','Accessories'); ?>>Accessories</option>
                    </select>
                    <?php if(isset($_SESSION['typeErr'])): ?>
                        <span class="err-msg"><?php echo $_SESSION['typeErr']; unset($_SESSION['typeErr']); ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Item Condition</label>
                    <select name="item_condition" id="condition">
                        <option value="">Select item condition</option>
                        <option value="new" <?php echo oldSelected('item_condition','new'); ?>>Brand New</option>
                        <option value="thrift" <?php echo oldSelected('item_condition','thrift'); ?>>Used (Thrift)</option>
                    </select>
                </div>

                <div id="thriftFields" style="display:<?php echo (($old['item_condition'] ?? '') === 'thrift') ? 'block' : 'none'; ?>;">
                    <div class="form-group">
                        <label>Used Duration</label>
                        <input type="text" name="used_duration" placeholder="e.g. 1 year" value="<?php echo old('used_duration'); ?>">
                    </div>

                    <div class="form-group">
                        <label>Condition Details</label>
                        <input type="text" name="condition_details" placeholder="e.g. Good / Slightly worn" value="<?php echo old('condition_details'); ?>">
                    </div>

                    <div class="form-group">
                        <label>Defects (optional)</label>
                        <input type="text" name="defects" placeholder="e.g. small stain" value="<?php echo old('defects'); ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label>Fabric / Material</label>
                        <input type="text" name="fabric" placeholder="e.g. Pure Silk" value="<?php echo old('fabric'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Price ($)</label>
                        <input type="number" name="price" step="0.01" placeholder="89.99" value="<?php echo old('price'); ?>">
                        <?php if(isset($_SESSION['priceErr'])): ?>
                            <span class="err-msg"><?php echo $_SESSION['priceErr']; unset($_SESSION['priceErr']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label>Available Sizes</label>
                        <input type="text" name="sizes" placeholder="S, M, L, XL" value="<?php echo old('sizes'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Available Colors</label>
                        <input type="text" name="colors" placeholder="Red, Blue, Black" value="<?php echo old('colors'); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Stock Quantity</label>
                    <input type="number" name="stock" placeholder="50" value="<?php echo old('stock'); ?>">
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
                    <textarea name="description" id="desc" rows="3" placeholder="Describe your product..."><?php echo old('description'); ?></textarea>
                    <?php if(isset($_SESSION['descErr'])): ?>
                        <span class="err-msg"><?php echo $_SESSION['descErr']; unset($_SESSION['descErr']); ?></span>
                    <?php endif; ?>
                </div>

                <button type="submit" class="submit-btn">Post Item</button>
            </form>
        </main>
    </div>
    <script src="../js/postItem.js"></script>
    <script src="../js/logout.js"></script>
</body>
</html>