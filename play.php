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

    <div class="game-footer">
        <div style="margin-bottom: 10px;">
<button onclick="placeTestBet()" id="play_btn" style="background: #00ff88; color: #000; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; cursor: pointer; text-transform: uppercase; width: 100%;">
    🎯 PLAY / BET
</button>


</div>
        <a href="bet_history.php" class="history-btn">📜 VIEW BET HISTORY</a>
    </div>
<!-- ১৩৬ নম্বর লাইন থেকে শুরু করুন -->
<div class="card" style="background: #111; padding: 10px; border-radius: 12px; border: 1px solid #333; margin: 20px 10px; overflow: hidden;">
    <div onclick="toggleBox('betHistoryBox', 'betHistIcon')" style="cursor:pointer; display:flex; justify-content:space-between; align-items:center; padding:10px;">
        <h4 style="color:#00ff88; margin:0; font-size:15px;">📜 My Bet History</h4>
        <span id="betHistIcon" style="color:#888;">▼</span>
    </div>
    
    <div id="betHistoryBox" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; padding: 0 10px;">
        <div style="padding: 15px 0; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 11px; text-align: center;">
                <tr style="color: #888; border-bottom: 1px solid #333;">
                    <th style="padding: 10px;">সময়</th>
                    <th>পরিমাণ</th>
                    <th>ওয়ালেট</th>
                    <th>ফলাফল</th>
                </tr>
                <?php
                $u_id = $_SESSION['user_id'];
                $bets = $conn->query("SELECT * FROM bets WHERE username = '$u_id' ORDER BY id DESC LIMIT 10");

                if ($bets && $bets->num_rows > 0) {
                    while($b = $bets->fetch_assoc()) {
                        $res_color = ($b['win_loss'] == 'win') ? '#00ff88' : '#ff4d4d';
                        echo "<tr style='border-bottom: 1px solid #222;'>";
                        echo "<td style='padding: 10px; color: #777;'>" . date('h:i A', strtotime($b['created_at'])) . "</td>";
                        echo "<td>৳" . number_format($b['bet_amount'], 0) . "</td>";
                        echo "<td>" . strtoupper($b['active_wallet'] ?? 'MAIN') . "</td>";
                        echo "<td style='color: $res_color; font-weight: bold;'>" . strtoupper($b['win_loss']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='padding: 20px; color: #555;'>এখনো কোনো বেট নেই</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</div>

<script>
function toggleBox(id, iconId) {
    var content = document.getElementById(id);
    var icon = document.getElementById(iconId);
    if (content.style.maxHeight && content.style.maxHeight !== "0px") {
        content.style.maxHeight = "0px";
        icon.innerText = "▼";
    } else {
        content.style.maxHeight = content.scrollHeight + "px";
        icon.innerText = "▲";
    }
}
</script>
<!-- ১৩৬ নম্বর লাইন এখানে শেষ হবে -->


<script>
 function updateWallet(walletType) {
    // ডাটাবেস বা সেশনে ওয়ালেট টাইপ সেভ করার জন্য কল
    fetch('update_wallet.php?type=' + walletType)
    .then(() => {
        // পেজ রিফ্রেশ করলে এখন সেটি সিলেক্ট করা ওয়ালেটেই থাকবে
        location.reload();
    });
}
   
function placeTestBet() {
    const wallet = document.getElementById('active_wallet').value;
    const btn = document.getElementById('play_btn');
    const amount = 10; 

    btn.disabled = true;
    btn.innerText = "PROCESSING...";

    let formData = new FormData();
    formData.append('amount', amount);
    formData.append('wallet', wallet);

    fetch('./place_bet.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        if(text.includes("success")) {
            alert("✅ বাজি সফল হয়েছে!");
            location.reload(); 
        } else {
            alert("⚠️ বাজি ধরা হয়েছে, ব্যালেন্স চেক করুন।");
            location.reload();
        }
    })
    .catch(err => {
        console.log(err);
        location.reload();
    });
}
</script>


</body>
</html>
            
