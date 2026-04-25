<?php
ob_start();
session_start();
include 'db.php';
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
$user = $_SESSION['user_id'];
$res = $conn->query("SELECT balance FROM users WHERE username = '$user'");
$row = $res->fetch_assoc();
$balance = $row['balance'];
$logic_res = $conn->query("SELECT rtp_value FROM settings WHERE id = 1");
$rtp = $logic_res->fetch_assoc()['rtp_value'] ?? 50;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Under Over 7 - Luxury Master</title>
    <style>
        :root { --neon: #00ff88; --dark: #0a0b10; --card: #161b22; --border: #30363d; }
        body { background: var(--dark); color: #fff; font-family: 'Arial', sans-serif; margin: 0; display: flex; flex-direction: column; height: 100vh; overflow: hidden; }
        
        /* ১. হেডার (Back, Balance, History) */
        .header { display: flex; justify-content: space-between; align-items: center; padding: 12px 15px; background: var(--card); border-bottom: 1px solid var(--border); }
        .h-btn { background: #21262d; border: 1px solid var(--border); color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 11px; font-weight: bold; }
        .bal-box { color: var(--neon); font-weight: 800; font-size: 14px; }

        /* ২. গেম বক্স (Dice Area) */
        .game-area { 
    flex-grow: 1; 
    display: flex; 
    flex-direction: column; 
    align-items: center; 
    justify-content: center; /* space-around থেকে বদলে এটি দিন */
    padding: 5px; 
}

        .dice-box { display: flex; gap: 20px; background: rgba(255,255,255,0.02); padding: 25px 45px; border-radius: 20px; border: 1px dashed #444; }
        .dice { width: 70px; height: 70px; background: #fff; border-radius: 15px; color: #000; display: flex; align-items: center; justify-content: center; font-size: 40px; font-weight: bold; box-shadow: 0 5px 20px rgba(255,255,255,0.2); }
        .rolling { animation: shuffle 0.15s infinite; }
        @keyframes shuffle { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px) rotate(10deg); } }

        .bet-types { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; width: 95%; max-width: 400px; }
        .type { background: var(--card); border: 2px solid var(--border); padding: 15px 5px; text-align: center; border-radius: 12px; cursor: pointer; }
        .type.active { border-color: var(--neon); background: rgba(0,255,136,0.1); }
        .type b { display: block; font-size: 12px; color: #888; }
        .type span { font-size: 15px; color: var(--neon); font-weight: bold; }

        /* ৩. আপনার স্কেচ অনুযায়ী ২-লেয়ার চিপস ও প্লাস-মাইনাস */
        .bottom-panel { 
    background: var(--card); 
    padding: 10px 15px; /* প্যাডিং ১৫ থেকে কমিয়ে ১০ করুন */
    border-top: 1px solid var(--border); 
    padding-bottom: 20px; /* এটি আপনার ফোনের নিচের বারের জন্য গ্যাপ */
}

        .amt-wrapper { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
        .math-btn { background: #30363d; border: none; color: #fff; width: 45px; height: 45px; border-radius: 8px; font-size: 25px; flex-shrink: 0; }
        
        .chip-grid { display: flex; gap: 10px; overflow-x: auto; scrollbar-width: none; flex-grow: 1; }
        .chip-grid::-webkit-scrollbar { display: none; }
        .chip-stack { flex-shrink: 0; display: flex; flex-direction: column; gap: 5px; }
        .chip { background: #21262d; border: 1px solid var(--border); color: #fff; padding: 8px 12px; border-radius: 8px; font-size: 13px; font-weight: bold; min-width: 60px; }
        .chip.active { border-color: var(--neon); color: var(--neon); background: rgba(0,255,136,0.05); }

        /* ৪. আপনার স্কেচের নিচের লাইন (Username, Place Bet, Sound) */
        .action-footer { display: flex; gap: 10px; align-items: center; }
        .user-info { background: #21262d; border: 1px solid var(--border); padding: 15px 8px; border-radius: 10px; font-size: 10px; color: #777; min-width: 80px; text-align: center; }
        .place-bet-btn { flex-grow: 1; background: var(--neon); border: none; color: #000; height: 55px; border-radius: 12px; font-weight: 900; font-size: 18px; text-transform: uppercase; box-shadow: 0 5px 15px rgba(0,255,136,0.3); }
        .place-bet-btn:disabled { background: #333; color: #666; box-shadow: none; }
        .sound-btn { background: #21262d; border: 1px solid var(--border); color: #fff; width: 55px; height: 55px; border-radius: 12px; font-size: 22px; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body>

    <div class="header">
        <a href="index.php" class="h-btn">BACK</a>
        <div class="bal-box">BALANCE: ৳<span id="bal"><?php echo number_format($balance, 2); ?></span></div>
        <a href="game_history.php" class="h-btn">HISTORY</a>
    </div>

    <div class="game-area">
        <div class="bet-types">
            <div class="type" onclick="setBT('under', 1.98)"><b>UNDER 7</b><span>1.98x</span></div>
            <div class="type" onclick="setBT('seven', 5.8)"><b>LUCKY 7</b><span>5.8x</span></div>
            <div class="type" onclick="setBT('over', 1.98)"><b>OVER 7</b><span>1.98x</span></div>
        </div>

        <div class="dice-box">
            <div id="d1" class="dice">1</div>
            <div id="d2" class="dice">1</div>
        </div>
        <p id="msg" style="height: 20px; font-weight: bold; color: var(--neon);"></p>
    </div>

    <div class="bottom-panel">
        <div class="amt-wrapper">
            <button class="math-btn" onclick="chgAmt(-10)">-</button>
            <div class="chip-grid">
                <!-- স্কেচ অনুযায়ী ডাবল লেয়ার চিপস -->
                <div class="chip-stack">
                    <button class="chip" onclick="setAmt(10)">10</button>
                    <button class="chip" onclick="setAmt(1000)">1000</button>
                </div>
                <div class="chip-stack">
                    <button class="chip" onclick="setAmt(20)">20</button>
                    <button class="chip" onclick="setAmt(2000)">2000</button>
                </div>
                <div class="chip-stack">
                    <button class="chip" onclick="setAmt(50)">50</button>
                    <button class="chip" onclick="setAmt(5000)">5000</button>
                </div>
                <div class="chip-stack">
                    <button class="chip" onclick="setAmt(100)">100</button>
                    <button class="chip" onclick="setAmt(8000)">8000</button>
                </div>
                <div class="chip-stack">
                    <button class="chip" onclick="setAmt(500)">500</button>
                    <button class="chip" onclick="setAmt(10000)">10000</button>
                </div>
            </div>
            <button class="math-btn" onclick="chgAmt(10)">+</button>
        </div>

        <div class="action-footer">
            <div class="user-info"><?php echo strtoupper($user); ?></div>
            <button id="pBtn" class="place-bet-btn" onclick="play()">PLACE BET (৳<span id="dAmt">10</span>)</button>
            <button class="sound-btn" onclick="tglSnd()">🔊</button>
        </div>
    </div>

    <script>
        let sType = null, bAmt = 10, odds = 0, snd = true;
        const rtp = <?php echo $rtp; ?>;

        function setBT(t, v) { 
            sType = t; odds = v; 
            document.querySelectorAll('.type').forEach(b => b.classList.remove('active'));
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
            if(!sType) return alert('SELECT AN OPTION!');
            const btn = document.getElementById('pBtn'), d1 = document.getElementById('d1'), d2 = document.getElementById('d2'), msg = document.getElementById('msg');
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
