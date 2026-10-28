<?php
    global $pdo;
    if($_SERVER['REQUEST_METHOD']==='POST'){
        $email=$_POST['email']; $pass=$_POST['password'];
        $hash = hash('sha256',$pass);
        $stmt=$pdo->prepare('SELECT * FROM users WHERE email=? AND password=? AND role="admin" LIMIT 1');
        $stmt->execute([$email,$hash]); $u=$stmt->fetch();
        if($u){ $_SESSION['user']=$u; header('Location: index.php?page=admin_dashboard'); exit; }
        else echo '<p>Invalid admin credentials.</p>';
    }
    ?>
    <h2>Admin Login</h2>
    <form method="post">
      <label>Email: <input name="email" type="email" required /></label><br/>
      <label>Password: <input name="password" type="password" required /></label><br/>
      <button>Login</button>
    </form>
    