<?php
session_start();
require_once '../db.php';       // ✅ DB connection
require_once '../functions.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $u = trim($_POST['username']);
    $p = md5($_POST['password']); // ❗ For production, use password_hash()

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username=? AND password=? LIMIT 1");
    $stmt->bind_param("ss", $u, $p);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin'] = $row['username'];

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "❌ Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #4e73df, #1cc88a);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .login-card {
      width: 380px;
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      background: #fff;
      padding: 30px;
      animation: fadeIn 0.7s ease-in-out;
    }
    .login-card h3 {
      font-weight: 600;
      color: #4e73df;
    }
    .form-control {
      border-radius: 12px;
      padding: 12px;
    }
    .btn-primary {
      border-radius: 12px;
      font-weight: 500;
      transition: 0.3s;
    }
    .btn-primary:hover {
      background-color: #2e59d9;
      transform: scale(1.03);
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="login-card">
    <h3 class="text-center mb-3">🔐 Admin Login</h3>
    <?php if(isset($error)) echo "<div class='alert alert-danger text-center'>$error</div>"; ?>
    <form method="POST">
      <div class="mb-3">
        <input type="text" name="username" class="form-control" placeholder="👤 Username" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="🔑 Password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </div>
</body>
</html>
