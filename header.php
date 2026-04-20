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
        :root { --neon: #00ff88; --gold: #ffdf1b; --dark: #0a0f0d; }
        body { background: var(--dark); color: white; font-family: 'Segoe UI', sans-serif; margin: 0; padding-bottom: 85px; }
        
        .top-nav { 
            background: linear-gradient(180deg, #073128, #000); 
            display: flex; justify-content: space-between; 
            padding: 10px 15px; align-items: center; 
            border-bottom: 2px solid var(--neon); 
            position: sticky; top: 0; z-index: 1000; height: 60px; 
        }

        .logo { font-size: 18px; font-weight: 900; color: var(--neon); text-shadow: 0 0 10px var(--neon); font-style: italic; }
        
        .nav-right { display: flex; align-items: center; gap: 8px; }

        /* অফার বাটন স্টাইল */
        .btn-offer {
            background: transparent; color: var(--neon); border: 1px solid var(--neon);
            padding: 6px 10px; border-radius: 5px; font-size: 11px; font-weight: bold; cursor: pointer;
        }

        .balance-box { 
            background: rgba(0,255,136,0.1); padding: 5px 12px; border-radius: 20px; 
            border: 1px solid var(--neon); color: var(--gold); font-weight: bold; cursor: pointer; font-size: 13px;
        }

        .btn-auth { border: none; padding: 8px 12px; border-radius: 5px; font-weight: 900; font-size: 11px; cursor: pointer; text-transform: uppercase; }
        .btn-login { background: var(--gold); color: black; }
        .btn-reg { background: #333; color: white; border: 1px solid #444; }
    </style>
</head>
<body>
    <header class="top-nav">
        <div class="logo">BETLOVER777</div>
        <div class="nav-right">
            <!-- অফার বাটন সবসময় থাকবে -->
            <button class="btn-offer" onclick="location.href='offer.php'">OFFER</button>

            <?php if($isLoggedIn): ?>
                <div class="balance-box" onclick="location.href='wallet.php'">৳ <?php echo number_format($userBalance, 2); ?></div>
                <button class="btn-reg" onclick="location.href='logout.php'" style="padding: 7px 10px;"><i class="fa-solid fa-power-off"></i></button>
            <?php else: ?>
                <button class="btn-auth btn-login" onclick="openLogin()">LOGIN</button>
                <button class="btn-auth btn-reg" onclick="openRegister()">REGISTER</button>
            <?php endif; ?>
        </div>
    </header>
