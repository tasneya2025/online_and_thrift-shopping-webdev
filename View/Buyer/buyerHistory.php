<?php
session_start();
require_once("../../Model/UserModel.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header("Location: ../login/login.php");
    exit();
}

$historyResult = getBuyerHistory($_SESSION['email']);
$rows = [];
while ($row = $historyResult->fetch_assoc()) {
    $rows[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppon | My Purchase History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/allHistory.css">
    <link rel="stylesheet" href="../css/logout.css">
</head>
<body>

<aside class="sidebar">
    <div class="logo-section">
        <div class="logo-box"><i class="fa-solid fa-bag-shopping"></i></div>
        <div>
            <h2>Shoppon</h2>
            <p>Buyer Center</p>
        </div>
    </div>
    <nav class="side-nav">
        <a href="buyerDashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a>
        <a href="buyerViewCart.php"><i class="fa-solid fa-cart-shopping"></i> View Cart</a>
        <a href="buyerSettings.php"><i class="fa-solid fa-gear"></i> Settings</a>
        <a href="buyerHistory.php" class="active"><i class="fa-regular fa-clock"></i> My History</a>
    </nav>
    <div class="bottom-section">
        <div class="account-info">
            <p>Buyer Account</p>
            <strong style="color:#3b71fe;"><?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?></strong><br>
            <small><?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></small>
        </div>
        <a href="../../Controller/logoutController.php" class="logout-link">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>
    </div>
</aside>

<div class="container">
    <div class="page-header">
        <h2>My Purchase History</h2>
    </div>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Shop (Seller)</th>
                <th>Price</th>
                <th>Date</th>
                <th>Payment Method</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($rows)): ?>
                <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['seller_username'] ?? 'Unknown Shop'); ?></td>
                    <td>$<?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo htmlspecialchars(date('d M Y, h:i A', strtotime($row['purchase_date']))); ?></td>
                    <td>
                        <?php
                            $method = strtolower($row['payment_method'] ?? '');
                            if ($method === 'card') {
                                echo '<span><i class="fa-regular fa-credit-card"></i> Card</span>';
                            } elseif ($method === 'bkash') {
                                echo '<span><i class="fa-solid fa-mobile-screen-button"></i> bKash</span>';
                            } elseif ($method === 'cod') {
                                echo '<span><i class="fa-solid fa-money-bill-wave"></i> Cash on Delivery</span>';
                            } else {
                                echo '<span>—</span>';
                            }
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="empty-row">You have no purchase history yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="../js/logout.js"></script>
</body>
</html>