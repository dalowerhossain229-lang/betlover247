<?php
ob_start();
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
$user = $_SESSION['user_id'];
$res = $conn->query("SELECT balance FROM users WHERE username = '$user'");
$row = $res->fetch_assoc();
$balance = $row['balance'];

// Fetch Admin Logic (RTP/Win-Loss)
$logic_res = $conn->query("SELECT rtp_value FROM settings WHERE id = 1");
$rtp = $logic_res->fetch_assoc()['rtp_value'] ?? 50;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Under Over 7 - Luxury Edition</title>
    <style>
        :root { --neon: #00ff88; --dark-bg: #0a0b10; --card-bg: #161b22; --border: #30363d; }
        body { background: var(--dark-bg); color: #fff; font-family: 'Segoe UI', sans-serif; margin: 0; display: flex; flex-direction: column; height: 100vh; overflow: hidden; }
        
        /* ১. আপনার নকশার হেডার (Back, Balance, History) */
        .header { display: flex; justify-content: space-between; align-items: center; padding: 12px 15px; background: var(--card-bg); border-bottom: 1px solid var(--border); box-sizing: border-box; }
        .h-btn { background: #21262d; border: 1px solid var(--border); color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: bold; }
        .bal-box { color: var(--neon); font-weight: 800; font-size: 14px; text-shadow: 0 0 10px rgba(0,255,136,0.3); }

        /* ২. মাঝখানের গেম এরিয়া (Dice Area) */
        .game-area { flex-grow: 1; display: flex; flex-direction: column; align-items: center; justify-content: space-evenly; padding: 15px; }
        .dice-display { background: rgba(255,255,255,0.03); border: 2px dashed var(--border); padding: 25px 40px; border-radius: 20px; display: flex; gap: 20px; }
        .dice { width: 65px; height: 65px; background: #fff; border-radius: 12px; color: #000; display: flex; align-items: center; justify-content: center; font-size: 38px; font-weight: bold; box-shadow: 0 5px 20px rgba(255,255,255,0.2); }
        .rolling { animation: jumpShuffle 0.15s infinite; }
        @keyframes jumpShuffle { 0%, 100% { transform: translateY(0) rotate(0); } 50% { transform: translateY(-15px) rotate(15deg); } }

        /* ৩. ওড সিলেকশন (Under, 7, Over) */
        .odds-container { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; width: 100%; max-width: 400px; }
        .odd-box { background: var(--card-bg); border: 2px solid var(--border); padding: 15px 5px; text-align: center; border-radius: 12px; cursor: pointer; transition: 0.3s; }
        .odd-box.active { border-color: var(--neon); background: rgba(0,255,136,0.1); box-shadow: 0 0 15px rgba(0,255,136,0.2); }
        .odd-box b { display: block; font-size: 13px; margin-bottom: 3px; }
        .odd-box span { font-size: 11px; color: var(--neon); }

        /* ৪. কন্ট্রোল প্যানেল (আপনার স্কেচ অনুযায়ী একদম নিচে) */
        .bottom-panel { background: var(--card-bg); padding: 15px; border-top: 1px solid var(--border); padding-bottom: env(safe-area-inset-bottom, 15px); }
        
        .amt-row { display: flex; align-items: center; gap: 8px; margin-bottom: 15px; }
        .math-btn { background: #30363d; border: none; color: #fff; width: 40px; height: 40px; border-radius: 8px; font-size: 22px; flex-shrink: 0; }
        .amt-scroll { display: flex; gap: 8px; overflow-x: auto; scrollbar-width: none; flex-grow: 1; }
        .amt-scroll::-webkit-scrollbar { display: none; }
        .chip { flex-shrink: 0; background: #21262d; border: 1px solid var(--border); color: #fff; padding: 10px 15px; border-radius: 8px; font-size: 13px; font-weight: bold; }
        .chip.active { border-color: var(--neon); color: var(--neon); background: rgba(0,255,136,0.05); }

        .action-row { display: flex; gap: 12px; align-items: center; }
        .snd-btn { background: #21262d; border: 1px solid var(--border); color: #fff; width: 55px; height: 55px; border-radius: 12px; font-size: 22px; display: flex; align-items: center; justify-content: center; }
        .bet-btn { flex-grow: 1; background: var(--neon); border: none; color: #000; height: 55px; border-radius: 12px; font-weight: 800; font-size: 18px; text-transform: uppercase; box-shadow: 0 4px 15px rgba(0,255,136,0.3); }
        .bet-btn:disabled { background: #333; color: #888; box-shadow: none; }
    </style>
</head>
<body>

    <div class="header">
        <a href="index.php" class="h-btn">BACK</a>
        <div class="bal-box">BALANCE: ৳<span id="uBal"><?php echo number_format($balance, 2); ?></span></div>
        <a href="game_history.php" class="h-btn">HISTORY</a>
    </div>

    <div class="game-area">
        <div class="odds-container">
            <div class="odd-box" onclick="setBT('under', 1.98)"><b>UNDER 7</b><span>1.98x</span></div>
            <div class="odd-box" onclick="setBT('seven', 5.8)"><b>LUCKY 7</b><span>5.8x</span></div>
            <div class="odd-box" onclick="setBT('over', 1.98)"><b>OVER 7</b><span>1.98x</span></div>
        </div>

        <div class="dice-display">
            <div id="d1" class="dice">1</div>
            <div id="d2" class="dice">1</div>
        </div>
        
        <p id="gMsg" style="height: 20px; font-weight: bold; font-size: 18px; color: var(--neon);"></p>
    </div>

    <div class="bottom-panel">
        <div class="amt-row">
            <button class="math-btn" onclick="chgAmt(-10)">-</button>
            <div class="amt-scroll">
                <?php $chips = [10, 20, 50, 100, 200, 500, 1000, 5000]; foreach($chips as $c): ?>
                    <button class="chip" onclick="setAmt(<?php echo $c; ?>)"><?php echo $c; ?></button>
                <?php endforeach; ?>
            </div>
            <button class="math-btn" onclick="chgAmt(10)">+</button>
        </div>

        <div class="action-row">
            <button class="snd-btn" onclick="tglSnd()">🔊</button>
            <button id="bBtn" class="bet-btn" onclick="play()">PLACE BET (৳<span id="dAmt">10</span>)</button>
        </div>
    </div>

    <script>
        let sType = null, bAmt = 10, odds = 0, snd = true;
        const rtp = <?php echo $rtp; ?>;

        function setBT(t, v) { 
            sType = t; odds = v; 
            document.querySelectorAll('.odd-box').forEach(b => b.classList.remove('active'));
            event.currentTarget.classList.add('active');
        }
        function setAmt(v) { 
            bAmt = v; 
            document.querySelectorAll('.chip').forEach(b => b.classList.remove('active'));
            event.currentTarget.classList.add('active');
            document.getElementById('dAmt').innerText = bAmt; 
        }
        function chgAmt(v) { bAmt = Math.max(10, bAmt + v); document.getElementById('dAmt').innerText = bAmt; }
        function tglSnd() { snd = !snd; event.target.innerText = snd ? "🔊" : "🔇"; }

        function play() {
            if(!sType) return alert('SELECT A BET OPTION!');
            const btn = document.getElementById('bBtn'), d1 = document.getElementById('d1'), d2 = document.getElementById('d2'), msg = document.getElementById('gMsg');
            
            btn.disabled = true; d1.classList.add('rolling'); d2.classList.add('rolling'); msg.innerText = "ROLLING...";

            fetch('api_callback.php', {
                method: 'POST', headers: { 'Content-Type' : 'application/json' },
                body: JSON.stringify({ action: 'bet', username: '<?php echo $user; ?>', amount: bAmt, tx_id: 'G_'+Date.now() })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'ok') {
                    setTimeout(() => {
                        d1.classList.remove('rolling'); d2.classList.remove('rolling');
                        
                        function getRes() {
                            let v1 = Math.floor(Math.random()*6)+1, v2 = Math.floor(Math.random()*6)+1;
                            let total = v1 + v2;
                            let isWin = (sType==='under' && total<7) || (sType==='seven' && total===7) || (sType==='over' && total>7);
                            let chance = Math.floor(Math.random()*100)+1;
                            if(chance > rtp && isWin) return getRes();
                            return {v1, v2, total, isWin};
                        }

                        let res = getRes();
                        d1.innerText = res.v1; d2.innerText = res.v2;

                        if(res.isWin) {
                            let wAmt = bAmt * odds;
                            msg.style.color = "#00ff88"; msg.innerText = "WINNER! +৳" + wAmt.toFixed(2);
                            fetch('api_callback.php', {
                                method: 'POST', headers: { 'Content-Type' : 'application/json' },
                                body: JSON.stringify({ action: 'win', username: '<?php echo $user; ?>', amount: wAmt, tx_id: 'W_'+Date.now() })
                            }).then(() => setTimeout(() => location.reload(), 2500));
                        } else {
                            msg.style.color = "#ff4d4d"; msg.innerText = "LOST! TOTAL: " + res.total;
                            setTimeout(() => location.reload(), 2500);
                        }
                    }, 1500);
                } else { alert(data.message); location.reload(); }
            });
        }
    </script>
</body>
</html>
