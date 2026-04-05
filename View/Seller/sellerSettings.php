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
                <strong style="color: #3b71fe;">
                    <?php echo $_SESSION['name'] ?? "Guest Seller"; ?>
                </strong><br>
                <small><?php echo $_SESSION['email']; ?></small>
            </div>

            <a href="../../Controller/logoutController.php" class="logout-link">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </aside>

    <main class="main-content">

        <header class="content-header">
            <h1 style="color: #3b71fe;">Settings</h1>
            <p style="color: grey;">Manage your account preferences</p>
        </header>

        <?php if(isset($_GET['success'])): ?>
            <div class="alert-success">Updated successfully!</div>
        <?php endif; ?>

        <?php if(isset($_GET['error'])): ?>
            <div class="alert-error" style="color: red;"><?php echo $_GET['error']; ?></div>
        <?php endif; ?>

        <div class="settings-card">
            <h3 style="color: #3b71fe;"><i class="fa-solid fa-user"></i> Profile Information</h3>

            <form action="../../Controller/updateSettingsController.php" method="POST">
                <div class="form-group">
                    <br>
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo $_SESSION['name'] ?? ''; ?>" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" value="<?php echo $_SESSION['email']; ?>" disabled>
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" rows="4"><?php echo $_SESSION['address'] ?? ''; ?></textarea>
                </div>

                <button type="submit" name="update_profile" class="btn-update">Save Changes</button>
            </form>
        </div>

        <div class="settings-card">
            <h3 style="color: #3b71fe;"><i class="fa-solid fa-lock"></i> Change Password</h3><br>

            <form action="../../Controller/updateSettingsController.php" method="POST">

                <div class="form-group pass-field">
                    <label>Current Password</label>
                    <div class="input-wrapper">
                        <input type="password" name="current_password" id="curr_p" required>
                        <i class="fa-solid fa-eye" onclick="toggle('curr_p', this)"></i>
                    </div>
                </div>

                <div class="form-group pass-field">
                    <label>New Password</label>
                    <div class="input-wrapper">
                        <input type="password" name="new_password" id="new_p" required>
                        <i class="fa-solid fa-eye" onclick="toggle('new_p', this)"></i>
                    </div>
                </div>

                <div class="form-group pass-field">
                    <label>Confirm New Password</label>
                    <div class="input-wrapper">
                        <input type="password" name="confirm_password" id="conf_p" required>
                        <i class="fa-solid fa-eye" onclick="toggle('conf_p', this)"></i>
                    </div>
                </div>

                <button type="submit" name="update_pass" class="btn-update">Update Password</button>
            </form>
        </div>

        <div class="settings-card">
            <h3 style="color: #3b71fe;"><i class="fa-solid fa-bell"></i> Notifications</h3><br>

            <?php
            require_once("../../Model/UserModel.php");
            $status = getNotificationStatus($_SESSION['email']);
            ?>

            <div class="notif-item">
                <div>
                    <strong>Purchase Notifications</strong>
                    <p>Get notified when someone buys your product</p>
                </div>

                <label class="switch">
                    <input type="checkbox" id="notifToggle" <?php echo $status ? "checked" : ""; ?>>
                    <span class="slider"></span>
                </label>
            </div>
        </div>

    </main>
</div>

<script>
function toggle(id, el) {
    const input = document.getElementById(id);
    if (input.type === "password") {
        input.type = "text";
        el.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        input.type = "password";
        el.classList.replace("fa-eye-slash", "fa-eye");
    }
}

document.getElementById("notifToggle").addEventListener("change", function() {
    let status = this.checked ? 1 : 0;

    fetch("../../Controller/updateSettingsController.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "toggle_notification=1&notification=" + status
    });
});
</script>

</body>
</html>