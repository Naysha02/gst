<?php
    global $pdo;
    if($_SERVER['REQUEST_METHOD']==='POST'){
        $email = $_POST['email']; $password = $_POST['password'];
        $hash = hash('sha256', $password);
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email=? AND password=? LIMIT 1');
        $stmt->execute([$email,$hash]);
        $u = $stmt->fetch();
        if($u){
            if($u['approved']==0){ echo '<p>Your account is pending admin approval.</p>'; return; }
            $_SESSION['user'] = $u;
            header('Location: index.php?page=dealer_dashboard'); exit;
        } else echo '<p>Invalid credentials.</p>';
    }
    ?>
    <h2>Dealer Login</h2>
    <form method="post">
        <label>Email: <input name="email" type="email" required /></label><br/>
        <label>Password: <input name="password" type="password" required/></label><br/>
        <button>Login</button>
    </form>
    