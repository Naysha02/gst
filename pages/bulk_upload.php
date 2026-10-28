<?php
    require_role('dealer');
    global $pdo;
    $user = current_user();
    $d = $pdo->prepare('SELECT * FROM dealers WHERE user_id=? LIMIT 1'); $d->execute([$user['id']]); $dealer = $d->fetch();
    if($_SERVER['REQUEST_METHOD']==='POST' && isset($_FILES['csv'])){
        $csv = $_FILES['csv']['tmp_name'];
        $handle = fopen($csv,'r');
        $header = fgetcsv($handle);
        while(($row = fgetcsv($handle))!==false){
            // expected columns: title,make,model,year,mileage,fuel,transmission,price
            list($title,$make,$model,$year,$mileage,$fuel,$trans,$price) = array_pad($row,8,'');
            $images = json_encode(['assets/no-image.png']);
            $pdo->prepare('INSERT INTO listings (dealer_id,title,make,model,year,mileage,fuel_type,transmission,price,location,images,approved) VALUES (?,?,?,?,?,?,?,?,?,?,?,0)')
                ->execute([$dealer['id'],$title,$make,$model,$year,$mileage,$fuel,$trans,$price,'UAE',$images]);
        }
        fclose($handle);
        echo '<p>CSV processed. Listings submitted for admin approval.</p>';
    }
    ?>
    <h2>Bulk CSV Upload</h2>
    <p>CSV columns: title,make,model,year,mileage,fuel,transmission,price</p>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="csv" accept=".csv" required />
        <button>Upload CSV</button>
    </form>
    