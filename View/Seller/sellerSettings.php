<?php

session_start();
if (!isset($_SESSION['email'])) { 
    header("Location: ../login/login.php"); 
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Settings | Shoppon</title>
    <link rel="stylesheet" href="../css/sellerSettings.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
                <a href="sellerPostItems.php"><i class="fa-solid fa-plus"></i> Post Item</a>
                <a href="sellerSettings.php" class="active"><i class="fa-solid fa-gear"></i> Settings</a>

            </nav>

            <div class="bottom-section">
                <div class="account-info">
                    <p>Seller Account</p>
                    <strong><?php echo isset($_SESSION['name']) ? $_SESSION['name'] : "Guest Seller"; ?></strong>
                    <br>
                    <small><?php echo isset($_SESSION['email']) ? $_SESSION['email'] : "seller@shoppon.com"; ?></small>
                </div>

                <a href="../../Controller/logoutController.php" class="logout-link">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </div>
        </aside>

        <main class="main-content">
            <h1>Settings</h1>
            <p>Manage your account preferences</p>

            <div class="settings-form">
                <h3><i class="fa-solid fa-user"></i> Profile Information</h3>
                <form action="../../Controller/updateSettingsController.php" method="POST">

                </form>
            </div>
        </main>
    </div>
</body>
</html>