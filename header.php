<?php
// ১. সেশন এবং ব্যালেন্স চেক (সব পেজের শুরুতে থাকতে হবে)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

$isLoggedIn = isset($_SESSION['user_id']);
$userBalance = 0.00;

if ($isLoggedIn) {
    $uid = $_SESSION['user_id'];
    $res = $conn->query("SELECT balance FROM users WHERE username = '$uid'");
    if ($res && $row = $res->fetch_assoc()) {
        $userBalance = (float)$row['balance'];
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
    <!-- আইকন দেখার জন্য Font Awesome লিঙ্ক -->
    <link rel="stylesheet" href="https://cloudflare.com">
    
    <style>
        :root { --neon: #00ff88; --gold: #ffdf1b; --dark: #0a0f0d; }
        body { background: var(--dark); color: white; font-family: 'Segoe UI', sans-serif; margin: 0; padding-bottom: 85px; }
        
        /* টপ নেভিগেশন ডিজাইন */
        .top-nav { 
    background: linear-gradient(180deg, #073128, #000); 
    display: flex; 
    justify-content: space-between; 
    padding: 0 8px; 
    align-items: center; 
    border-bottom: 2px solid var(--neon); 
    position: sticky; 
    top: 0; 
    z-index: 1000; 
    height: 55px !important; /* উচ্চতা কমিয়ে ফিক্সড করা হলো */
    box-shadow: 0 0 15px rgba(0,255,136,0.2);
}

.logo { 
    font-size: 14px !important; /* ১৫ থেকে ১৪ করা হলো */
    font-weight: 900; 
    color: var(--neon); 
    text-shadow: 0 0 10px var(--neon); 
    font-style: italic; 
    white-space: nowrap; 
}

        
        
        .nav-right { display: flex; align-items: center; gap: 8px; }

        /* বাটন ডিজাইন */
        /* হেডারের উচ্চতা ফিক্সড করা */
.top-nav { 
    background: linear-gradient(180deg, #073128, #000); 
    display: flex; 
    justify-content: space-between; 
    padding: 0 8px; 
    align-items: center; 
    border-bottom: 2px solid var(--neon); 
    position: sticky; 
    top: 0; 
    z-index: 1000; 
    height: 55px !important; /* উচ্চতা কমিয়ে ফিক্সড করা হলো */
    box-shadow: 0 0 15px rgba(0,255,136,0.2);
}

/* বাটনগুলোর সাইজ একটু ছোট করা যাতে ফোনের স্ক্রিনে ধরে যায় */
.btn-header {
    height: 28px !important; /* উচ্চতা কমানো হলো */
    padding: 0 6px !important; 
    border-radius: 4px; 
    font-size: 9px !important; /* ফন্ট সাইজ একটু ছোট করা হলো */
    font-weight: 900; 
    cursor: pointer; 
    display: flex; 
    align-items: center; 
    justify-content: center;
    text-transform: uppercase; 
    border: none; 
    white-space: nowrap; /* লেখা যাতে ভেঙে নিচে না নামে */
}


        .btn-offer-top { background: transparent; color: var(--neon); border: 1.5px solid var(--neon); }
        .btn-offer-top:hover { background: rgba(0,255,136,0.1); }
        
        .btn-login-top { background: var(--gold); color: black; box-shadow: 0 0 10px rgba(255,223,27,0.3); }
        .btn-reg-top { background: #222; color: white; border: 1px solid #444; }

        /* ব্যালেন্স চিপ ডিজাইন */
        .balance-chip-top { 
            background: rgba(0,255,136,0.15); padding: 0 12px; height: 34px;
            border-radius: 20px; border: 1.5px solid var(--neon); 
            color: var(--gold); font-weight: 900; font-size: 12px;
            display: flex; align-items: center; cursor: pointer;
            box-shadow: inset 0 0 5px rgba(0,255,136,0.2);
        }

        /* লগআউট আইকন */
        .logout-icon { 
            background: #ff4d4d !important; 
            color: white !important; 
            width: 34px; height: 34px; 
            border-radius: 6px; display: flex; 
            align-items: center; justify-content: center; 
            cursor: pointer; border: none; font-size: 16px;
        }
        .logout-icon:hover { background: #ff1a1a !important; }

    </style>
</head>
<body>
    <header class="top-nav">
        <div class="logo">BETLOVER777</div>
        
        <div class="nav-right">
            <!-- অফার বাটন -->
            <button class="btn-header btn-offer-top" onclick="openOffer()">OFFER</button>

            <?php if($isLoggedIn): ?>
                <div class="balance-chip-top" onclick="showWalletMenu()">
    ৳ <?php echo number_format($userBalance, 2); ?>
</div>

<div id="walletMenu" style="display:none; position:absolute; top:60px; right:55px; background:#073128; border:2px solid var(--neon); border-radius:10px; z-index:10000; flex-direction:column; width:140px; box-shadow: 0 8px 25px rgba(0,0,0,0.6); overflow:hidden;">
    <div onclick="openDeposit()" style="padding:15px; border-bottom:1px solid rgba(0,255,136,0.2); cursor:pointer; font-weight:900; color:var(--neon); text-align:center; font-size:12px; transition:0.3s;">
        <i class="fa-solid fa-plus-circle"></i> DEPOSIT
    </div>
    <div onclick="openWithdraw()" style="padding:15px; cursor:pointer; font-weight:900; color:#ff4d4d; text-align:center; font-size:12px; transition:0.3s;">
        <i class="fa-solid fa-minus-circle"></i> WITHDRAW
    </div>
</div>


                <!-- লগআউট বাটন -->
                <button class="logout-icon" onclick="handleLogout()">
                    <i class="fa-solid fa-power-off"></i>
                </button>
            <?php else: ?>
                <!-- লগইন না থাকলে -->
                <button class="btn-header btn-login-top" onclick="openLogin()">LOGIN</button>
                <button class="btn-header btn-reg-top" onclick="openRegister()">REGISTER</button>
            <?php endif; ?>
        </div>
    </header>
