<?php
ob_start();
session_start();
include 'db.php';

// ১. সেশন থেকে ইউজার চেক
$u = $_SESSION['username'] ?? $_SESSION['user_id'] ?? '';

if (empty($u)) {
    header("Location: index.php");
    exit();
}

// ২. ডাটাবেস থেকে তথ্য আনা
$query = $conn->query("SELECT * FROM users WHERE username = '$u' OR id = '$u'");
$user_data = $query->fetch_assoc();

// ৩. গেম লোড লিঙ্ক (৩টি ওয়ালেট ডাইনামিক সিঙ্কের জন্য আপডেট করা হয়েছে)
$aviator_base_url = "https://aviator2-0-azym.onrender.com"; 
$active_wallet = $user_data['active_wallet'] ?? 'main';
$game_url = $aviator_base_url . "?userId=" . urlencode($u) . "&wallet=" . urlencode($active_wallet);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Demo Game Test - BetLover247</title>
    <style>
        body { margin: 0; padding: 0; background: #000; font-family: sans-serif; overflow: hidden; color: #fff; }
        
        /* হেডার ডিজাইন */
        .game-header { 
            background: #000; 
            height: 55px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 0 10px; 
            border-bottom: 2px solid #00ff88; 
            box-sizing: border-box;
        }

        .back-btn { 
            background: #00ff88; 
            color: #000; 
            text-decoration: none; 
            padding: 8px 12px; 
            border-radius: 5px; 
            font-weight: bold; 
            font-size: 12px; 
        }

        .wallet-select { 
            background: #111; 
            color: #ffdf1b; 
            border: 1px solid #333; 
            padding: 8px; 
            border-radius: 6px; 
            font-weight: bold; 
            font-size: 14px; 
            flex-grow: 1;
            margin: 0 10px;
            outline: none;
        }

        /* গেম এরিয়া */
        .game-container { 
            width: 100%; 
            height: calc(100vh - 100px); 
            background: #111; 
        }

        /* ফুটার */
        .game-footer { 
            background: #000; 
            height: 45px; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            border-top: 1px solid #333;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .history-btn { 
            color: #00ff88; 
            text-decoration: none; 
            font-size: 13px; 
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="game-header">
        <a href="index.php" class="back-btn">⬅ BACK</a>
        <a href="bet_logs.php" class="back-btn" style="background: #111; color: #ffdf1b; border: 1px solid #333; margin-left: 5px; text-decoration: none;">📜 HISTORY</a>

<select id="active_wallet" onchange="updateWallet(this.value)" style="background: #000; color: #ffdf1b; border: 1px solid #333; padding: 5px; border-radius: 5px; font-weight: bold; outline: none;">
    <?php
    // ডাটাবেস থেকে সব ব্যালেন্স একবারে নিয়ে আসা
    $u_id = $_SESSION['user_id'];
    $st = $conn->query("SELECT balance, pb_balance, bonus_balance, active_wallet FROM users WHERE username = '$u_id'")->fetch_assoc();
    $act = $st['active_wallet'] ?? 'main';
    ?>
    <option value="main" <?php if($act == 'main') echo 'selected'; ?>>Main: ৳<?php echo number_format($st['balance'], 2); ?></option>
    <option value="pb" <?php if($act == 'pb') echo 'selected'; ?>>PB: ৳<?php echo number_format($st['pb_balance'], 2); ?></option>
    <option value="bonus" <?php if($act == 'bonus') echo 'selected'; ?>>Bonus: ৳<?php echo number_format($st['bonus_balance'], 2); ?></option>
</select>
        <div style="font-size: 11px; color: #888; text-align: right;">
            <small style="display:block; font-size:8px;">PLAYER</small>
            <?php echo $u; ?>
        </div>
    </div>

    <div class="game-container">
        <!-- এই আইফ্রেমে গেম লোড হবে -->
        <iframe 
            src="<?php echo $game_url; ?>" 
            id="game_frame" 
            style="width: 100%; height: 100%; border: none;"
            allow="autoplay; fullscreen; gaming">
        </iframe>
    </div>
</div>
<script>
// 🎰 ১. থিমের নিজস্ব ওয়ালেট সিলেকশন রিফ্রেশার
function updateWallet(walletType) {
    fetch('update_wallet.php?type=' + walletType)
    .then(() => {
        location.reload();
    });
}

// 🎯 ২. এভিয়েটর গেম থেকে আসা লাইভ বাজি ধরার সিগন্যাল লিসেনার
window.addEventListener("message", function(event) {
    if (event.data && event.data.action === "refresh_wallet") {
        // বাজি ধরা বা জেতার সাথে সাথে আইফ্রেমের দেয়াল ভেঙে মেইন পেজ রিফ্রেশ করে ব্যালেন্স সিঙ্ক করবে
        location.reload();
    }
});
</script>





</body>
</html>
            
