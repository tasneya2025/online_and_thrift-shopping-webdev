<?php
session_start();
require_once("../../Model/adminModel.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 3) {
    header("Location: ../login/login.php");
    exit();
}

$products  = getAllProductsAdmin();
$adminName = $_SESSION['name'] ?? 'Admin';
$flashMsg  = $_SESSION['admin_msg'] ?? '';
unset($_SESSION['admin_msg']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shoppon | Manage Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/adminPanel.css">
    <link rel="stylesheet" href="../css/logout.css">
</head>
<body>
<div class="dashboard-container">

    <aside class="sidebar">
        <div class="logo-section">
            <div class="logo-box"><i class="fa-solid fa-bag-shopping"></i></div>
            <div><h2>Shoppon</h2><p>Admin Center</p></div>
        </div>
        <nav class="side-nav">
            <a href="adminDashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="adminBuyers.php"><i class="fa-solid fa-users"></i> Buyers</a>
            <a href="adminSellers.php"><i class="fa-solid fa-store"></i> Sellers</a>
            <a href="adminProducts.php" class="active"><i class="fa-solid fa-box-open"></i> Products</a>
            <a href="adminOrders.php"><i class="fa-solid fa-receipt"></i> Orders</a>
        </nav>
        <div class="bottom-section">
            <div class="account-info">
                <p>Admin Account</p>
                <strong style="color:#3b71fe;"><?php echo htmlspecialchars($adminName); ?></strong><br>
                <span style="font-size:11px;color:#999;"><?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></span>
            </div>
            <a href="../../Controller/logoutController.php" class="logout-link">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </aside>

    <main class="main-content">
        <div class="content-header">
            <h1>Manage Products</h1>
            <p><?php echo count($products); ?> products listed on the platform.</p>
        </div>

        <?php if ($flashMsg): ?>
        <div class="flash-msg"><i class="fa-solid fa-check-circle"></i> <?php echo htmlspecialchars($flashMsg); ?></div>
        <?php endif; ?>

        <div class="section-card">
            <div class="section-title"><i class="fa-solid fa-box-open"></i> All Products</div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Condition</th>
                        <th>Seller</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($products)): ?>
                    <tr class="empty-row"><td colspan="8">No products found.</td></tr>
                <?php else: ?>
                    <?php foreach ($products as $p): ?>
                    <tr>
                        <td>
                            <img src="../uploads/<?php echo htmlspecialchars($p['image_path']); ?>"
                                 class="product-thumb"
                                 onerror="this.src='../images/item1.jpg'">
                        </td>
                        <td><strong><?php echo htmlspecialchars($p['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars(ucfirst($p['category'] ?? '—')); ?></td>
                        <td><strong>$<?php echo number_format((float)$p['price'], 2); ?></strong></td>
                        <td><?php echo (int)$p['stock']; ?> left</td>
                        <td>
                            <span class="badge <?php echo ($p['item_condition'] ?? '') === 'new' ? 'badge-new' : 'badge-used'; ?>">
                                <?php echo ucfirst($p['item_condition'] ?? 'new'); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($p['seller_name'] ?? '—'); ?></td>
                        <td>
                            <form method="POST" action="../../Controller/adminActionController.php"
                                  onsubmit="return confirm('Remove this product permanently?');">
                                <input type="hidden" name="action" value="delete_product">
                                <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                                <input type="hidden" name="redirect" value="../View/Admin/adminProducts.php">
                                <button type="submit" class="btn-action btn-delete">
                                    <i class="fa-solid fa-trash"></i> Remove
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
<script src="../js/logout.js"></script>

</body>
</html>