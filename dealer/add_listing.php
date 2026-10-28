<?php
require_once '../functions.php';
requireDealer(); // check dealer login

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title'] ?? '');
    $make    = trim($_POST['make'] ?? '');
    $model   = trim($_POST['model'] ?? '');
    $year    = intval($_POST['year'] ?? 0);
    $mileage = trim($_POST['mileage'] ?? '');
    $fuel    = trim($_POST['fuel'] ?? '');
    $trans   = trim($_POST['transmission'] ?? '');
    $price   = floatval($_POST['price'] ?? 0);

    if (empty($title) || empty($make) || empty($model) || $price <= 0) {
        $error = "Please fill all required fields correctly.";
    } else {
        // ============================
        // Save uploaded images
        // ============================
        $images = [];
        $uploadDir = '../uploads/listings/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['tmp_name'] as $i => $tmpName) {
                $ext = pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION);
                $newFile = $uploadDir . uniqid() . '.' . $ext;
                if (move_uploaded_file($tmpName, $newFile)) {
                    // Save relative path for DB & browser
                    $images[] = 'uploads/listings/' . basename($newFile);
                }
            }
        }

        // JSON encode without escaping slashes
        $images_json = json_encode($images, JSON_UNESCAPED_SLASHES);

        // ============================
        // Insert listing into DB
        // ============================
        $stmt = $conn->prepare("INSERT INTO listings 
            (dealer_id, title, make, model, year, mileage, fuel_type, transmission, price, images, status, created_at)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,NOW())");

        $dealer_id = $_SESSION['dealer_id'];
        $status    = 'pending';

        $stmt->bind_param("ississssdss", 
            $dealer_id, $title, $make, $model, $year, $mileage, $fuel, $trans, $price, $images_json, $status
        );

        if ($stmt->execute()) {
            $success = "Listing submitted successfully! Waiting for admin approval.";
        } else {
            $error = "Database insert failed: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Add Listing</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
body { font-family: Arial, sans-serif; background:#f8f9fa; padding:20px; }
.container { max-width:700px; margin:0 auto; }
.card { padding:15px; border-radius:8px; margin-bottom:15px; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
.form-row { margin-bottom:12px; }
input, select, button { width:100%; padding:8px; border-radius:6px; border:1px solid #ccc; }
button { background:#4e73df; color:#fff; border:none; cursor:pointer; transition:0.3s; }
button:hover { background:#2e59d9; }
</style>
</head>
<body class="container">
<h2>Add New Car Listing</h2>

<?php if(!empty($error)): ?>
<div class="card" style="background:#ffe6e6;"><?php echo e($error); ?></div>
<?php endif; ?>

<?php if(!empty($success)): ?>
<div class="card" style="background:#e6ffe6;"><?php echo e($success); ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
<div class="form-row">
    <input type="text" name="title" placeholder="Title (e.g., Toyota Corolla 2018)" required>
</div>
<div class="form-row">
    <input type="text" name="make" placeholder="Make" required>
</div>
<div class="form-row">
    <input type="text" name="model" placeholder="Model" required>
</div>
<div class="form-row">
    <input type="number" name="year" placeholder="Year" min="1900" max="2100" required>
</div>
<div class="form-row">
    <input type="text" name="mileage" placeholder="Mileage (km)" required>
</div>
<div class="form-row">
    <select name="fuel" required>
        <option value="">Fuel Type</option>
        <option value="Petrol">Petrol</option>
        <option value="Diesel">Diesel</option>
        <option value="Hybrid">Hybrid</option>
        <option value="EV">EV</option>
    </select>
</div>
<div class="form-row">
    <select name="transmission" required>
        <option value="">Transmission</option>
        <option value="Automatic">Automatic</option>
        <option value="Manual">Manual</option>
    </select>
</div>
<div class="form-row">
    <input type="number" name="price" placeholder="Price (AED)" step="0.01" required>
</div>
<div class="form-row">
    <label>Upload Images (Multiple allowed)</label>
    <input type="file" name="images[]" multiple accept="image/*">
</div>
<button type="submit">Submit Listing</button>
</form>
</body>
</html>
