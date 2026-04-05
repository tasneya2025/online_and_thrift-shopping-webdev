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
</head>
<body>

<div class="container">
    <a href="sellerDashboard.php" class="back-btn">← Back</a>  
    <h2>All Purchase History</h2>
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

</body>
</html>