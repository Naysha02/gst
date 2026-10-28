<?php
    global $pdo;
    $stmt = $pdo->query('SELECT l.*, d.company_name FROM listings l JOIN dealers d ON l.dealer_id=d.id WHERE l.approved=1 ORDER BY l.created_at DESC');
    $all = $stmt->fetchAll();
    echo '<h2>Active Listings</h2>';
    if(!$all) echo '<p>No listings yet.</p>';
    foreach($all as $row){
        $imgs = json_decode($row['images'], true) ?: [];
        $thumb = $imgs[0] ?? 'assets/no-image.png';
        echo '<div class="card">';
        echo "<img src='".htmlspecialchars($thumb)."' width=160 />";
        echo '<h3>'.htmlspecialchars($row['title']).'</h3>';
        echo '<p>'.htmlspecialchars($row['make'] . ' ' . $row['model'] . ' - ' . $row['year']).'</p>';
        echo '<p>Price: AED '.htmlspecialchars($row['price']).'</p>';
        echo "<p><a href='index.php?page=view&id={$row['id']}'>View</a></p>";
        echo '</div>';
    }
    ?>