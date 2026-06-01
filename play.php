<?php
ob_start();
session_start();
include 'db.php';

// ১. সেশন থেকে নিখুঁতভাবে ইউজার আইডি চেক
$u = $_SESSION['user_id'] ?? $_SESSION['username'] ?? '';
if (empty($u)) {
    header("Location: index.php");
    exit();
}

// ২. ডাটাবেজ থেকে রিয়েল-টাইম ইউজারের ওয়ালেট ডেটা আনা
$query = $conn->query("SELECT * FROM users WHERE username = '$u' OR id = '$u'");
$user_data = $query->fetch_assoc();

// ==================== ৩. ডাইনামিক রেন্ডার হোস্টিং সাব-ডোমেন ইউআরএল লিঙ্ক ম্যাট্রিক্স ====================
$aviator_base_url      = "https://aviator2-0-azym.onrender.com";
$wingo_base_url        = "https://color-trade.onrender.com";
$chicken_base_url      = "https://chickenroad.onrender.com";
$slot_base_url         = "https://lucky777-slot.onrender.com";
$ace_base_url          = "https://super-ace.onrender.com";
$ludo_base_url         = "https://ludu777.onrender.com";
$wheel_base_url        = "https://mega-wheel.onrender.com";

$coinflip_base_url     = "https://head-and-tail-sj8q.onrender.com";
$mines_base_url        = "https://lucky-mines.onrender.com";
$billionaire_base_url  = "https://billionaire-slot-lyrw.onrender.com";
$andarbahar_base_url   = "https://andar-bahar-nalu.onrender.com";
$baccarat_base_url     = "https://baccarat-master.onrender.com";
$blackjack_base_url    = "https://blackjack-21-pszh.onrender.com";
$crypto_base_url       = "https://crypto-multiply.onrender.com";
$dice3d_base_url       = "https://dice-3d.onrender.com";
$dragontiger_base_url  = "https://dragon-tiger-muo0.onrender.com";
$fortunegod_base_url   = "https://golden-fortune-win.onrender.com";
$hilo_base_url         = "https://hilo-card.onrender.com";
$jhandimunda_base_url  = "https://jhandi-munda-pk5r.onrender.com";
$marblepop_base_url    = "https://onrender.com";
$moneytree_base_url    = "https://onrender.com";
$twistspin_base_url    = "https://onrender.com";
$roulette_base_url     = "https://onrender.com";
$moneycoming_base_url  = "https://onrender.com";
$rainbow_base_url      = "https://rainbow-slot.onrender.com";
$derby_base_url        = "https://royal-derby.onrender.com";
$keno_base_url         = "https://royal-keno.onrender.com";
$plinko_base_url       = "https://royal-plinko.onrender.com";

// ==================== ৪. ডাইনামিক সেশন ও ওয়ালেট ভেরিয়েবল হ্যান্ডলার বর্ম ====================
$active_wallet = isset($_GET['wallet']) ? $_GET['wallet'] : (isset($_GET['data']['active_wallet']) ? $_GET['data']['active_wallet'] : 'main');
$get_game_type = isset($_GET['game']) ? $_GET['game'] : '';
$game_user_id = !empty($u) ? $u : "guest";

$game_url = "https://onrender.com"; // Fallback URL

// ==================== ৫. খাতার ৪টি পাতা মিলিয়ে কন্ডিশনাল রাউটিং গেটওয়ে লুপ (If-Else) ====================
if ($get_game_type === 'color') {
    $game_url = $wingo_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'chicken') {
    $game_url = $chicken_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'slotmaster') {
    $game_url = $slot_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'superace') {
    $game_url = $ace_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'ludo') {
    $game_url = $ludo_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'megawheel') {
    $game_url = $wheel_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'coinflip') {
    $game_url = $coinflip_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'mines') {
    $game_url = $mines_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'billionaireslot') {
    $game_url = $billionaire_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'andarbahar') {
    $game_url = $andarbahar_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'baccaratmaster') {
    $game_url = $baccarat_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'blackjack21') {
    $game_url = $blackjack_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'cryptomultiply') {
    $game_url = $crypto_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'dice3d') {
    $game_url = $dice3d_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'dragontiger') {
    $game_url = $dragontiger_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'goldenfortunewin') {
    $game_url = $fortunegod_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'hilocard') {
    $game_url = $hilo_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'jhandimunda') {
    $game_url = $jhandimunda_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'luckymarblepop') {
    $game_url = $marblepop_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'luckymoneytree') {
    $game_url = $moneytree_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'twistmultiplespin') {
    $game_url = $twistspin_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'miniroulette') {
    $game_url = $roulette_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'moneycoming') {
    $game_url = $moneycoming_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'rainbowslot') {
    $game_url = $rainbow_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'royalderby') {
    $game_url = $derby_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'royalkeno') {
    $game_url = $keno_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'royalplinko') {
    $game_url = $plinko_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'crazy-777') {
    $game_url = "https://onrender.com" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'jili-fishing') {
    $game_url = "https://onrender.com" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'dragon-ball-fishing') {
    $game_url = "https://onrender.com" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'fighter-crash') {
    $game_url = "https://onrender.com" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'penalty-shootout') {
    $game_url = "https://onrender.com" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'lightning-roulette') {
    $game_url = "https://onrender.com" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else if ($get_game_type === 'aviator') {
    $game_url = $aviator_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else {
    $game_url = $aviator_base_url . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Game Test - BetLover247</title>
    <style>
        body { margin: 0; padding: 0; background: #000; font-family: sans-serif; }
        
        /* 🕹️ গেম হেডার */
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
        
        .history-btn {
            color: #00ff88;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
            background: #111;
            padding: 8px 12px;
            border-radius: 5px;
            border: 1px solid #333;
        }
        
        /* 🎰 গেম এরিয়া */
        .game-container {
            width: 100%;
            height: calc(100vh - 55px);
            background: #111;
        }
        
        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>

<div class="game-header">
    <a href="index.php" class="back-btn">BACK</a>
    
    <!-- 🎯 ওয়ালেট সিলেকশন ড্রপডাউন -->
    <select id="active_wallet" class="wallet-select" onchange="updateWallet(this.value)">
        <option value="main" <?php if($active_wallet == 'main') echo 'selected'; ?>>Main: ৳<?php echo number_format($user_data['balance'], 2); ?></option>
        <option value="pb" <?php if($active_wallet == 'pb') echo 'selected'; ?>>PB: ৳<?php echo number_format($user_data['pb_balance'], 2); ?></option>
        <option value="bonus" <?php if($active_wallet == 'bonus') echo 'selected'; ?>>Bonus: ৳<?php echo number_format($user_data['bonus_balance'], 2); ?></option>
    </select>

    <a href="bet_logs.php" class="history-btn">HISTORY</a>
</div>

<div class="game-container">
    <!-- 🎯 এভিয়েটর গেম লোড আইফ্রেম -->
    <iframe src="<?php echo $game_url; ?>" id="game_frame" allow="autoplay; fullscreen; gaming"></iframe>
</div>

<script>
// ১. ওয়ালেট সিলেকশন রিফ্রেশার
function updateWallet(walletType) {
    fetch('update_wallet.php?type=' + walletType)
    .then(() => {
        location.reload();
    });
}

// 🎯 ২. ইউনিভার্সাল রিফ্রেশ-মুক্ত ব্যালেন্স সিঙ্ক (যা সাথে সাথে ওপরের ব্যালেন্স আপডেট করবে)
window.addEventListener("message", function(event) {
    if (event.data && event.data.action === "refresh_wallet") {
        fetch(window.location.href)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            // 🎯 থিমের সম্ভাব্য সব ব্যালেন্স আইডি ও ড্রপডাউন কন্টেইনার এক ক্লিকে লাইভ সিঙ্ক করার মেকানিজম
            const selectors = ['#active_wallet', '#balance', '#main-balance', '.user-balance', '.balance-amount', '[id*="balance"]'];

            selectors.forEach(selector => {
                const newEl = doc.querySelector(selector);
                const currentEl = document.querySelector(selector);
                if (newEl && currentEl) {
                    currentEl.innerHTML = newEl.innerHTML;
                }
            });
        });
    }
});
</script>
</body>
</html>
