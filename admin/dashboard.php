<?php
session_start();
require_once '../db.php';
require_once '../functions.php';

// ✅ Check admin session properly
if (!isset($_SESSION['admin_id'])) {
    redirect('login.php');
    exit;
}

// Fetch pending dealers
$pendingDealers = $conn->query("SELECT * FROM dealers WHERE status='pending' ORDER BY created_at DESC");

// Fetch pending listings
$pendingListings = $conn->query("SELECT l.*, d.name as dealer_name 
    FROM listings l 
    JOIN dealers d ON d.id = l.dealer_id 
    WHERE l.status='pending' 
    ORDER BY l.created_at DESC");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    h2 { color: #4e73df; }
    .card {
      border: 1px solid #ccc; padding: 12px; margin-bottom: 10px; border-radius: 8px;
      background: #f9f9f9;
    }
    a { text-decoration: none; color: #007bff; }
    a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <h2>Admin Dashboard</h2>

  <h3>Pending Dealers</h3>
  <?php if ($pendingDealers->num_rows > 0): ?>
    <?php while($d = $pendingDealers->fetch_assoc()): ?>
      <div class="card">
        <strong><?php echo htmlspecialchars($d['name']); ?></strong> — <?php echo htmlspecialchars($d['email']); ?>
        <div style="margin-top:8px;">
          <a href="approve_dealer.php?id=<?php echo $d['id']; ?>&action=approve">Approve</a> |
          <a href="approve_dealer.php?id=<?php echo $d['id']; ?>&action=reject">Reject</a>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No pending dealers.</p>
  <?php endif; ?>

  <h3>Pending Listings</h3>
  <?php if ($pendingListings->num_rows > 0): ?>
    <?php while($l = $pendingListings->fetch_assoc()): ?>
      <div class="card">
        <strong><?php echo htmlspecialchars($l['title']); ?></strong> by <?php echo htmlspecialchars($l['dealer_name']); ?> <br>
        AED <?php echo number_format($l['price']); ?>
        <div style="margin-top:8px;">
          <a href="approve_listing.php?id=<?php echo $l['id']; ?>&action=publish">Publish</a> |
          <a href="approve_listing.php?id=<?php echo $l['id']; ?>&action=reject">Reject</a>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No pending listings.</p>
  <?php endif; ?>

  <p><a href="logout.php">Logout</a></p>
</body>
</html>
