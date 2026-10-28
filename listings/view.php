<?php
require_once '../functions.php';
$id = intval($_GET['id'] ?? 0);
$res = $conn->query("SELECT l.*, d.name as dealer_name, d.id as dealer_id FROM listings l JOIN dealers d ON d.id = l.dealer_id WHERE l.id = $id AND l.status='published' LIMIT 1");
if (!$res || $res->num_rows == 0) { echo "Listing not found or not published."; exit; }
$l = $res->fetch_assoc();
$imgs = $l['images'] ? json_decode($l['images'], true) : [];
?>
<!doctype html>
<html><head><meta charset="utf-8"><title><?php echo e($l['title']); ?></title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body class="container">
    <h2><?php echo e($l['title']); ?></h2>
    <?php if($imgs): foreach($imgs as $img): ?>
        <img src="../<?php echo e($img); ?>" style="max-width:200px; margin-right:6px;">
    <?php endforeach; else: ?>
        <img src="../assets/images/no_image.png" style="max-width:200px;">
    <?php endif; ?>
    <p><strong>AED <?php echo number_format($l['price']); ?></strong></p>
    <p><?php echo e($l['make'].' '.$l['model'].' • '.$l['year']); ?></p>
    <p>Fuel: <?php echo e($l['fuel_type']); ?> • Transmission: <?php echo e($l['transmission']); ?></p>

    <h3>Contact Dealer</h3>
    <form method="POST" action="../lead_submit.php">
        <input type="hidden" name="listing_id" value="<?php echo $l['id']; ?>">
        <input type="hidden" name="dealer_id" value="<?php echo $l['dealer_id']; ?>">
        <div class="form-row"><input type="text" name="name" placeholder="Your name" required></div>
        <div class="form-row"><input type="email" name="email" placeholder="Your email" required></div>
        <div class="form-row"><input type="text" name="phone" placeholder="Phone"></div>
        <div class="form-row"><textarea name="message" placeholder="Message"></textarea></div>
        <button type="submit">Send Enquiry</button>
    </form>

    <p><a href="../index.php">Back to listings</a></p>
</body>
</html>
