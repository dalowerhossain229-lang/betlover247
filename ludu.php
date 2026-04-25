<?php
session_start();
include 'db.php';

// লগইন চেক
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$user = $_SESSION['user_id'];
$res = $conn->query("SELECT balance FROM users WHERE username = '$user'");
$row = $res->fetch_assoc();
$balance = $row['balance'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ludu Star - Win Cash</title>
    <style>
        body { background: #0a0f0d; color: white; font-family: sans-serif; text-align: center; padding: 20px; }
        .game-box { background: #111; padding: 20px; border-radius: 15px; border: 1px solid #00ff88; margin-top: 50px; }
        .btn { background: #00ff88; color: #000; padding: 10px 20px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; margin-top: 10px; }
        .balance-info { color: #00ff88; font-size: 18px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <a href="index.php" style="color: #888; text-decoration: none; float: left;">⬅️ Home</a>
    <div class="balance-info">Balance: ৳<span id="bal"><?php echo number_format($balance, 2); ?></span></div>

    <div class="game-box">
        <h2>🎲 LUDU LUCKY NUMBER</h2>
        <p>Bet ৳10 & Win ৳20!</p>
        <input type="number" id="guess" placeholder="1 to 6" style="width: 50px; padding: 5px; text-align: center;">
        <br>
        <button class="btn" onclick="playLudu()">ROLL DICE</button>
        <h3 id="result"></h3>
    </div>

    <script>
    function playLudu() {
        let guess = document.getElementById('guess').value;
        if(guess < 1 || guess > 6) return alert('১ থেকে ৬ এর মধ্যে সংখ্যা দিন!');

        // ১. টাকা কাটা (Bet)
        fetch('api_callback.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'bet', username: '<?php echo $user; ?>', amount: 10, tx_id: 'LUDU_'+Date.now() })
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'ok') {
                let dice = Math.floor(Math.random() * 6) + 1;
                if(dice == guess) {
                    document.getElementById('result').innerHTML = "🎲 "+dice+"! আপনি জিতেছেন! ৳২০ যোগ হয়েছে।";
                    // ২. টাকা যোগ করা (Win)
                    fetch('api_callback.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ action: 'win', username: '<?php echo $user; ?>', amount: 20, tx_id: 'WIN_'+Date.now() })
                    }).then(() => location.reload());
                } else {
                    document.getElementById('result').innerHTML = "🎲 "+dice+"! আপনি হেরেছেন। আবার চেষ্টা করুন!";
                    setTimeout(() => location.reload(), 2000);
                }
            } else {
                alert('পর্যাপ্ত ব্যালেন্স নেই!');
            }
        });
    }
    </script>
</body>
</html>
