<?php
session_start();
require_once("../../Model/adminModel.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 3) {
    header("Location: ../login/login.php");
    exit();
}

ensureBanColumn();
$stats         = getAdminStats();
$recentOrders  = getAllOrdersAdmin();
$recentOrders  = array_slice($recentOrders, 0, 8);
$monthlyRev    = getMonthlyRevenue();
$adminName     = $_SESSION['name'] ?? 'Admin';
$flashMsg      = $_SESSION['admin_msg'] ?? '';
unset($_SESSION['admin_msg']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shoppon | Admin Dashboard</title>
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
            <a href="dashboard.php" class="active"><i class="fa-solid fa-house"></i> Dashboard</a>
             <a href="adminBuyers.php" ><i class="fa-solid fa-users"></i> Buyers</a>
            <a href="adminSellers.php"><i class="fa-solid fa-store"></i> Sellers</a>
            <a href="adminProducts.php"><i class="fa-solid fa-box-open"></i> Products</a>
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
            <h1>Admin Dashboard</h1>
            <p>Welcome back, <?php echo htmlspecialchars($adminName); ?>! Here's your platform overview.</p>
        </div>

        <?php if ($flashMsg): ?>
        <div class="flash-msg"><i class="fa-solid fa-check-circle"></i> <?php echo htmlspecialchars($flashMsg); ?></div>
        <?php endif; ?>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <p>Total Buyers</p>
                    <h3><?php echo $stats['total_buyers']; ?></h3>
                </div>
                <div class="stat-icon blue"><i class="fa-solid fa-users"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <p>Total Sellers</p>
                    <h3><?php echo $stats['total_sellers']; ?></h3>
                </div>
                <div class="stat-icon purple"><i class="fa-solid fa-store"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <p>Total Products</p>
                    <h3><?php echo $stats['total_products']; ?></h3>
                </div>
                <div class="stat-icon orange"><i class="fa-solid fa-box-open"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <p>Total Orders</p>
                    <h3><?php echo $stats['total_orders']; ?></h3>
                </div>
                <div class="stat-icon teal"><i class="fa-solid fa-receipt"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <p>Total Revenue</p>
                    <h3>$<?php echo number_format($stats['total_revenue'], 2); ?></h3>
                </div>
                <div class="stat-icon green"><i class="fa-solid fa-dollar-sign"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <p>Pending Orders</p>
                    <h3><?php echo $stats['pending_orders']; ?></h3>
                </div>
                <div class="stat-icon red"><i class="fa-solid fa-clock"></i></div>
            </div>
        </div>

<div class="section-card">
            <div class="section-title"><i class="fa-solid fa-receipt"></i> Recent Orders</div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Buyer</th>
                        <th>Email</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($recentOrders)): ?>
                    <tr class="empty-row"><td colspan="7">No orders yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($recentOrders as $o): ?>
                    <tr>
                        <td>#<?php echo $o['id']; ?></td>
                        <td><?php echo htmlspecialchars($o['buyer_name'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($o['buyer_email']); ?></td>
                        <td><strong>$<?php echo number_format($o['total_amount'], 2); ?></strong></td>
                        <td><?php echo htmlspecialchars(ucfirst($o['payment_method'])); ?></td>
                        <td>
                            <span class="badge <?php echo $o['status'] === 'paid' ? 'badge-paid' : 'badge-pending'; ?>">
                                <?php echo ucfirst($o['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($o['created_at'])); ?></td>
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