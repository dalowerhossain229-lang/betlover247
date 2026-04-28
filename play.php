<?php
session_start();
include 'db.php';

// ১. সেশন থেকে ইউজার চেক
$u = $_SESSION['username'] ?? $_SESSION['user_id'] ?? '';
if (empty($u)) {
    header("Location: index.php");
    exit();
}

// ২. ডাটাবেস থেকে ইউজারের সব ব্যালেন্স আনা
$query = $conn->query("SELECT * FROM users WHERE username = '$u' OR id = '$u'");
$user_data = $query->fetch_assoc();

// ৩. অ্যাডমিন প্যানেল থেকে গেম লিঙ্ক আনা (আপনার সেটিংস টেবিল অনুযায়ী)
$game_url = "https://google.com"; // এখানে আপনার আসল API বা ডেমো গেম লিঙ্কটি বসান
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
            padding: 0 10px; 
            border-bottom: 2px solid #222; 
            box-sizing: border-box;
        }

        .back-btn { 
            background: #00ff88; 
            color: #000; 
            text-decoration: none; 
            padding: 8px 15px; 
            border-radius: 5px; 
            font-weight: bold; 
            font-size: 13px; 
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
            flex-grow: 1;
            margin: 0 10px;
            text-align: center;
        }

        .user-name { 
            font-size: 11px; 
            color: #888; 
            text-align: right; 
            min-width: 60px;
        }

        /* গেম কন্টেইনার */
        .game-area { 
            width: 100%; 
            height: calc(100vh - 100px); 
            background: #1a1a1a; 
            position: relative;
        }

        /* ফুটার হিস্ট্রি বাটন */
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

        .history-link { 
            color: #00ff88; 
            text-decoration: none; 
            font-size: 14px; 
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body>

    <!-- হেডার সেকশন -->
    <div class="game-header">
        <a href="index.php" class="back-btn">⬅ BACK</a>

        <select id="active_wallet" class="wallet-select" onchange="switchWallet(this.value)">
            <option value="main">Main: ৳<?php echo number_format($user_data['balance'], 0); ?></option>
            <option value="pb">PB: ৳<?php echo number_format($user_data['pb_balance'], 0); ?></option>
            <option value="bonus">Bonus: ৳<?php echo number_format($user_data['bonus_balance'], 0); ?></option>
        </select>

        <div class="user-name">
            <small style="display:block; font-size:8px; color:#555;">PLAYER</small>
            <?php echo $u; ?>
        </div>
    </div>

    <!-- গেম এরিয়া (Iframe) -->
    <div class="game-area">
        <iframe 
            src="<?php echo $game_url; ?>" 
            id="game_frame" 
            style="width: 100%; height: 100%; border: none;"
            allow="autoplay; fullscreen; gaming"
            sandbox="allow-forms allow-modals allow-orientation-lock allow-pointer-lock allow-popups allow-popups-to-escape-sandbox allow-scripts allow-same-origin">
        </iframe>
    </div>

    <!-- ফুটার সেকশন -->
    <div class="game-footer">
        <a href="bet_history.php" class="history-link">
            📜 VIEW BET HISTORY
        </a>
    </div>

    <script>
        function switchWallet(walletType) {
            console.log("Selected Wallet: " + walletType);
            // এখানে আপনি চাইলে AJAX ব্যবহার করে সার্ভারে ওয়ালেট টাইপ আপডেট করতে পারেন
        }
    </script>

</body>
</html>
