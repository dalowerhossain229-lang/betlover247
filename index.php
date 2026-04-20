<?php
session_start();
include 'db.php';
// ডাটাবেস থেকে এজেন্ট নম্বর টেনে আনা
$sSet = $conn->query("SELECT * FROM site_settings WHERE id=1");
$settings = $sSet->fetch_assoc();
$bk_no = $settings['bkash_no'] ?? '01700000000';
$ng_no = $settings['nagad_no'] ?? '01800000000';

$isLoggedIn = false;
$userBalance = 0; // এখানে ৫০০ লিখে রাখলে তা কখনোই আপডেট হবে না
$userName = "";

// চেক করা ইউজার কি লগইন অবস্থায় আছে?
if (isset($_SESSION['user_id'])) {
    $isLoggedIn = true;
    $user_id = $_SESSION['user_id'];

    // ডাটাবেস থেকে ইউজারের লেটেস্ট ব্যালেন্স এবং নাম আনা
    $result = $conn->query("SELECT full_name, balance, status FROM users WHERE username = '$user_id'");
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // --- এই ২টো লাইন সবথেকে জরুরি ---
        $userBalance = $row['balance']; // ডাটাবেসের টাটকা টাকা ভেরিয়েবলে রাখা
        $userName = $row['full_name'];  // ইউজারের আসল নাম রাখা
        
        // ইউজার ব্যান কি না চেক করা
        if ($row['status'] == 'banned') {
            session_destroy();
            header("Location: index.php?error=banned");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BETLOVER24 | Pro Master v6.1</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com">
    <style>
        :root { --primary-green:#073128 ; --bet-yellow: #ffdf1b; --dark-bg: #0a0a0a; --card-bg: #1a1a1a; --text-white: #ffffff; --error-color: #d32f2f; }
        body { background-color: var(--dark-bg); color: var(--text-white); font-family: 'Segoe UI', sans-serif; margin: 0; padding-bottom: 90px; overflow-x: hidden; }
        .top-nav { background: var(--primary-green); display: flex; justify-content: space-between; padding: 12px 15px; align-items: center; position: sticky; top: 0; z-index: 1000; height: 60px; box-shadow: 0 2px 10px rgba(0,0,0,0.5); }
        .logo { font-size: 20px; font-weight: 900; color: var(--bet-yellow); font-style: italic; }
        .auth-buttons { display: flex; gap: 8px; }
        .auth-btn { padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: bold; cursor: pointer; border: none; }
        .login-btn { background: var(--bet-yellow); color: #000; }
        .reg-btn { background: #000; color: #fff; border: 1px solid #333; }
        .user-balance-area { display: none; align-items: center; gap: 10px; }
        .balance-chip { background: rgba(0,0,0,0.3); padding: 6px 12px; border-radius: 20px; font-size: 13px; color: var(--bet-yellow); border: 1px solid rgba(255,223,27,0.2); cursor: pointer; }
/* স্লাইডারের মেইন কন্টেইনার */
.slider-container { 
    margin: 15px auto 5px auto; 
    border-radius: 12px; 
    overflow: hidden; 
    height: 95px; 
    width: 94%; 
    border: 1px solid #14362a; 
    position: relative; 
    background: #0e2f22; 
}

/* স্লাইডার: সব স্লাইডকে এক লাইনে রাখার মূল কোড */
.slider { 
    display: flex !important; 
    flex-wrap: nowrap !important; /* বক্সগুলো নিচে নামতে দেবে না */
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1); 
    width: 500% !important; /* ৫টি বক্স তাই ৫০০% */
    height: 100%; 
}

/* প্রতিটি স্লাইড বক্স (সবুজ মেট গ্রিন) */
.slide { 
    width: 20% !important; /* ৫০০% এর ৫ ভাগের ১ ভাগ */
    flex-shrink: 0 !important; /* বক্স ছোট হতে দেবে না */
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    padding: 0 20px; 
    box-sizing: border-box; 
    position: relative; 
    overflow: hidden; 
    background: #0e2f22; 
}

/* স্লাইডারের ভেতরের টেক্সট ও ব্যাজ স্টাইল */
.slide-text h3 { margin: 0; font-size: 16px; color: var(--bet-yellow); text-transform: uppercase; font-weight: 900; }
.slide-text p { margin: 2px 0 0; font-size: 10px; opacity: 0.9; color: #fff; }
.slide-badge { position: absolute; top: 15px; right: -25px; background: var(--bet-yellow); color: #000; font-size: 10px; font-weight: 900; padding: 4px 30px; transform: rotate(35deg); box-shadow: 0 2px 5px rgba(0,0,0,0.5); text-transform: uppercase; white-space: nowrap; }
.bg-icon { position: absolute; right: 40px; font-size: 35px; opacity: 0.15; color: #fff; z-index: 1; }
/* নতুন নোটিশ বোর্ড স্টাইল */
.winning-ticker { 
    background: #07221b; /* ডিপ মেট গ্রিন */
    color: var(--bet-yellow); 
    font-size: 12px; 
    padding: 8px 0; 
    border-top: 1px solid #14362a; 
    border-bottom: 1px solid #14362a; 
    overflow: hidden; 
    margin-bottom: 15px;
    display: flex;
    align-items: center;
}

.ticker-text { 
    display: inline-block; 
    white-space: nowrap;
    padding-left: 100%; 
    animation: notice-scroll 20s linear infinite; 
}

@keyframes notice-scroll { 
    0% { transform: translateX(0); } 
    100% { transform: translateX(-100%); } 
}

.ticker-text span { 
    margin-right: 50px; 
    font-weight: bold;
    color: var(--bet-yellow);
}
/* ড্রয়িং অনুযায়ী ৪ কলামের গ্রিড */
.game-grid { 
    display: grid; 
    grid-template-columns: repeat(4, 1fr); 
    gap: 8px; 
    padding: 0 10px; 
}
.game-card { 
    background-color: #121212; 
    height: 100px; /* উচ্চতা কিছুটা বাড়ানো হয়েছে */
    border-radius: 10px; 
    position: relative; /* ভেতরের ছবিকে বর্ডারের সাথে আটকাতে */
    display: flex; 
    flex-direction: column; 
    align-items: center; 
    justify-content: flex-end; /* লেখাটি নিচে রাখার জন্য */
    border: 1px solid #14362a; 
    text-align: center; 
    overflow: hidden; /* ছবি যাতে বক্সের বাইরে না যায় */
    cursor: pointer;
}

/* ছবি পুরো বক্স দখল করবে */
.game-card img { 
    width: 100%; 
    height: 100%; 
    object-fit: cover; /* ছবি বক্সের পুরোটা ভরাট করবে */
    position: absolute; 
    top: 0; 
    left: 0; 
    z-index: 1; /* ছবি নিচে থাকবে */
}

/* গেমের নাম (ছবির ওপরে ভাসমান থাকবে) */
.game-card p { 
    width: 100%;
    background: rgba(0, 0, 0, 0.7); /* হালকা কালো শ্যাডো যাতে লেখা পড়া যায় */
    color: #ffdf1b; 
    font-size: 9px; 
    font-weight: 900; 
    text-transform: uppercase; 
    margin: 0;
    padding: 5px 0;
    z-index: 2; /* লেখা ছবির ওপরে থাকবে */
}


/* বিশেষ সার্চ বক্স স্টাইল */
.search-card {
    background: #000;
    border: 1px dashed #ffdf1b;
}

.search-input {
    width: 85%;
    background: transparent;
    border: none;
    color: #fff;
    font-size: 9px;
    text-align: center;
    outline: none;
}

        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.92); display: none; justify-content: center; align-items: center; z-index: 3000; backdrop-filter: blur(8px); }
        .modal-content { background: var(--card-bg); width: 92%; max-width: 400px; padding: 25px; border-radius: 20px; border: 1px solid var(--primary-green); max-height: 85vh; overflow-y: auto; position: relative; }
        .modal-header { display: flex; justify-content: space-between; margin-bottom: 20px; align-items: center; }
        .modal-input { width: 100%; padding: 14px; background: #000; border: 1px solid #333; border-radius: 12px; color: white; margin-bottom: 12px; outline: none; font-size: 14px; box-sizing: border-box; }
        .modal-label { display: block; font-size: 11px; color: #888; margin-bottom: 5px; margin-left: 5px; }
        .submit-btn { width: 100%; padding: 15px; background: var(--primary-green); color: white; border: none; border-radius: 12px; font-weight: bold; cursor: pointer; }
        .footer-nav { position: fixed; bottom: 0; width: 100%; background: var(--primary-green); display: flex; justify-content: space-around; align-items: center; height: 75px; z-index: 2000; box-shadow: 0 -2px 10px rgba(0,0,0,0.5); }
.nav-tab {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    text-align: center;
}

.nav-tab span {
    color: #ffdf1b !important; /* সরাসরি হলুদ হেক্স কোড */
    text-transform: uppercase !important; /* সব বড় হাতের অক্ষর */
    font-size: 10px !important; /* অক্ষরগুলো একটু ছোট করা হয়েছে */
    font-weight: 900;
    letter-spacing: 0.5px;
    display: block;
}



        .promo-card-box { background: #0c0c0c; padding: 20px; border-radius: 15px; margin-bottom: 15px; border: 1px solid #1a1a1a; cursor: pointer; text-align: left; }
        .promo-card-box h4 { margin: 0 0 10px 0; color: var(--bet-yellow); font-size: 14px; font-weight: bold; }
        .promo-card-box p { margin: 0; font-size: 12px; color: #eee; line-height: 1.5; }
        .prof-item { padding: 15px; border-bottom: 1px solid #222; font-size: 14px; display: flex; justify-content: space-between; align-items: center; }
        .prof-link { padding: 15px; border-bottom: 1px solid #222; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 10px; color: #eee; }
/* লোগো এবং সব বাটনকে এক লাইনে সমান করার জন্য */
.top-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 10px;
    height: 60px;
}

.logo {
    font-size: 15px !important;
    font-weight: 900;
    color: var(--bet-yellow);
    margin-right: 5px;
}

/* অফার, লগইন ও রেজিস্টার - সব বাটন সমান উচ্চতার হবে */
.auth-btn, .offer-btn-top {
    height: 32px; /* সব বাটনের উচ্চতা সমান */
    padding: 0 10px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: bold;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-transform: uppercase;
    border: none;
    cursor: pointer;
    box-sizing: border-box;
}

/* অফার বাটন স্টাইল */
.offer-btn-top {
    background: transparent;
    color: var(--bet-yellow);
    border: 1px solid var(--bet-yellow);
    margin-right: auto; /* লোগোর পাশে রেখে বাকিদের ডানে ঠেলে দেবে */
    margin-left: 8px;
}

/* লগইন বাটন (হলুদ) */
.login-btn {
    background: var(--bet-yellow);
    color: #000;
    margin-right: 5px;
}

/* রেজিস্টার বাটন (কালো) */
.reg-btn {
    background: #000;
    color: #fff;
    border: 1px solid #333;
}
    </style>
</head>
<body onclick="handleGlobalClick()">
    <header class="top-nav">
        <div class="logo">BETLOVER24</div>
    <button class="offer-btn-top">OFFER</button>
    
        <div id="authSection" class="auth-buttons">
            <button class="auth-btn login-btn" onclick="openLogin()">Login</button>
            <button class="auth-btn reg-btn" onclick="openRegister()">Register</button>
        </div>
        <div id="balanceSection" class="user-balance-area">
            <div class="balance-chip" onclick="openWallet()">৳ <span id="topBalance">500.00</span></div>
            <button class="auth-btn reg-btn" onclick="handleLogout()" style="padding: 6px 10px;"><i class="fa-solid fa-power-off"></i></button>
        </div>
    </header>

    <div class="slider-container">
        <div class="slider" id="mainSlider">
            <div class="slide" style="background: linear-gradient(45deg, #8b0000, #000);"><div class="slide-text"><h3>Insurance</h3><p>লস রিকভারি পলিসি।</p></div><div class="slide-badge">নিরাপদ</div><i class="fa-solid fa-shield-halved bg-icon"></i></div>
            <div class="slide" style="background: linear-gradient(45deg, #004d39, #000);"><div class="slide-text"><h3>Investment</h3><p>১০% মাসিক লাভ।</p></div><div class="slide-badge">লাভ নিন</div><i class="fa-solid fa-chart-line bg-icon"></i></div>
            <div class="slide" style="background: linear-gradient(45deg, #4b0082, #000);"><div class="slide-text"><h3>Affiliate</h3><p>কমিশন আয় করুন।</p></div><div class="slide-badge">আয় হবেই</div><i class="fa-solid fa-users bg-icon"></i></div>
            <div class="slide" style="background: linear-gradient(45deg, #b8860b, #000);"><div class="slide-text"><h3>Sudden Bonus</h3><p>ইনস্ট্যান্ট ক্যাশ।</p></div><div class="slide-badge">এখনই</div><i class="fa-solid fa-bolt bg-icon"></i></div>
            <div class="slide" style="background: linear-gradient(45deg, #001f3f, #000);"><div class="slide-text"><h3>Health Card</h3><p>চিকিৎসা সহায়তায় সুবিধা।</p></div><div class="slide-badge">চিকিৎসা</div><i class="fa-solid fa-notes-medical bg-icon"></i></div>
        </div>
    </div>

<div class="winning-ticker">
    <div class="ticker-text" id="winningTicker">
        <!-- অ্যাডমিন প্যানেল থেকে আসা নোটিশ এখানে শো করবে -->
        <span>স্বাগতম BETLOVER24-এ! আমাদের নতুন অফার পেতে এখনই ডিপোজিট করুন। যেকোনো সমস্যায় কাস্টমার সার্ভিসে যোগাযোগ করুন।</span>
    </div>
</div>


<div class="game-grid">
    <!-- সারি ১ -->
<!-- ৩১৩ নম্বর লাইনে এই কোডটি বসান -->
<div class="game-card" onclick="window.location.href='superace/'">
    <img src="super-ace.png">
    <p>Super Ace</p>
</div>

    <div class="game-card"><img src="chicken-road.png"><p>Chicken road</p></div>
    <div class="game-card"><img src="lottery.png"><p>Lottery</p></div>
    <div class="game-card search-card">
        <input type="text" placeholder="Search..." class="search-input">
    </div>

    <!-- সারি ২ -->
<div class="game-card" onclick="window.location.href='games/wheel/wheel.php'">
    <img src="wheel.png">
    <p>Wheel</p>
</div>

    <div class="game-card"><img src="better-ace.png"><p>Better Ace</p></div>
    <div class="game-card"><img src="lucky-slot.png"><p>Lucky slot</p></div>
    <div class="game-card"><img src="cricket.png"><p>Cricket</p></div>

    <!-- সারি ৩ -->
    <div class="game-card"><img src="football.png"><p>Football</p></div>
    <div class="game-card"><img src="crazy-time.png"><p>Crazy time</p></div>
    <div class="game-card"><img src="super-wheel.png"><p>Super wheel</p></div>
    <div class="game-card"><img src="ludu.png"><p>Ludu</p></div>

    <!-- সারি ৪ -->
    <div class="game-card"><img src="aviator.png"><p>Aviator</p></div>
    <div class="game-card"><img src="mega-ace.png"><p>Mega Ace</p></div>
    <div class="game-card">
    <a href="https://onrender.com<?php echo isset($user['username']) ? $user['username'] : ''; ?>" style="display:block; text-decoration:none; color:inherit;">
        <img src="lucky-777.png">
        <p>Lucky 777</p>
    </a>
</div>



    <div class="game-card"><img src="super-ace-2.png"><p>Super Ace-2</p></div>
</div>




    



<!-- History Modal -->
<div id="historyModal" class="modal-overlay">
    <div class="modal-content" style="max-width:400px;">
        <div class="modal-header">
            <span style="font-weight:bold; color:var(--bet-yellow);">Transaction History</span>
            <i class="fa-solid fa-times" onclick="closeModal('historyModal')"></i>
        </div>
        <div style="overflow-y:auto; max-height:350px; margin-top:10px;">
            <table style="width:100%; border-collapse:collapse; font-size:11px; color:#fff;">
                <tr style="background:#222; text-align:left;">
                    <th style="padding:10px;">Type</th>
                    <th style="padding:10px;">Amount</th>
                    <th style="padding:10px;">Status</th>
                </tr>
                <?php
                if($isLoggedIn) {
                    $uID = $_SESSION['user_id'];
                    $hRes = $conn->query("SELECT type, amount, status FROM transactions WHERE user_id='$uID' ORDER BY id DESC LIMIT 15");
                    while($hRow = $hRes->fetch_assoc()) {
                        $st = $hRow['status'];
                        $color = ($st == 'approved') ? '#4caf50' : (($st == 'rejected') ? '#f44336' : '#ffa500');
                        echo "<tr style='border-bottom:1px solid #333;'>
                            <td style='padding:10px;'>".strtoupper($hRow['type'])."</td>
                            <td style='padding:10px;'>৳{$hRow['amount']}</td>
                            <td style='padding:10px; color:$color; font-weight:bold;'>".ucfirst($st)."</td>
                        </tr>";
                    }
                }
                ?>
            </table>
        </div>
    </div>
</div>

    <div id="profileModal" class="modal-overlay">
        <div class="modal-content" style="padding: 0;">
            <div class="modal-header" style="padding: 20px; margin: 0; border-bottom: 1px solid #222;"><span style="font-weight: bold; color: var(--bet-yellow);">My Account</span><i class="fa-solid fa-times" onclick="closeModal(`profileModal`)"></i></div>
<div class="prof-item"><span>User ID:</span><span style="color:var(--bet-yellow)"><?php echo $username; ?></span></div>
<div class="prof-item"><span>Balance:</span><span style="color:var(--bet-yellow)">৳ <span id="profBal"><?php echo $userBalance; ?></span></span></div>

<div class="prof-link" onclick="openModal('historyModal')"><i class="fa-solid fa-clock-rotate-left"></i> Activity</div>

            <div class="prof-link" onclick="openSettings()"><i class="fa-solid fa-gear"></i> Settings</div>
            <div class="prof-link" onclick="handleLogout(); closeModal(`profileModal`)" style="color: var(--error-color);"><i class="fa-solid fa-power-off"></i> Logout</div>
        </div>
    </div>

<!-- Settings Modal -->
<div id="settingsModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <span style="font-weight:bold; color:var(--bet-yellow);">Settings</span>
            <i class="fa-solid fa-times" onclick="closeModal('settingsModal')"></i>
        </div>
        
        <p style="font-size:12px; color:#888;">বিকাশ/নগদ নম্বর যোগ করুন (Admin Approval Needed):</p>
        <input type="text" id="setMethod" class="modal-input" placeholder="Method (e.g. Bkash/Nagad)">
        <input type="text" id="setNumber" class="modal-input" placeholder="Account Number">
        <button class="submit-btn" onclick="saveSettings('number')" style="margin-bottom:20px;">Add Number</button>

        <p style="font-size:12px; color:#888;">পাসওয়ার্ড পরিবর্তন:</p>
        <input type="password" id="newPass" class="modal-input" placeholder="New Password">
        <button class="submit-btn" style="background:var(--error-color)" onclick="saveSettings('pass')">Update Password</button>
    </div>
</div>


    <div id="walletModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <div style="display:flex; gap:15px;">
<span id="tabDep" style="color:var(--bet-yellow); font-weight:bold; cursor:pointer;" onclick="switchWalletTab('dep')">Deposit</span>
<span id="tabWith" style="color:#888; font-weight:bold; cursor:pointer;" onclick="switchWalletTab('with')">Withdraw</span>
</div>
<i class="fa-solid fa-times" onclick="closeModal('walletModal')"></i>
</div>
<div id="depSection">
    <select id="depMethod" class="modal-input" onchange="updateDepNumber()">
        <option value="bkash">Bkash Agent</option>
        <option value="nagad">Nagad Agent</option>
    </select>
    
    <div style="background:#111;padding:12px;border-radius:10px;border:1px dashed var(--bet-yellow);text-align:center;margin-bottom:15px;margin-top:10px;">
        <p id="depMethodText" style="font-size:10px;color:#888;margin:0;">Cash Out to Bkash Agent:</p>
<h4 id="depNumber" style="color:var(--bet-yellow);margin:5px 0;"><?php echo $bk_no; ?></h4>
    </div>

    <input type="number" id="depAmount" class="modal-input" placeholder="Amount">
    <input type="text" id="depTrxId" class="modal-input" placeholder="TrxID">
    <button class="submit-btn" onclick="submitDeposit()">Submit Deposit</button>
</div>

<div id="withSection" style="display:none;">
<select class="modal-input">
<option value="bkash_p">Bkash Personal</option>
<option value="nagad_p">Nagad Personal</option>
</select>
<input type="text" id="withPhone" class="modal-input" placeholder="Withdraw Number">
<input type="number" id="withAmount" class="modal-input" placeholder="Amount">
<button class="submit-btn" style="background:var(--error-color)" onclick="submitWithdraw()">Withdraw Request</button>
</div>
</div>
</div>
<div id="loginModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <span style="font-weight:bold; color:var(--bet-yellow);">Login</span>
            <i class="fa-solid fa-times" onclick="closeModal('loginModal')"></i>
        </div>
        
        <!-- ইউজার আইডি ইনপুট -->
        <input type="text" id="loginID" class="modal-input" placeholder="User ID">
        
        <!-- পাসওয়ার্ড ইনপুট -->
        <input type="password" id="loginPass" class="modal-input" placeholder="Password">
        
        <!-- নতুন ফরগেট পাসওয়ার্ড বাটন (নিখুঁত এলাইনমেন্টসহ) -->
        <div style="text-align: right; margin-top: -8px; margin-bottom: 15px; padding-right: 5px;">
            <a href="forget-password.php" style="color: var(--bet-yellow); font-size: 11px; text-decoration: none; font-weight: bold; opacity: 0.9;">Forget Password?</a>
        </div>
        
        <!-- লগইন বাটন (ডাটা কানেকশন ঠিক রাখা হয়েছে) -->
        <button class="submit-btn" onclick="handleLogin()">Login Now</button>
    </div>
</div>


    <div id="registerModal" class="modal-overlay"><div class="modal-content"><div class="modal-header"><span style="font-weight:bold; color:var(--bet-yellow);">Register</span><i class="fa-solid fa-times" onclick="closeModal(`registerModal`)"></i></div><input type="text" id="regFullName" class="modal-input" placeholder="Full Name"><input type="text" id="regPhone" class="modal-input" placeholder="Mobile / Email"><input type="password" id="regPass" class="modal-input" placeholder="Password"><input type="password" id="regConfirmPass" class="modal-input" placeholder="Confirm PW"><button class="submit-btn" onclick="handleRegister()">Register</button></div></div>

<div id="promoModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <span style="font-weight:bold; color:var(--bet-yellow);">Promotions</span>
            <i class="fa-solid fa-times" onclick="closeModal('promoModal')"></i>
        </div>
<!-- ১. Investment কার্ড -->
<div class="promo-card-box" onclick="openDynamicForm('investment')">
    <h4>Investment</h4>
    <p>১০% মাসিক লাভ।</p>
</div>

<!-- ২. Health Card কার্ড -->
<div class="promo-card-box" onclick="openDynamicForm('health')">
    <h4>Health Card</h4>
    <p>চিকিৎসা সহায়তায় সুবিধা।</p>
</div>

<!-- ৩. Insurance কার্ড -->
<div class="promo-card-box" onclick="openDynamicForm('insurance')">
    <h4>Insurance</h4>
    <p>আপনার বিনিয়োগ নিরাপদ রাখুন।</p>
</div>

<!-- ৪. Sudden Bonus কার্ড -->
<div class="promo-card-box" onclick="openDynamicForm('bonus')">
    <h4>Sudden Bonus</h4>
    <p>ইনস্ট্যান্ট ক্যাশ বোনাস পান।</p>
</div>

<!-- ৫. Affiliate কার্ড -->
<div class="promo-card-box" onclick="openDynamicForm('affiliate')">
    <h4>Affiliate System</h4>
    <p>রেফার করে কমিশন আয় করুন।</p>
</div>

</div>
</div>

<div id="dynamicFormModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <span id="formTitle" style="font-weight:bold; color:var(--bet-yellow);">Form</span>
            <i class="fa-solid fa-times" onclick="closeModal('dynamicFormModal')"></i>
        </div>
        <div id="formContent"></div> <!-- এখানে ইনপুটগুলো অটোমেটিক আসবে -->
        
        <!-- এই বাটনটিই এখন আপনার ৫টি পয়েন্টের তথ্য ডাটাবেসে পাঠাবে -->
        <button type="button" class="submit-btn" onclick="submitPromoForm()">Submit Request</button>
    </div>
</div>


<footer class="footer-nav">
    <div class="nav-tab active" onclick="location.reload()"><span>Home</span></div>
    
    <!-- নতুন বাটন: Sports -->
    <div class="nav-tab"><span>Sports</span></div>
    
    <div class="nav-tab" onclick="openPromo()"><span>Promo</span></div>
    
    <!-- নতুন বাটন: Casino -->
    <div class="nav-tab"><span>Casino</span></div>
    
    <div class="nav-tab" onclick="openProfile()"><span>Account</span></div>
</footer>

<script>
 function openPromo() {
    document.getElementById('promoModal').style.display = 'flex';
}
// ১. লগইন স্ট্যাটাস সরাসরি ডাটাবেস (PHP) থেকে আসবে
let isLoggedIn = <?php echo ($isLoggedIn) ? 'true' : 'false'; ?>;

// ২. ব্যালেন্স সরাসরি ডাটাবেস (PHP) থেকে আসবে (৫০০ টাকার ঝামেলা শেষ!)
let balance = <?php echo floatval($userBalance); ?>;

    function updateBalanceUI() { 
        const bText = balance.toFixed(2);
        if(document.getElementById('topBalance')) document.getElementById('topBalance').innerText = bText; 
        if(document.getElementById('profBal')) document.getElementById('profBal').innerText = bText;
        localStorage.setItem('userBalance', balance); 
    }
function openSettings() {
    closeModal('profileModal');
    document.getElementById('settingsModal').style.display = 'flex';
}

function saveSettings(type) {
    let details = "";
    if(type === 'number') {
        const method = document.getElementById('setMethod').value;
        const num = document.getElementById('setNumber').value;
        if(!method || !num) { alert("সব ঘর পূরণ করুন!"); return; }
        details = "Add Number - Method: " + method + " | Number: " + num;
    } else {
        const pass = document.getElementById('newPass').value;
        if(!pass) { alert("নতুন পাসওয়ার্ড দিন!"); return; }
        details = "Password Change Request - New Pass: " + pass;
    }

    let fd = new FormData();
    fd.append('promo_type', 'Account Update');
    fd.append('details', details);

    // আমরা আমাদের তৈরি করা প্রোমোশন ফাইলটিই এখানে ব্যবহার করছি ডাটাবেসে সেভ করতে
    fetch('process_promo.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.status === 'success') closeModal('settingsModal');
    })
    .catch(() => alert("সার্ভার এরর!"));
}
function handleGameBet(amount, gameName) {
    if(!isLoggedIn) { openLogin(); return; }
    
    // ডাটাবেস থেকে লিঙ্ক আনার লজিক
    fetch('get_game_link.php?name=' + gameName)
    .then(res => res.json())
    .then(data => {
        if(data.link) {
            // পপ-আপ ব্লকার এড়াতে window.open ব্যবহার করা হলো
            const newWindow = window.open(data.link, '_blank');
            if (!newWindow || newWindow.closed || typeof newWindow.closed == 'undefined') { 
                alert("দয়া করে আপনার ব্রাউজারের Pop-up এলাও করুন।"); 
            }
        } else {
            alert(gameName + " গেমটি মেইনটেন্যান্সে আছে।");
        }
    });
}

    function updateAuthUI() { 
        document.getElementById('authSection').style.display = isLoggedIn ? 'none' : 'flex'; 
        document.getElementById('balanceSection').style.display = isLoggedIn ? 'flex' : 'none'; 
    }
function updateDepNumber() {
    const method = document.getElementById('depMethod').value;
    const text = document.getElementById('depMethodText');
    const num = document.getElementById('depNumber');

    if (method === 'bkash') {
        text.innerText = `Cash Out to Bkash Agent:`;
num.innerText = '<?php echo $bk_no; ?>';
    } else if (method === 'nagad') {
        text.innerText = `Cash Out to Nagad Agent:`;
num.innerText = '<?php echo $ng_no; ?>';
    }
}

    // মোডাল কন্ট্রোল
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }
    function openLogin() { document.getElementById('loginModal').style.display = 'flex'; }
    function openRegister() { document.getElementById('registerModal').style.display = 'flex'; }
    function openProfile() { if(!isLoggedIn) openLogin(); else document.getElementById('profileModal').style.display = 'flex'; }
    function openWallet() { if(!isLoggedIn) openLogin(); else document.getElementById('walletModal').style.display = 'flex'; }

    // রেজিস্ট্রেশন লজিক
    function handleRegister() {
        const name = document.getElementById('regFullName').value;
        const phone = document.getElementById('regPhone').value; 
        const pass = document.getElementById('regPass').value;
        const cPass = document.getElementById('regConfirmPass').value;

        if(!name || !phone || !pass || pass !== cPass) { 
            alert("সবগুলো তথ্য সঠিকভাবে দিন!"); return; 
        }

        let fd = new FormData();
        fd.append('fullName', name); 
        fd.append('username', phone); 
        fd.append('password', pass);

        fetch('register.php', { method: 'POST', body: fd })
        .then(res => res.json())
        .then(data => { 
            alert(data.message); 
            if(data.status === 'success') { 
                closeModal('registerModal'); openLogin(); 
            } 
        })
        .catch(() => alert("রেজিস্ট্রেশন সার্ভার কানেকশন এরর!"));
    }

    // লগইন লজিক
    function handleLogin() {
        const id = document.getElementById('loginID').value;
        const pass = document.getElementById('loginPass').value;
        if(!id || !pass) { alert("আইডি ও পাসওয়ার্ড দিন!"); return; }
        
        let fd = new FormData();
        fd.append('username', id); 
        fd.append('password', pass);

        fetch('login_proc.php', { method: 'POST', body: fd })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                isLoggedIn = true; 
                localStorage.setItem('isLoggedIn', 'true');
                updateAuthUI(); 
                closeModal('loginModal'); 
                location.reload();
            } else { alert(data.message); }
        }).catch(() => alert("লগইন সার্ভার কানেকশন এরর!"));
    }

function handleLogout() {
    // ১. ব্রাউজারের পুরনো মেমোরি ডিলিট করা
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('userBalance');
    
    // ২. সার্ভার থেকে লগআউট করা
    window.location.href = 'logout.php';
}


    // উইন্ডো লোড
    window.onload = function() { 
        updateAuthUI(); 
        updateBalanceUI(); 
        // স্লাইডার ও টি্যাকার লজিক এখানে থাকবে...
    };
// ডিপোজিট ফাংশন
function submitDeposit() {
    const amount = document.getElementById('depAmount').value;
    const trx = document.getElementById('depTrxId').value;
    const method = document.getElementById('depMethod').value;

    if (!amount || !trx) { alert("সবগুলো ঘর পূরণ করুন!"); return; }

    let fd = new FormData();
    fd.append('method', method);
    fd.append('amount', amount);
    fd.append('trxId', trx);

    fetch('process_deposit.php', {
        method: 'POST',
        body: fd,
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.status === 'success') {
            closeModal('walletModal');
            location.reload(); // ব্যালেন্স রিফ্রেশ হবে
        }
    })
    .catch(() => alert("ডিপোজিট সার্ভার এরর! process_deposit.php ফাইলটি চেক করুন।"));
}

function switchWalletTab(type) {
    const isDep = (type === 'dep');
    const depSec = document.getElementById('depSection');
    const withSec = document.getElementById('withSection');
    const tabD = document.getElementById('tabDep');
    const tabW = document.getElementById('tabWith');

    if (depSec && withSec) {
        depSec.style.display = isDep ? 'block' : 'none';
        withSec.style.display = isDep ? 'none' : 'block';
    }

    if (tabD && tabW) {
        tabD.style.color = isDep ? 'var(--bet-yellow)' : '#888';
        tabW.style.color = isDep ? '#888' : 'var(--bet-yellow)';
    }
}
function openDynamicForm(type) {
    if(!isLoggedIn) { openLogin(); return; }
    const content = document.getElementById('formContent');
    const title = document.getElementById('formTitle');
    let html = "";

    if(type === 'investment') { 
        title.innerText = "Fixed Investment"; 
        html = `<input type="number" placeholder="পরিমাণ" class="modal-input" required><input type="text" placeholder="পেমেন্ট মেথড" class="modal-input" required>`; 
    }
    else if(type === 'health') { 
        title.innerText = "Health Card Service"; 
        html = `<input type="text" placeholder="User ID" class="modal-input" required><input type="text" placeholder="সমস্যার বিবরণ" class="modal-input" required>`; 
    }
    else if(type === 'insurance') { 
        title.innerText = "Insurance System"; 
        html = `<input type="text" placeholder="User ID" class="modal-input" required><input type="text" placeholder="NID নম্বর" class="modal-input" required>`; 
    }
    else if(type === 'bonus') { 
        title.innerText = "Sudden Bonus"; 
        html = `<input type="text" placeholder="আপনার আইডি দিন" class="modal-input" required><p style="font-size:10px; color:gray;">অ্যাডমিন যাচাই করে বোনাস দিবে।</p>`; 
    }
    else if(type === 'affiliate') { 
        title.innerText = "Affiliate Program"; 
        html = `<input type="text" placeholder="আপনার রেফারেল নাম" class="modal-input" required><input type="text" placeholder="লিংক পেতে আপনার তথ্য দিন" class="modal-input" required>`; 
    }

    content.innerHTML = html;
    closeModal('promoModal'); // আগের লিস্ট বন্ধ হবে
    document.getElementById('dynamicFormModal').style.display = 'flex'; // ফর্ম খুলবে
}

function saveSettings(type) {
    let methodVal = "";
    let numberVal = "";
    let passVal = "";
    let details = "";

    if (type === 'number') {
        methodVal = document.getElementById('setMethod').value;
        numberVal = document.getElementById('setNumber').value;
        if (!methodVal || !numberVal) { alert("বিকাশ/নগদ পদ্ধতি এবং নম্বর দিন!"); return; }
        details = "Add Number Request - Method: " + methodVal + " | Number: " + numberVal;
    } else if (type === 'pass') {
        passVal = document.getElementById('newPass').value;
        if (!passVal) { alert("নতুন পাসওয়ার্ডটি দিন!"); return; }
        details = "Password Change Request - New Password: " + passVal;
    }

    let fd = new FormData();
    fd.append('promo_type', 'Account Update'); // এটি ডাটাবেসে টাইপ হিসেবে থাকবে
    fd.append('details', details);

    fetch('process_promo.php', { 
        method: 'POST', 
        body: fd,
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.status === 'success') closeModal('settingsModal');
    })
    .catch(() => alert("সার্ভার এরর! process_promo.php চেক করুন।"));
}

function submitWithdraw() {
    const phoneInput = document.getElementById(`withPhone`);
    const amountInput = document.getElementById(`withAmount`);

    if (!phoneInput || !amountInput || !phoneInput.value || !amountInput.value) { 
        alert(`মোবাইল নম্বর এবং টাকার পরিমাণ সঠিকভাবে দিন!`); 
        return; 
    }

    let formData = new FormData();
    formData.append(`phone`, phoneInput.value);
    formData.append(`amount`, amountInput.value);

    fetch(`process_withdraw.php`, { 
        method: `POST`, 
        body: formData,
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.status === `success`) {
            phoneInput.value = "";
            amountInput.value = "";
            closeModal(`walletModal`);
            location.reload(); 
        }
    })
    .catch(() => alert(`উইথড্র সার্ভার এরর! process_withdraw.php ফাইলটি চেক করুন।`));
}
function submitPromoForm() {
    const title = document.getElementById('formTitle').innerText;
    const inputs = document.querySelectorAll('#formContent input');
    let details = "";
    
    // সব ইনপুট বক্স থেকে তথ্যগুলো এক লাইনে আনা
    inputs.forEach(input => {
        if(input.value) {
            details += input.placeholder + ": " + input.value + " | ";
        }
    });

    if(!details) { 
        alert(`সবগুলো তথ্য সঠিকভাবে দিন!`); 
        return; 
    }

    let fd = new FormData();
    fd.append('promo_type', title);
    fd.append('details', details);

    fetch('process_promo.php', { 
        method: 'POST', 
        body: fd,
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.status === 'success') {
            closeModal('dynamicFormModal');
        }
    })
    .catch(() => alert(`প্রোমোশন সার্ভার এরর! process_promo.php ফাইলটি চেক করুন।`));
}
// মোডাল ওপেন করার ফাংশন
function openModal(id) {
    const modal = document.getElementById(id);
    if(modal) {
        modal.style.display = 'flex'; // টেবিলটি স্ক্রিনে দেখাবে
    } else {
        console.error("মোডাল আইডি পাওয়া যায়নি: " + id);
    }
}

// মোডাল বন্ধ করার ফাংশন
function closeModal(id) {
    const modal = document.getElementById(id);
    if(modal) {
        modal.style.display = 'none'; // টেবিলটি লুকিয়ে ফেলবে
    }
}
    // স্লাইডার সচল করার সহজ লজিক
    (function() {
        let count = 0;
        // স্লাইডার এলিমেন্টটি ধরুন
        const sliderEl = document.querySelector('.slider');
        
        if (!sliderEl) return; // যদি স্লাইডার না পাওয়া যায় তবে কোড চলবে না

        function slideNext() {
            count++;
            if (count >= 5) {
                count = 0;
            }
            // ২০% করে বামে সরাবে (৫টি স্লাইডের জন্য)
            sliderEl.style.transform = "translateX(-" + (count * 20) + "%)";
        }

        // প্রতি ৩ সেকেন্ড পর পর স্লাইড হবে
        setInterval(slideNext, 3000);
    })();
</script>
</body>
</html>
