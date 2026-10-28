<?php
    require_role('dealer');
    global $pdo;
    $user = current_user();
    // fetch dealer id
    $d = $pdo->prepare('SELECT * FROM dealers WHERE user_id=? LIMIT 1'); $d->execute([$user['id']]); $dealer = $d->fetch();
    echo '<h2>Dealer Dashboard - '.htmlspecialchars($dealer['company_name']).'</h2>';
    echo '<p><a href="index.php?page=bulk_upload">Bulk CSV Upload</a> | <a href="index.php?page=logout">Logout</a></p>';

    // simple add listing form
    if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['add_listing'])){
        $title = $_POST['title']; $make = $_POST['make']; $model = $_POST['model']; $year = $_POST['year'];
        $price = $_POST['price']; $mileage = $_POST['mileage']; $fuel = $_POST['fuel']; $trans = $_POST['transmission'];
        // handle a single image upload basic
        $imgPath = 'assets/no-image.png';
        if(isset($_FILES['image']) && $_FILES['image']['tmp_name']){
            $targetDir = __DIR__ . '/../uploads/';
            if(!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $fn = basename($_FILES['image']['name']);
            $dest = $targetDir . time() . '_' . $fn;
            move_uploaded_file($_FILES['image']['tmp_name'], $dest);
            $imgPath = 'uploads/' . basename($dest);
        }
        $images = json_encode([$imgPath]);
        $pdo->prepare('INSERT INTO listings (dealer_id,title,make,model,year,mileage,fuel_type,transmission,price,location,images,approved) VALUES (?,?,?,?,?,?,?,?,?,?,?,0)')
            ->execute([$dealer['id'],$title,$make,$model,$year,$mileage,$fuel,$trans,$price,'UAE',$images]);
        echo '<p>Listing submitted for admin approval.</p>';
    }

    // show add form
    echo '<h3>Add Listing</h3>';
    ?>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="add_listing" value="1"/>
        <label>Title: <input name="title" required></label><br/>
        <label>Make: <input name="make"></label><br/>
        <label>Model: <input name="model"></label><br/>
        <label>Year: <input name="year" type="number"></label><br/>
        <label>Mileage: <input name="mileage" type="number"></label><br/>
        <label>Price: <input name="price" type="number" step="0.01"></label><br/>
        <label>Image: <input type="file" name="image" accept="image/*"></label><br/>
        <button>Add</button>
    </form>

    <h3>Your Listings</h3>
    <?php
    $stmt = $pdo->prepare('SELECT * FROM listings WHERE dealer_id=? ORDER BY created_at DESC'); $stmt->execute([$dealer['id']]);
    $rows = $stmt->fetchAll();
    foreach($rows as $r){
        echo '<div class="card">'.htmlspecialchars($r['title']).' - ' . ($r['approved'] ? 'Approved' : 'Pending') . '</div>';
    }
    ?>