<?php
require_once '../functions.php';
if (!isCustomerLoggedIn()) redirect('login.php');
$cid = $_SESSION['customer_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['listing_id'])) {
    $lid = intval($_POST['listing_id']);
    $conn->query("INSERT INTO favourites (customer_id,listing_id) VALUES ($cid,$lid)");
}
$res = $conn->query("SELECT l.* FROM listings l JOIN favourites f ON f.listing_id = l.id WHERE f.customer_id = $cid ORDER BY f.created_at DESC");
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Favourites</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body class="container">
    <h2>My Favourites</h2>
    <?php while($row = $res->fetch_assoc()): ?>
        <div class="card">
            <strong><?php echo e($row['title']); ?></strong><br>
            AED <?php echo number_format($row['price']); ?>
        </div>
    <?php endwhile; ?>
</body>
</html>
