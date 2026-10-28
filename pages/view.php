<?php
    global $pdo;
    $id = intval($_GET['id'] ?? 0);
    $stmt = $pdo->prepare('SELECT l.*, d.company_name, u.email FROM listings l JOIN dealers d ON l.dealer_id=d.id JOIN users u ON d.user_id=u.id WHERE l.id=? LIMIT 1');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if(!$row){ echo '<p>Listing not found.</p>'; return; }
    $imgs = json_decode($row['images'], true) ?: [];
    echo '<h2>'.htmlspecialchars($row['title']).'</h2>';
    foreach($imgs as $im) echo "<img src='".htmlspecialchars($im)."' width=220 />";
    echo '<p>Price: AED '.$row['price'].'</p>';
    echo '<p>Dealer: '.htmlspecialchars($row['company_name']).' - Email: '.htmlspecialchars($row['email']).'</p>';
    echo "<p><a href='mailto:".htmlspecialchars($row['email'])."?subject=Enquiry%20about%20".urlencode($row['title'])."'>Contact Dealer</a></p>";
    ?>