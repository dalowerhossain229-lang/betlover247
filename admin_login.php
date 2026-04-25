<?php
session_start();
include 'db.php';

if(isset($_POST['login'])){
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];
    
    $res = $conn->query("SELECT * FROM admin WHERE username='$user'");
    if($res->num_rows > 0){
        $row = $res->fetch_assoc();
        if(password_verify($pass, $row['password'])){
            $_SESSION['admin_logged_in'] = true;
            header("Location: admin_panel.php"); // আপনার অ্যাডমিন প্যানেলের নাম দিন
            exit;
        }
    }
    $error = "ভুল ইউজারনেম বা পাসওয়ার্ড!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
        body { background: #0a0f0d; color: white; font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: #111; padding: 30px; border-radius: 15px; border: 1px solid #00ff88; box-shadow: 0 0 20px rgba(0,255,136,0.2); width: 300px; text-align: center; }
        input { width: 100%; padding: 12px; margin: 10px 0; background: #000; border: 1px solid #333; color: #00ff88; border-radius: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #00ff88; border: none; color: #000; font-weight: bold; border-radius: 8px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>🔒 Admin Login</h2>
        <?php if(isset($error)) echo "<p style='color:red; font-size:12px;'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">LOGIN</button>
        </form>
    </div>
</body>
</html>
