<?php
session_start();
include 'db.php';
$isLoggedIn = isset($_SESSION['user_id']);
$userBalance = 0.00;

if ($isLoggedIn) {
    $uid = $_SESSION['user_id'];
    $res = $conn->query("SELECT balance FROM users WHERE username = '$uid'");
    if ($row = $res->fetch_assoc()) {
        $userBalance = $row['balance'];
        $_SESSION['balance'] = $userBalance;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BETLOVER777 | Win Big</title>
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        :root { --neon: #00ff88; --gold: #ffdf1b; --dark: #0a0f0d; --card: #161b22; }
        body { background: var(--dark); color: white; font-family: 'Segoe UI', sans-serif; margin: 0; padding-bottom: 85px; }
        .top-nav { background: linear-gradient(180deg, #073128, #000); display: flex; justify-content: space-between; padding: 10px 15px; align-items: center; border-bottom: 2px solid var(--neon); box-shadow: 0 0 15px rgba(0,255,136,0.3); position: sticky; top: 0; z-index: 1000; height: 55px; }
        .logo { font-size: 20px; font-weight: 900; color: var(--neon); text-shadow: 0 0 10px var(--neon); font-style: italic; }
        .balance-box { background: rgba(0,255,136,0.1); padding: 5px 15px; border-radius: 20px; border: 1px solid var(--neon); color: var(--gold); font-weight: bold; cursor: pointer; }
        .btn-auth { background: var(--gold); color: black; border: none; padding: 7px 15px; border-radius: 5px; font-weight: 900; font-size: 12px; cursor: pointer; }
    </style>
</head>
<body>
    <header class="top-nav">
        <div class="logo">BETLOVER777</div>
        <div class="nav-right">
            <?php if($isLoggedIn): ?>
                <div class="balance-box" onclick="location.href='wallet.php'">৳ <?php echo number_format($userBalance, 2); ?></div>
            <?php else: ?>
                <button class="btn-auth" onclick="openLogin()">LOGIN</button>
            <?php endif; ?>
        </div>
    </header>
