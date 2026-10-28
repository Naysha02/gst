<?php
require_once '../functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // check duplicate email
    $check = $conn->prepare("SELECT id FROM customers WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Email already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO customers (name,email,password,phone) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $name, $email, $password, $phone);
        if ($stmt->execute()) {
            $_SESSION['customer'] = $email;
            redirect("profile.php");
        } else {
            $error = "Registration failed!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Customer Signup</title></head>
<body>
<h2>Customer Signup</h2>
<?php if(!empty($error)) echo "<p style='color:red'>$error</p>"; ?>
<form method="post">
  <input type="text" name="name" placeholder="Full Name" required><br>
  <input type="email" name="email" placeholder="Email" required><br>
  <input type="text" name="phone" placeholder="Phone"><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <button type="submit">Register</button>
</form>
</body>
</html>
