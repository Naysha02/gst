<?php
require_once '../functions.php';
if (!isDealerLoggedIn()) redirect('login.php');
$dealer_id = $_SESSION['dealer_id'];
$res = $conn->query("SELECT * FROM dealers WHERE id = $dealer_id");
$dealer = $res->fetch_assoc();

// dealer listings
$listings = $conn->query("SELECT * FROM listings WHERE dealer_id = $dealer_id ORDER BY created_at DESC");
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Dealer Dashboard</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body class="container">
    <div class="header">
        <h2>Welcome, <?php echo e($dealer['name']); ?></h2>
        <div><a href="add_listing.php">Add Listing</a> | <a href="bulk_upload.php">Bulk Upload</a> | <a href="listings.php">My Listings</a> | <a href="../index.php">Home</a></div>
    </div>

    <h3>Your Listings</h3>
    <?php while($row = $listings->fetch_assoc()): ?>
        <div class="card">
            <h4><?php echo e($row['title']); ?> (<?php echo e($row['status']); ?>)</h4>
            <p>AED <?php echo number_format($row['price']); ?> • <?php echo e($row['year']); ?> • <?php echo e($row['mileage']); ?> km</p>
            <a href="listings.php?edit=<?php echo $row['id']; ?>">Edit</a>
        </div>
    <?php endwhile; ?>

    <p><a href="logout.php">Logout</a></p>
</body>
</html>
