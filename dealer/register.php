<?php
require_once '../functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$name || !$email || !$password) {
        $error = "Please fill required fields.";
    } else {
        // check exists
        $stmt = $conn->prepare("SELECT id FROM dealers WHERE email = ?");
        $stmt->bind_param('s',$email); $stmt->execute(); $stmt->store_result();
        if ($stmt->num_rows) {
            $error = "Email already registered.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt2 = $conn->prepare("INSERT INTO dealers (name,email,password,phone) VALUES (?,?,?,?)");
            $stmt2->bind_param('ssss',$name,$email,$hash,$phone);
            if ($stmt2->execute()) {
                $success = "Registered. Await admin approval.";
            } else $error = "Registration failed.";
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Dealer Register</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body class="container">
    <h2>Dealer Register</h2>
    <?php if(!empty($error)): ?><div class="card" style="background:#ffe6e6;"><?php echo e($error); ?></div><?php endif; ?>
    <?php if(!empty($success)): ?><div class="card" style="background:#e6ffe6;"><?php echo e($success); ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-row"><input type="text" name="name" placeholder="Business name" required></div>
        <div class="form-row"><input type="email" name="email" placeholder="Email" required></div>
        <div class="form-row"><input type="text" name="phone" placeholder="Phone"></div>
        <div class="form-row"><input type="password" name="password" placeholder="Password" required></div>
        <button type="submit">Register</button>
    </form>
    <p><a href="login.php">Login</a></p>
</body>
</html>
