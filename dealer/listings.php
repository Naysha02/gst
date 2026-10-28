<?php
require_once '../functions.php';
if (!isDealerLoggedIn()) redirect('login.php');
$dealer_id = $_SESSION['dealer_id'];
$listings = $conn->query("SELECT * FROM listings WHERE dealer_id = $dealer_id ORDER BY created_at DESC");
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>My Listings</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body class="container">
    <h2>My Listings</h2>
    <?php while($row = $listings->fetch_assoc()): ?>
    <div class="card">
        <strong><?php echo e($row['title']); ?></strong> — <?php echo e($row['status']); ?><br>
        AED <?php echo number_format($row['price']); ?> • <?php echo e($row['year']); ?> • <?php echo e($row['mileage']); ?> km
    </div>
    <?php endwhile; ?>
</body>
</html>
