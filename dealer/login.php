<?php
require_once '../functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, password, status, verified FROM dealers WHERE email=? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $row = $res->fetch_assoc()) {
        $id = $row['id'];
        $hash = $row['password'];
        $status = $row['status'];
        $verified = $row['verified'];

        if (password_verify($password, $hash)) {
            if ($status !== 'approved') {
                $error = "Account not approved by admin yet. Status: " . e($status);
            } else {
                $_SESSION['dealer_id'] = $id;
                $_SESSION['dealer_email'] = $email;
                redirect('dashboard.php');
            }
        } else {
            $error = "Invalid credentials.";
        }
    } else {
        $error = "No account found with this email.";
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dealer Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="container">
    <h2>Dealer Login</h2>
    <?php if (!empty($error)): ?>
        <div class="card" style="background:#ffe6e6;"><?php echo e($error); ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-row">
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="form-row">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</body>
</html>
