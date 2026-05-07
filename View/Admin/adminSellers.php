<?php
session_start();
require_once("../../Model/adminModel.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 3) {
    header("Location: ../login/login.php");
    exit();
}

ensureBanColumn();
$sellers   = getAllSellers();
$adminName = $_SESSION['name'] ?? 'Admin';
$flashMsg  = $_SESSION['admin_msg'] ?? '';
unset($_SESSION['admin_msg']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shoppon | Manage Sellers</title>
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
            <a href="adminDashboard.php" ><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="adminBuyers.php"><i class="fa-solid fa-users"></i> Buyers</a>
            <a href="adminSellers.php" class="active"><i class="fa-solid fa-store"></i> Sellers</a>
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
            <h1>Manage Sellers</h1>
            <p><?php echo count($sellers); ?> registered sellers on the platform.</p>
        </div>

        <?php if ($flashMsg): ?>
        <div class="flash-msg"><i class="fa-solid fa-check-circle"></i> <?php echo htmlspecialchars($flashMsg); ?></div>
        <?php endif; ?>

        <div class="section-card">
            <div class="section-title"><i class="fa-solid fa-store"></i> All Sellers</div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($sellers)): ?>
                    <tr class="empty-row"><td colspan="7">No sellers found.</td></tr>
                <?php else: ?>
                    <?php foreach ($sellers as $s): ?>
                    <tr>
                        <td><?php echo $s['id']; ?></td>
                        <td><?php echo htmlspecialchars($s['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($s['username']); ?></td>
                        <td><?php echo htmlspecialchars($s['email']); ?></td>
                        <td><span class="badge badge-new"><?php echo $s['product_count']; ?> items</span></td>
                        <td>
                            <span class="badge <?php echo $s['is_banned'] ? 'badge-banned' : 'badge-active'; ?>">
                                <?php echo $s['is_banned'] ? 'Banned' : 'Active'; ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="../../Controller/adminActionController.php" style="display:inline;">
                                <input type="hidden" name="table" value="seller">
                                <input type="hidden" name="id" value="<?php echo $s['id']; ?>">
                                <input type="hidden" name="redirect" value="../Admin/sellers.php">
                                <?php if ($s['is_banned']): ?>
                                    <input type="hidden" name="action" value="unban">
                                    <button type="submit" class="btn-action btn-unban"><i class="fa-solid fa-unlock"></i> Unban</button>
                                <?php else: ?>
                                    <input type="hidden" name="action" value="ban">
                                    <button type="submit" class="btn-action btn-ban"><i class="fa-solid fa-ban"></i> Ban</button>
                                <?php endif; ?>
                            </form>
                            <form method="POST" action="../../Controller/adminActionController.php" style="display:inline;"
                                  onsubmit="return confirm('Delete this seller and all their data?');">
                                <input type="hidden" name="action" value="delete_user">
                                <input type="hidden" name="table" value="seller">
                                <input type="hidden" name="id" value="<?php echo $s['id']; ?>">
                                <input type="hidden" name="redirect" value="../Admin/sellers.php">
                                <button type="submit" class="btn-action btn-delete"><i class="fa-solid fa-trash"></i></button>
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