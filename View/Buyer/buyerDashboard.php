<?php
session_start();
require_once("../../Model/productModel.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header("Location: ../login/login.php");
    exit();
}

$products = getAllProducts();

$newProducts    = [];
$thriftProducts = [];

foreach ($products as $row) {
    $condition = strtolower($row['item_condition'] ?? '');
    if ($condition === 'thrift') {
        $thriftProducts[] = $row;
    } else {
        $newProducts[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppon | Buyer Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/buyerDashboard.css">
    <link rel="stylesheet" href="../css/productModal.css">
</head>
<body>

<div class="modal-overlay" id="productModal">
    <div class="modal-card" id="modalCard">
        <button class="modal-close" onclick="closeModal()">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="modal-body" id="modalBody">
        </div>
    </div>
</div>

<script>
    var PRODUCTS = <?php
        $modalData = [];
        foreach ($products as $p) {
            $modalData[] = [
                'id'                => (int) $p['id'],
                'name'              => $p['name'],
                'seller'            => $p['seller_username'],
                'price'             => $p['price'],
                'category'          => $p['category']          ?? '',
                'product_type'      => $p['product_type']      ?? '',
                'fabric'            => $p['fabric']            ?? '',
                'sizes'             => $p['sizes']             ?? '',
                'colors'            => $p['colors']            ?? '',
                'stock'             => (int)($p['stock']       ?? 0),
                'description'       => $p['description']       ?? '',
                'image_path'        => $p['image_path']        ?? '',
                'item_condition'    => $p['item_condition']    ?? 'new',
                'used_duration'     => $p['used_duration']     ?? '',
                'condition_details' => $p['condition_details'] ?? '',
                'defects'           => $p['defects']           ?? '',
            ];
        }
        echo json_encode($modalData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    ?>;
</script>

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
            <a href="buyerViewCart.php"><i class="fa-solid fa-cart-shopping"></i> View Cart</a>
            <a href="buyerSettings.php"><i class="fa-solid fa-gear"></i> Settings</a>
            <a href="sellerHistory.php"><i class="fa-regular fa-clock"></i>My History</a>

        </nav>

        <div class="bottom-section">
            <div class="account-info">
                <p>Buyer Account</p>
                <strong style="color: #3b71fe;"><?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?></strong><br>
                <small><?php echo htmlspecialchars($_SESSION['email'] ?? 'buyer@shoppon.com'); ?></small>
            </div>
            <a href="../../Controller/logoutController.php" class="logout-link">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </aside>

    <main class="main-content">

        <?php if (isset($_SESSION['cart_msg'])): ?>
            <div class="flash-msg">
                <i class="fa-solid fa-circle-check"></i>
                <?php echo htmlspecialchars($_SESSION['cart_msg']); unset($_SESSION['cart_msg']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['checkout_msg'])): ?>
            <div class="flash-msg flash-checkout">
                <i class="fa-solid fa-box"></i>
                <?php echo htmlspecialchars($_SESSION['checkout_msg']); unset($_SESSION['checkout_msg']); ?>
            </div>
        <?php endif; ?>

        <header class="content-header">
            <div class="header-flex">
                <div>
                    <p>Welcome back, <?php echo htmlspecialchars($_SESSION['name']); ?>!</p>
                    <h1>Discover</h1>
                    <p>Fresh picks from every Shoppon seller</p>
                </div>
                <div class="search-bar">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="productSearch" placeholder="Search products, sellers, or categories...">
                </div>
            </div>
        </header>

        <div class="section-header">
            <div class="section-title-wrap">
                <span class="section-badge badge-new">
                    <i class="fa-solid fa-tag"></i> New
                </span>
                <h2>Brand New Items</h2>
            </div>
        </div>

        <section class="product-grid" id="productGrid">
            <?php if (!empty($newProducts)): ?>
                <?php foreach ($newProducts as $row):
                    $stock       = (int)($row['stock'] ?? 0);
                    $isOutOfStock = $stock <= 0;
                ?>
                <div class="product-card <?php echo $isOutOfStock ? 'oos-card' : ''; ?>"
                     data-id="<?php echo $row['id']; ?>"
                     data-name="<?php echo strtolower(htmlspecialchars($row['name'])); ?>"
                     data-seller="<?php echo strtolower(htmlspecialchars($row['seller_username'])); ?>"
                     data-cat="<?php echo strtolower(htmlspecialchars($row['category'])); ?>"
                     onclick="openModal(<?php echo (int)$row['id']; ?>)">

                    <div class="image-container">
                        <img src="../uploads/<?php echo htmlspecialchars($row['image_path']); ?>" alt="Product">
                        <span class="category-tag"><?php echo htmlspecialchars($row['category']); ?></span>
                        <span class="type-tag tag-new"><i class="fa-solid fa-tag"></i> New</span>
                        <?php if ($isOutOfStock): ?>
                            <div class="oos-overlay">Sold Out</div>
                        <?php endif; ?>
                    </div>

                    <div class="product-details">
                        <h4 class="p-name"><?php echo htmlspecialchars($row['name']); ?></h4>

                        <p class="seller-info">
                            <i class="fa-solid fa-circle-user"></i> Seller:
                            <span><?php echo htmlspecialchars($row['seller_username']); ?></span>
                        </p>

                        <p class="stock-count <?php echo $isOutOfStock ? 'text-red' : 'text-green'; ?>">
                            <i class="fa-solid <?php echo $isOutOfStock ? 'fa-xmark' : 'fa-check'; ?>"></i>
                            <?php echo $isOutOfStock ? 'Out of Stock' : $stock . ' available'; ?>
                        </p>

                        <div class="price-row">
                            <span class="price">$<?php echo number_format($row['price'], 2); ?></span>
                        </div>
                        <div class="button-group" onclick="event.stopPropagation()">
                            <?php if (!$isOutOfStock): ?>
                                <a href="../../Controller/addToCartController.php?id=<?php echo $row['id']; ?>&redirect=cart" class="btn-buy">
                                    Buy Now
                                </a>
                                <a href="../../Controller/addToCartController.php?id=<?php echo $row['id']; ?>" class="btn-cart">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </a>
                            <?php else: ?>
                                <button class="btn-buy" disabled>Unavailable</button>
                                <span class="btn-cart btn-disabled">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-products"><p>No new items available right now.</p></div>
            <?php endif; ?>
        </section>

        <div class="section-header section-header-thrift">
            <div class="section-title-wrap">
                <span class="section-badge badge-thrift">
                    <i class="fa-solid fa-recycle"></i> Thrift
                </span>
                <h2>Thrift Finds</h2>
            </div>
        </div>

        <section class="product-grid" id="thriftGrid">
            <?php if (!empty($thriftProducts)): ?>
                <?php foreach ($thriftProducts as $row):
                    $stock        = (int)($row['stock'] ?? 0);
                    $isOutOfStock = $stock <= 0;
                ?>
                <div class="product-card thrift-card <?php echo $isOutOfStock ? 'oos-card' : ''; ?>"
                     data-id="<?php echo $row['id']; ?>"
                     data-name="<?php echo strtolower(htmlspecialchars($row['name'])); ?>"
                     data-seller="<?php echo strtolower(htmlspecialchars($row['seller_username'])); ?>"
                     data-cat="<?php echo strtolower(htmlspecialchars($row['category'])); ?>"
                     onclick="openModal(<?php echo (int)$row['id']; ?>)">

                    <div class="image-container">
                        <img src="../uploads/<?php echo htmlspecialchars($row['image_path']); ?>" alt="Product">
                        <span class="category-tag"><?php echo htmlspecialchars($row['category']); ?></span>
                        <span class="type-tag tag-thrift"><i class="fa-solid fa-recycle"></i> Thrift</span>
                        <?php if ($isOutOfStock): ?>
                            <div class="oos-overlay">Sold Out</div>
                        <?php endif; ?>
                    </div>

                    <div class="product-details">
                        <h4 class="p-name"><?php echo htmlspecialchars($row['name']); ?></h4>

                        <p class="seller-info">
                            <i class="fa-solid fa-circle-user"></i> Seller:
                            <span><?php echo htmlspecialchars($row['seller_username']); ?></span>
                        </p>

                        <p class="stock-count <?php echo $isOutOfStock ? 'text-red' : 'text-green'; ?>">
                            <i class="fa-solid <?php echo $isOutOfStock ? 'fa-xmark' : 'fa-check'; ?>"></i>
                            <?php echo $isOutOfStock ? 'Out of Stock' : $stock . ' available'; ?>
                        </p>

                        <div class="price-row">
                            <span class="price">$<?php echo number_format($row['price'], 2); ?></span>
                        </div>

                        <div class="button-group" onclick="event.stopPropagation()">
                            <?php if (!$isOutOfStock): ?>
                                <a href="../../Controller/addToCartController.php?id=<?php echo $row['id']; ?>&redirect=cart" class="btn-buy btn-buy-thrift">
                                    Buy Now
                                </a>
                                <a href="../../Controller/addToCartController.php?id=<?php echo $row['id']; ?>" class="btn-cart btn-cart-thrift">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </a>
                            <?php else: ?>
                                <button class="btn-buy btn-buy-thrift" disabled>Unavailable</button>
                                <span class="btn-cart btn-disabled">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-products"><p>No thrift items available right now.</p></div>
            <?php endif; ?>
        </section>

    </main>
</div>
<script src="../js/productModal.js"></script>
<script src="../js/buyerDashboard.js"></script>


</body>
</html>