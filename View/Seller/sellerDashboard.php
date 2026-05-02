<?php
session_start();

require_once("../../Model/productModel.php");
require_once("../../Model/UserModel.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: ../../View/login/login.php");
    exit();
}

$seller_email     = $_SESSION['email'];
$productList      = getSellerProducts($seller_email);
$conn             = get_db_connection();
$seller_email_esc = mysqli_real_escape_string($conn, $seller_email);

$totalsRes   = mysqli_query($conn,
    "SELECT COUNT(*) AS total_sold, COALESCE(SUM(price),0) AS total_income
     FROM purchase_history WHERE seller_email='$seller_email_esc'"
);
$totals      = mysqli_fetch_assoc($totalsRes);
$totalSold   = (int)($totals['total_sold']   ?? 0);
$totalIncome = (float)($totals['total_income'] ?? 0);

$notifEnabled = getNotificationStatus($seller_email);
$bellNotifs   = [];
$bellUnread   = 0;
if ($notifEnabled) {
    $bellNotifs = getSellerNotifications($seller_email, 8);
    $bellUnread = countUnreadNotifications($seller_email);
}

function bellTimeAgo($datetime) {
    $diff = (new DateTime())->getTimestamp() - (new DateTime($datetime))->getTimestamp();
    if ($diff < 60)     return 'just now';
    if ($diff < 3600)   return floor($diff / 60) . 'm ago';
    if ($diff < 86400)  return floor($diff / 3600) . 'h ago';
    if ($diff < 604800) return floor($diff / 86400) . 'd ago';
    return (new DateTime($datetime))->format('M j');
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shoppon | Seller Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/sellerDashboard.css">

</head>
<body>
<div class="dashboard-container">

    <aside class="sidebar">
        <div class="logo-section">
            <div class="logo-box"><i class="fa-solid fa-bag-shopping"></i></div>
            <div><h2>Shoppon</h2><p>Seller Center</p></div>
        </div>
        <nav class="side-nav">
            <a href="sellerDashboard.php" class="active"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="sellerPostItems.php"><i class="fa-solid fa-plus"></i> Post Item</a>
            <a href="sellerSettings.php"><i class="fa-solid fa-gear"></i> Settings</a>
            <a href="sellerHistory.php"><i class="fa-regular fa-clock"></i>History</a>

        </nav>
        <div class="bottom-section">
            <div class="account-info">
                <p>Seller Account</p>
                <strong style="color:#3b71fe;"><?php echo htmlspecialchars($_SESSION['name'] ?? 'Guest Seller'); ?></strong><br>
                <small><?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></small>
            </div>
            <a href="../../Controller/logoutController.php" class="logout-link">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </aside>

    <main class="main-content">

        <div class="header-row">
            <div class="content-header">
                <h1>Store Overview</h1>
                <p>Welcome back, <?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?>!</p>
            </div>

            <?php if ($notifEnabled): ?>
            <div class="bell-wrap" id="bellWrap">
                <button class="bell-btn" id="bellBtn" onclick="toggleBell(event)">
                    <i class="fa-solid fa-bell"></i>
                    <span class="bell-badge <?php echo $bellUnread == 0 ? 'hidden' : ''; ?>" id="bellBadge">
                        <?php echo $bellUnread > 9 ? '9+' : $bellUnread; ?>
                    </span>
                </button>

                <div class="notif-dropdown" id="notifDropdown">
                    <div class="notif-drop-header">
                        <span><i class="fa-solid fa-bell" style="color:#3b71fe;margin-right:6px;"></i>Notifications</span>
                        <?php if ($bellUnread > 0): ?>
                        <button class="notif-drop-markall" id="dropMarkAll">Mark all read</button>
                        <?php endif; ?>
                    </div>

                    <div class="notif-drop-list">
                        <?php if (!empty($bellNotifs)): ?>
                            <?php foreach ($bellNotifs as $n):
                                $isUnread = !$n['is_read'];
                                $ago = bellTimeAgo($n['purchase_date']);
                            ?>
                            <div class="notif-drop-item <?php echo $isUnread ? 'unread' : 'read'; ?>">
                                <div class="notif-drop-dot"></div>
                                <div class="notif-drop-icon">
                                    <i class="fa-solid fa-bag-shopping"></i>
                                </div>
                                <div class="notif-drop-body">
                                    <div class="notif-drop-text">
                                        <strong><?php echo htmlspecialchars($n['buyer_email']); ?></strong>
                                        bought <strong><?php echo htmlspecialchars($n['product_name']); ?></strong>
                                    </div>
                                    <div class="notif-drop-time"><?php echo $ago; ?></div>
                                </div>
                                <div class="notif-drop-price">+$<?php echo number_format($n['price'], 2); ?></div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="notif-drop-empty">
                                <i class="fa-solid fa-bell-slash"></i>
                                No notifications yet
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($bellNotifs)): ?>
                    <div class="notif-drop-footer">
                        <a href="sellerHistory.php">View all in History →</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <section class="stats-grid">
            <div class="stat-card">
                <div class="stat-info"><p>Total Posts</p><h3><?php echo count($productList); ?></h3></div>
                <div class="stat-icon blue"><i class="fa-solid fa-box"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info"><p>Total Sold</p><h3><?php echo $totalSold; ?></h3></div>
                <div class="stat-icon green"><i class="fa-solid fa-cart-shopping"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info"><p>Total Income</p><h3>$<?php echo number_format($totalIncome, 2); ?></h3></div>
                <div class="stat-icon purple"><i class="fa-solid fa-wallet"></i></div>
            </div>
        </section>

        <section class="product-grid">
            <?php if (!empty($productList)): ?>
                <?php foreach ($productList as $product):
                    $isThrift = strtolower($product['item_condition'] ?? '') === 'thrift';
                    $stock    = (int)$product['stock'];
                ?>
                <div class="product-card">
                    <div class="image-container">
                        <div class="type-badge <?php echo $isThrift ? 'badge-thrift' : 'badge-new'; ?>">
                            <i class="fa-solid <?php echo $isThrift ? 'fa-recycle' : 'fa-tag'; ?>"></i>
                            <span><?php echo $isThrift ? 'Thrift' : 'New'; ?></span>
                        </div>
                        <img src="../uploads/<?php echo htmlspecialchars($product['image_path']); ?>" alt="Product">
                        <span class="category-tag"><?php echo htmlspecialchars($product['category']); ?></span>
                    </div>
                    <div class="product-details">
                        <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                        <p>Fabric: <?php echo htmlspecialchars($product['fabric']); ?></p>
                        <div class="price-row">
                            <span class="price">$<?php echo number_format($product['price'], 2); ?></span>
                            <span class="stock-badge <?php echo $stock > 0 ? 'in-stock' : 'no-stock'; ?>">
                                <?php echo $stock > 0 ? $stock . ' left' : 'Sold Out'; ?>
                            </span>
                        </div>
                        <div class="card-actions">
                            <a href="sellerEditItems.php?id=<?php echo $product['id']; ?>" class="btn-edit">
                                <i class="fa-solid fa-pen"></i> Edit
                            </a>
                            <a href="../../Controller/deleteItemController.php?id=<?php echo $product['id']; ?>"
                               class="btn-delete"
                               onclick="return confirm('Delete this post?');">
                                <i class="fa-solid fa-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-products">
                    <i class="fa-solid fa-box-open"></i>
                    <p>No products posted yet.</p>
                    <a href="sellerPostItems.php">Post your first item</a>
                </div>
            <?php endif; ?>
        </section>

    </main>
</div>
<script src="../js/sellerDashboard.js"></script>
</body>
</html>