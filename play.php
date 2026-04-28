<?php
// ১. সেশন এবং ডাটাবেস কানেকশন
session_start();
include 'db.php';

// ২. ইউজার লগইন চেক
$u = $_SESSION['username'] ?? $_SESSION['user_id'] ?? '';
if (empty($u)) {
    header("Location: index.php");
    exit();
}

// ৩. ডাটাবেস থেকে ইউজারের সব ব্যালেন্স আনা
$query = $conn->query("SELECT * FROM users WHERE username = '$u' OR id = '$u'");
$user_data = $query->fetch_assoc();

// ৪. আপনার গেম এপিআই ইউআরএল (এখানে আপনার গেম লিঙ্কটি বসবে)
$game_url = "https://your-api-link.com"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Play Game - BetLover247</title>
    <style>
        body { margin: 0; padding: 0; background: #000; font-family: sans-serif; overflow: hidden; color: #fff; }
        
        /* গেম হেডার ডিজাইন */
        .game-header { 
            background: #000; 
            height: 55px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 0 15px; 
            border-bottom: 1px solid #00ff88; 
            box-sizing: border-box;
        }

        .back-btn { 
            background: #00ff88; 
            color: #000; 
            text-decoration: none; 
            padding: 6px 12px; 
            border-radius: 5px; 
            font-weight: bold; 
            font-size: 12px; 
            text-transform: uppercase;
        }

        .wallet-select { 
            background: #111; 
            color: #ffdf1b; 
            border: 1px solid #333; 
            padding: 8px; 
            border-radius: 6px; 
            font-weight: bold; 
            font-size: 14px; 
            outline: none;
            max-width: 160px;
        }

        .user-name { 
            font-size: 12px; 
            color: #888; 
            text-align: right; 
            font-weight: bold;
        }

        /* গেম এরিয়া (Iframe) */
        .game-frame-container { 
            width: 100%; 
            height: calc(100vh - 95px); 
            background: #111; 
        }

        /* ফুটার হিস্ট্রি বাটন */
        .game-footer { 
            background: #000; 
            height: 40px; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            border-top: 1px solid #222; 
        }

        .history-link { 
            color: #00ff88; 
            text-decoration: none; 
            font-size: 13px; 
            font-weight: bold;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>

    <!-- হেডার: ব্যাক বাটন, ৩টি ব্যালেন্স এবং ইউজারনেম -->
    <div class="game-header">
        <a href="index.php" class="back-btn">⬅ BACK</a>

        <select id="active_wallet" class="wallet-select" onchange="switchWallet(this.value)">
            <option value="main">Main: ৳<?php echo number_format($user_data['balance'], 0); ?></option>
            <option value="pb">PB: ৳<?php echo number_format($user_data['pb_balance'], 0); ?></option>
            <option value="bonus">Bonus: ৳<?php echo number_format($user_data['bonus_balance'], 0); ?></option>
        </select>

        <div class="user-name">
            <small style="display:block; font-size:9px; color:#555;">PLAYER</small>
            <?php echo $u; ?>
        </div>
    </div>

    <!-- গেম কন্টেইনার (এখানে গেম লোড হবে) -->
    <div class="game-frame-container">
        <iframe src="<?php echo $game_url; ?>" id="game_frame" style="width: 100%; height: 100%; border: none;"></iframe>
    </div>

    <!-- ফুটার: বেট হিস্টোরি -->
    <div class="game-footer">
        <a href="bet_history.php" class="history-link">📜 VIEW GAME HISTORY</a>
    </div>

    <script>
        function switchWallet(walletType) {
            // ইউজারের সিলেক্ট করা ওয়ালেট টাইপ সেভ করা বা গেমে পাঠানো
            console.log("Selected Wallet: " + walletType);
            
            // আপনি যদি চান ডাটাবেসকে জানাতে যে ইউজার এখন এই ওয়ালেট দিয়ে খেলবে, তবে এখানে AJAX ব্যবহার করতে পারেন
            // fetch('api_callback.php?update_wallet=' + walletType);
        }
    </script>

</body>
</html>
