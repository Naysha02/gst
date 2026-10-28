<?php
require_once '../functions.php';
if (!isDealerLoggedIn()) redirect('login.php');
$dealer_id = $_SESSION['dealer_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    $handle = fopen($file, 'r');
    $header = fgetcsv($handle);
    $rowNo = 1; $created = 0; $errors = [];
    while (($data = fgetcsv($handle)) !== false) {
        $rowNo++;
        // expecting columns: title,make,model,year,price,mileage,fuel_type,transmission,condition,city
        if (count($data) < 10) { $errors[] = "Row $rowNo: insufficient columns"; continue; }
        $title = $conn->real_escape_string($data[0]);
        $make = $conn->real_escape_string($data[1]);
        $model = $conn->real_escape_string($data[2]);
        $year = intval($data[3]);
        $price = floatval($data[4]);
        $mileage = intval($data[5]);
        $fuel = $conn->real_escape_string($data[6]);
        $trans = $conn->real_escape_string($data[7]);
        $cond = $conn->real_escape_string($data[8]);
        $city = $conn->real_escape_string($data[9]);
        $images_json = json_encode([]); // CSV not providing images here
        $stmt = $conn->prepare("INSERT INTO listings (dealer_id,title,make,model,year,price,mileage,fuel_type,transmission,`condition`,city,images,status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,'pending')");
        $stmt->bind_param('isssidiissss', $dealer_id,$title,$make,$model,$year,$price,$mileage,$fuel,$trans,$cond,$city,$images_json);
        if ($stmt->execute()) $created++; else $errors[] = "Row $rowNo: DB insert failed";
    }
    fclose($handle);
    $msg = "$created listings added. ".count($errors)." errors.";
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Bulk Upload</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body class="container">
    <h2>Bulk Upload CSV</h2>
    <?php if(!empty($msg)) echo "<div class='card'>$msg</div>"; ?>
    <?php if(!empty($errors)) { echo "<div class='card'><strong>Errors:</strong><ul>"; foreach($errors as $er) echo "<li>".e($er)."</li>"; echo "</ul></div>"; } ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-row"><input type="file" name="csv_file" accept=".csv" required></div>
        <button type="submit">Upload CSV</button>
    </form>
    <p>CSV columns order: title,make,model,year,price,mileage,fuel_type,transmission,condition,city</p>
</body>
</html>
