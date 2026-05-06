<?php
ob_start();
require_once(dirname(__FILE__) . "/../Model/db.php");
ob_clean();

header('Content-Type: application/json');

$email = trim($_POST['email'] ?? '');
$role  = $_POST['role'] ?? '';

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['exists' => false]);
    exit();
}

$conn  = get_db_connection();
$table = ($role == '1' || $role === 'seller') ? 'seller' : 'buyer';
$stmt  = $conn->prepare("SELECT id FROM $table WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

echo json_encode(['exists' => $stmt->num_rows > 0]);
exit();
?>