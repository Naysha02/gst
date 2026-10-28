<?php
    global $pdo;
    if($_SERVER['REQUEST_METHOD']==='POST'){
        $name = $_POST['name']; $email = $_POST['email']; $password = $_POST['password']; $company = $_POST['company'] ?? '';
        // create user (dealer) - not production secure password hashing simplified for prototype
        $hash = hash('sha256', $password);
        $pdo->prepare('INSERT INTO users (name,email,password,role,approved) VALUES (?,?,?,?,0)')->execute([$name,$email,$hash,'dealer']);
        $uid = $pdo->lastInsertId();
        $pdo->prepare('INSERT INTO dealers (user_id,company_name) VALUES (?,?)')->execute([$uid,$company]);
        echo '<p>Registered. Waiting for admin approval.</p>';
        return;
    }
    ?>
    <h2>Dealer Register</h2>
    <form method="post">
        <label>Name: <input name="name" required /></label><br/>
        <label>Email: <input name="email" type="email" required /></label><br/>
        <label>Password: <input name="password" type="password" required/></label><br/>
        <label>Company: <input name="company" /></label><br/>
        <button>Register</button>
    </form>
    