<?php
session_start();
include 'db.php';

// ১. সেশন থেকে ইউজার চেক
$u = $_SESSION['username'] ?? $_SESSION['user_id'] ?? '';

// ২. ডাটাবেস থেকে তথ্য আনা
$query = $conn->query("SELECT * FROM users WHERE username = '$u' OR id = '$u'");
$user_data = $query->fetch_assoc();

// ৩. ডেমো গেম লিঙ্ক
$game_url = "https://2048.org";
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

        <select id="active_wallet" class="wallet-select" onchange="alert('আপনি ' + this.value + ' ওয়ালেট সিলেক্ট করেছেন। এটি ডেমো মোডে আছে।')">
            <option value="main">Main: ৳<?php echo number_format($user_data['balance'] ?? 0, 0); ?></option>
            <option value="pb">PB: ৳<?php echo number_format($user_data['pb_balance'] ?? 0, 0); ?></option>
            <option value="bonus">Bonus: ৳<?php echo number_format($user_data['bonus_balance'] ?? 0, 0); ?></option>
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

    <div class="game-footer">
        <div style="margin-bottom: 10px;">
<button onclick="placeTestBet()" id="play_btn" style="background: #00ff88; color: #000; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; cursor: pointer; text-transform: uppercase; width: 100%;">
    🎯 PLAY / BET
</button>


</div>
        <a href="bet_history.php" class="history-btn">📜 VIEW BET HISTORY</a>
    </div>
<script>
function placeTestBet() {
    const wallet = document.getElementById('active_wallet').value;
    const btn = document.getElementById('play_btn');
    const amount = 10; 

        btn.disabled = true;
    btn.innerText = "PROCESSING...";

    let formData = new FormData();
    formData.append('amount', amount);
    formData.append('wallet', wallet);

    fetch('place_bet.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            alert("বাজি সফল! আপনার ব্যালেন্স আপডেট করা হয়েছে।");
            location.reload(); 
        } else {
            alert("এরর: " + data.message);
            btn.disabled = false;
            btn.innerText = "🎯 PLACE BET";
        }
    })
    .catch(err => {
        console.log(err);
        alert("কানেকশন এরর! place_bet.php ফাইলটি চেক করুন।");
        btn.disabled = false;
        btn.innerText = "🎯 PLACE BET";
    });

    .catch(err => {
        alert("কানেকশন এরর! place_bet.php ফাইলটি চেক করুন।");
        btn.disabled = false;
        btn.innerText = "🎯 PLACE BET";
    });

    .catch(err => {
        console.error(err);
        alert("কানেকশন এরর! গিটহাবে place_bet.php ফাইলটি নেই অথবা বানান ভুল আছে।");
        btn.disabled = false;
        btn.innerText = "🎯 PLACE BET";
    });
}


</script>

</body>
</html>
            
