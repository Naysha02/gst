<?php
    require_role('admin');
    global $pdo;
    echo '<h2>Admin Dashboard</h2>';
    echo '<p><a href="index.php?page=logout">Logout</a></p>';

    // Approve dealers
    echo '<h3>Pending Dealers</h3>';
    $pd = $pdo->query('SELECT u.*, d.company_name, d.id as dealer_id FROM users u JOIN dealers d ON u.id=d.user_id WHERE u.role="dealer" AND u.approved=0')->fetchAll();
    foreach($pd as $p){
        echo '<div>'.htmlspecialchars($p['name']).' ('.htmlspecialchars($p['email']).') - '.htmlspecialchars($p['company_name']).
             ' <a href="index.php?page=admin_dashboard&approve_dealer='. $p['id'] .'">Approve</a></div>';
    }

    if(isset($_GET['approve_dealer'])){
        $uid = intval($_GET['approve_dealer']);
        $pdo->prepare('UPDATE users SET approved=1 WHERE id=?')->execute([$uid]);
        echo '<p>Dealer approved.</p>';
    }

    // Approve listings
    echo '<h3>Pending Listings</h3>';
    $pl = $pdo->query('SELECT l.*, d.company_name FROM listings l JOIN dealers d ON l.dealer_id=d.id WHERE l.approved=0')->fetchAll();
    foreach($pl as $l){
        echo '<div>'.htmlspecialchars($l['title']).' by '.htmlspecialchars($l['company_name']).' - <a href="index.php?page=admin_dashboard&approve_listing=' . $l['id'] . '">Approve</a></div>';
    }

    if(isset($_GET['approve_listing'])){
        $lid = intval($_GET['approve_listing']);
        $pdo->prepare('UPDATE listings SET approved=1 WHERE id=?')->execute([$lid]);
        echo '<p>Listing approved.</p>';
    }

    ?>