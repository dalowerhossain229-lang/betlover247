<?php
session_start();
include 'db.php';
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
$user = $_SESSION['user_id'];
$res = $conn->query("SELECT balance FROM users WHERE username = '$user'");
$row = $res->fetch_assoc();
$balance = $row['balance'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>7 Up 7 Down - Double Dice</title>
    <style>
        body { background: #0a0b10; color: #fff; font-family: sans-serif; margin: 0; display: flex; flex-direction: column; align-items: center; min-height: 100vh; }
        .header { width: 100%; padding: 15px; display: flex; justify-content: space-between; background: #161b22; box-sizing: border-box; border-bottom: 1px solid #333; }
        .balance-box { color: #00ff88; font-weight: bold; border: 1px solid #00ff88; padding: 5px 15px; border-radius: 20px; background: rgba(0,255,136,0.1); }
        
        .game-card { width: 90%; max-width: 400px; background: #161b22; margin-top: 30px; padding: 25px; border-radius: 25px; text-align: center; border: 1px solid #30363d; box-shadow: 0 10px 40px rgba(0,0,0,0.6); }
        
        /* ডাবল ডাইস এরিয়া */
        .dice-container { display: flex; justify-content: center; gap: 20px; margin: 30px 0; perspective: 1000px; }
        .dice { width: 60px; height: 60px; background: #fff; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 35px; color: #000; box-shadow: 0 5px 15px rgba(255,255,255,0.2); transition: transform 0.5s; }
        
        /* পাজল্ড অ্যানিমেশন */
        .rolling { animation: puzzle 0.15s infinite; }
        @keyframes puzzle {
            0% { transform: rotate(0deg) scale(1); }
            50% { transform: rotate(180deg) scale(1.1); }
            100% { transform: rotate(360deg) scale(1); }
        }

        .bet-options { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin: 25px 0; }
        .opt { background: #21262d; border: 2px solid #30363d; color: #fff; padding: 15px 5px; border-radius: 15px; cursor: pointer; transition: 0.3s; }
        .opt.active { border-color: #00ff88; background: rgba(0,255,136,0.2); box-shadow: 0 0 15px rgba(0,255,136,0.2); }
        .opt b { display: block; font-size: 13px; }
        .opt span { font-size: 11px; color: #00ff88; }

        input { background: #0d1117; border: 1px solid #30363d; color: #00ff88; width: 90%; padding: 15px; border-radius: 12px; text-align: center; font-size: 20px; outline: none; margin-bottom: 20px; }
        
        .btn-play { width: 100%; padding: 18px; background: #00ff88; border: none; border-radius: 15px; color: #000; font-weight: bold; font-size: 18px; cursor: pointer; box-shadow: 0 5px 15px rgba(0,255,136,0.3); }
        .btn-play:disabled { background: #30363d; color: #888; box-shadow: none; }
    </style>
</head>
<body>

    <div class="header">
        <a href="index.php" style="color: #fff; text-decoration: none;">⬅️ Home</a>
        <div class="balance-box">৳ <span id="bal"><?php echo number_format($balance, 2); ?></span></div>
    </div>

    <div class="game-card">
        <h2 style="color: #00ff88; margin-bottom: 5px;">7 UP 7 DOWN</h2>
        <p style="color: #888; font-size: 12px; margin-top: 0;">দুটো কড়ির যোগফলই আপনার ভাগ্য!</p>

        <div class="dice-container">
            <div id="dice1" class="dice">1</div>
            <div id="dice2" class="dice">1</div>
        </div>

        <div class="bet-options">
            <div class="opt" onclick="selectOpt('under', 1.98)">
                <b>UNDER 7</b> <span>1.98x</span>
            </div>
            <div class="opt" onclick="selectOpt('seven', 5.8)">
                <b>LUCKY 7</b> <span>5.8x</span>
            </div>
            <div class="opt" onclick="selectOpt('over', 1.98)">
                <b>OVER 7</b> <span>1.98x</span>
            </div>
        </div>

        <input type="number" id="betAmount" placeholder="বেট এর পরিমাণ লিখুন" min="10" max="6000">

        <button id="playBtn" class="btn-play" onclick="playGame()">PLACE BET</button>
        <p id="msg" style="margin-top: 15px; font-weight: bold;"></p>
    </div>

    <script>
        let selectedType = null;
        let odds = 0;

        function selectOpt(type, val) {
            selectedType = type;
            odds = val;
            document.querySelectorAll('.opt').forEach(el => el.classList.remove('active'));
            event.currentTarget.classList.add('active');
        }

        function playGame() {
            const amount = parseFloat(document.getElementById('betAmount').value);
            const msg = document.getElementById('msg');
            const d1 = document.getElementById('dice1');
            const d2 = document.getElementById('dice2');
            const btn = document.getElementById('playBtn');

            if(!selectedType) return alert('দয়া করে একটি ঘর সিলেক্ট করুন!');
            if(isNaN(amount) || amount < 10 || amount > 6000) return alert('বেট ১০ থেকে ৬০০০ টাকার মধ্যে হতে হবে!');

            btn.disabled = true;
            msg.style.color = "#888";
            msg.innerHTML = "কড়িগুলো ঘুরছে...";
            d1.classList.add('rolling');
            d2.classList.add('rolling');

            // ১. টাকা কাটা
            fetch('api_callback.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'bet', username: '<?php echo $user; ?>', amount: amount, tx_id: 'UO7_'+Date.now() })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'ok') {
                    setTimeout(() => {
                        d1.classList.remove('rolling');
                        d2.classList.remove('rolling');
                        
                        let val1 = Math.floor(Math.random() * 6) + 1;
                        let val2 = Math.floor(Math.random() * 6) + 1;
                        let total = val1 + val2;
                        
                        d1.innerHTML = val1;
                        d2.innerHTML = val2;

                        let win = false;
                        if(selectedType === 'under' && total < 7) win = true;
                        if(selectedType === 'seven' && total === 7) win = true;
                        if(selectedType === 'over' && total > 7) win = true;

                        if(win) {
                            let winAmount = amount * odds;
                            msg.style.color = "#00ff88";
                            msg.innerHTML = `WINNER! 🎰 মোট: ${total} (জিতেছেন ৳${winAmount.toFixed(2)})`;
                            fetch('api_callback.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ action: 'win', username: '<?php echo $user; ?>', amount: winAmount, tx_id: 'W_'+Date.now() })
                            }).then(() => setTimeout(() => location.reload(), 3000));
                        } else {
                            msg.style.color = "#ff4d4d";
                            msg.innerHTML = `You Lost! মোট ছিল: ${total}`;
                            setTimeout(() => location.reload(), 3000);
                        }
                    }, 2000);
                } else {
                    alert('ব্যালেন্স নেই!');
                    location.reload();
                }
            });
        }
    </script>
</body>
</html>
