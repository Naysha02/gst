<?php
require_once '../functions.php';
requireCustomer();

$email = $_SESSION['customer'];
$user = $conn->query("SELECT * FROM customers WHERE email='$email'")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head><title>My Profile</title></head>
<body>
<h2>Welcome, <?php echo e($user['name']); ?></h2>
<p><strong>Email:</strong> <?php echo e($user['email']); ?></p>
<p><strong>Phone:</strong> <?php echo e($user['phone']); ?></p>
<p><strong>Joined:</strong> <?php echo e($user['created_at']); ?></p>
<a href="logout.php">Logout</a>
</body>
</html>
