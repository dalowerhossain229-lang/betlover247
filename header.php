<?php
session_start();
include 'db.php';
$isLoggedIn = isset($_SESSION['user_id']);
$userBalance = 0.00;

if ($isLoggedIn) {
    $uid = $_SESSION['user_id'];
    $res = $conn->query("SELECT balance FROM users WHERE username = '$uid'");
    if ($res && $row = $res->fetch_assoc()) {
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
    <link rel="stylesheet" href="https://cloudflare.com">

    <style>
        :root { --neon: #00ff88; --gold: #ffdf1b; --dark: #0a0f0d; }
        body { background: var(--dark); color: white; font-family: 'Segoe UI', sans-serif; margin: 0; padding-bottom: 85px; }
        
        .top-nav { 
            background: linear-gradient(180deg, #073128, #000); 
            display: flex; justify-content: space-between; 
            padding: 0 10px; align-items: center; 
            border-bottom: 2px solid var(--neon); 
            position: sticky; top: 0; z-index: 1000; height: 60px; 
            box-shadow: 0 0 15px rgba(0,255,136,0.2);
        }

        .logo { font-size: 15px !important; font-weight: 900; color: var(--neon); text-shadow: 0 0 10px var(--neon); font-style: italic; white-space: nowrap; }
        
        .nav-right { display: flex; align-items: center; gap: 5px; }

        /* বাটনগুলোর কমন ডিজাইন */
        .btn-header {
            height: 30px; padding: 0 8px; border-radius: 4px; 
            font-size: 10px; font-weight: 900; cursor: pointer; 
            display: flex; align-items: center; justify-content: center;
            text-transform: uppercase; border: none; white-space: nowrap;
        }

        .btn-offer-top { background: transparent; color: var(--neon); border: 1px solid var(--neon); }
        .btn-login-top { background: var(--gold); color: black; }
        .btn-reg-top { background: #333; color: white; border: 1px solid #444; }

        /* ব্যালেন্স চিপ ডিজাইন */
        .balance-chip-top { 
            background: rgba(0,255,136,0.1); padding: 0 10px; height: 30px;
            border-radius: 20px; border: 1px solid var(--neon); 
            color: var(--gold); font-weight: bold; font-size: 11px;
            display: flex; align-items: center; cursor: pointer;
        }
.logout-icon { 
    background: #ff4d4d !important; 
    color: white !important; 
    width: 30px; 
    height: 30px; 
    border-radius: 4px; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    cursor: pointer; 
    border: none; 
}

    </style>
</head>
<body>
    <header class="top-nav">
        <div class="logo">BETLOVER777</div>
        
        <div class="nav-right">
            <!-- অফার বাটন সবসময় থাকবে -->
            <button class="btn-header btn-offer-top" onclick="location.href='offer.php'">OFFER</button>

            <?php if($isLoggedIn): ?>
                <!-- লগইন থাকলে ব্যালেন্স এবং লগআউট -->
                <div class="balance-chip-top" onclick="location.href='wallet.php'">
                    ৳ <?php echo number_format($userBalance, 2); ?>
                </div>
                <button class="logout-icon" onclick="location.href='logout.php'">
                    <i class="fa-solid fa-power-off"></i>
                </button>
            <?php else: ?>
                <!-- লগইন না থাকলে লগইন ও রেজিস্টার -->
                <button class="btn-header btn-login-top" onclick="openLogin()">LOGIN</button>
                <button class="btn-header btn-reg-top" onclick="openRegister()">REGISTER</button>
            <?php endif; ?>
        </div>
    </header>
