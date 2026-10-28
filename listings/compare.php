<?php
require_once '../functions.php';
$ids = array_filter(array_map('intval', explode(',', $_GET['ids'] ?? '')));
if (!$ids) { echo "No listings selected."; exit; }
$ids_list = implode(',', $ids);
$res = $conn->query("SELECT * FROM listings WHERE id IN ($ids_list) AND status='published'");
$items = [];
while($r = $res->fetch_assoc()) $items[] = $r;
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Compare</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body class="container">
    <h2>Compare</h2>
    <div style="display:flex; gap:12px;">
    <?php foreach($items as $it): ?>
        <div class="card" style="flex:1;">
            <h3><?php echo e($it['title']); ?></h3>
            <p>AED <?php echo number_format($it['price']); ?></p>
            <p><?php echo e($it['make'].' '.$it['model'].' • '.$it['year']); ?></p>
            <p>Mileage: <?php echo e($it['mileage']); ?></p>
            <p>Fuel: <?php echo e($it['fuel_type']); ?> | <?php echo e($it['transmission']); ?></p>
            <a href="view.php?id=<?php echo $it['id']; ?>">View</a>
        </div>
    <?php endforeach; ?>
    </div>
</body>
</html>
