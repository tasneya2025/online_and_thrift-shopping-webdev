<?php
session_start();
require_once("../../Model/UserModel.php");

if (!isset($_SESSION['email'])) {
    header("Location: ../login/login.php");
    exit();
}

$data = getAllHistory($_SESSION['email']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Purchase History</title>
    <link rel="stylesheet" href="../css/allHistory.css">
    <link rel="stylesheet" href="../css/logout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body>

    <aside class="sidebar">
        <div class="logo-section">
            <div class="logo-box"><i class="fa-solid fa-bag-shopping"></i></div>
            <div><h2>Shoppon</h2><p>Seller Center</p></div>
        </div>
        <nav class="side-nav">
            <a href="sellerDashboard.php" ><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="sellerPostItems.php"><i class="fa-solid fa-plus"></i> Post Item</a>
            <a href="sellerSettings.php"><i class="fa-solid fa-gear"></i> Settings</a>
            <a href="sellerHistory.php" class="active"><i class="fa-regular fa-clock"></i>History</a>

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
    <div class="container">
        <h2 style="margin-bottom: 20px;">All Purchase History</h2>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Buyer Email</th>
                    <th>Price</th>
                    <th>Purchase Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($data->num_rows > 0): ?>
                    <?php while($row = $data->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['buyer_email']); ?></td>
                        <td>$<?php echo number_format($row['price'], 2); ?></td>
                        <td><?php echo $row['purchase_date']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No purchase history found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<script src="../js/logout.js"></script>
</body>
</html>