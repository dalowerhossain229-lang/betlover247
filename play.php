<?php
ob_start();
session_start();
include 'db.php';

// || ১. সেশন থেকে ওরিজিনাল ইউজার আইডি একুরেট চেক বর্ম
$u = $_SESSION['user_id'] ?? $_SESSION['username'] ?? '';
if (empty($u)) {
    header("Location: index.php");
    exit();
}

// // ডাটাবেজ থেকে রিয়েল-টাইমে প্লেয়ারের ওরিজিনাল ওয়ালেট ডেটা আনা
$query = $conn->query("SELECT * FROM users WHERE username = '$u' OR id = '$u'");
$user_data = $query->fetch_assoc();

$active_wallet = isset($_GET['wallet']) ? $_GET['wallet'] : ($_GET['wallet'] ?? 'main');
$game_get_type = isset($_GET['game']) ? $_GET['game'] : '';

// 🔒 [গ্র্যান্ড ব্যালেন্স জিরো ফিক্সড ট্রিক]: নোড ইঞ্জিনে আইডি হিসেবে প্লেয়ারের ওরিজিনাল ডাটাবেজ ইউজারনেম পাস লক!
$game_user_id = !empty($user_data['username']) ? $user_data['username'] : (!empty($u) ? $u : "guest");

// ===================================================================================
// // ২. গ্র্যান্ড ডাইনামিক গেম ইউআরএল অবজেক্ট ডিরেক্টরি (আপনার স্ক্রিনশটের হুবহু ওরিজিনাল স্লট ওッズ সিঙ্ক ওস্তাদ!)
// ===================================================================================
$gameRoutingMap = [
    'luckywheel'           => "https://mega-wheel.onrender.com",
    'superace'             => "https://super-ace.onrender.com",
    'ludo'                 => "https://ludu777.onrender.com",
    'color'                => "https://color-trade.onrender.com",
    'cryptomultiply'       => "https://crypto-multiply.onrender.com",
    'royalkeno'            => "https://royal-keno.onrender.com",
    'royalderby'           => "https://royal-derby.onrender.com",
    'twistmultiplespin'    => "https://twist-multiple-spin.onrender.com",
    'billionaireslot'      => "https://billionaire-slot.onrender.com",
    'chicken'              => "https://chickenroad.onrender.com",
    'mines'                => "https://lucky-mines.onrender.com",
    'andarbahar'           => "https://andar-bahar-nalu.onrender.com",
    'baccaratmaster'       => "https://baccarat-master.onrender.com",
    'blackjack21'          => "https://blackjack-21-pszh.onrender.com",
    'dice3d'               => "https://dice-3d.onrender.com",
    'aviator'              =>"https://aviator2-0-azym.onrender.com",
    'dragontiger'          => "https://dragon-tiger-muo0.onrender.com",
    'hilocard'             => "https://hilo-card.onrender.com",
    'jhandimunda'          => "https://jhandi-munda-pk5r.onrender.com",
    'luckymarblepop'       => "https://lucky-marble-pop.onrender.com",
    'moneycoming'          => "https://money-coming.onrender.com",
    'royalplinko'          => "https://royal-plinko.onrender.com",
    'teenpatti'            => "https://teen-patti-s3pc.onrender.com",
    'fish-prawn-crab'      => "https://fish-prawn-crab.onrender.com",
    'fantan'               => "https://fan-tan.onrender.com",
    'sicbo'                => "https://sic-bo-bo.onrender.com",
    'goldenchicken'        => "https://golden-chicken-a99o.onrender.com",
    'shanghainights'       => "https://shanghai-nights.onrender.com",
    'alibaba'              => "https://alibaba-6yt1.onrender.com",
    'crazy777'                => "https://crazy-777.onrender.com",
    'caishendao'                => "https://cai-shen-dao.onrender.com",
    'fortunegems'                => "https://fortune-gems.onrender.com",
    'jadeelephant'                => "https://jade-elephant.onrender.com",
    'fafafa'                => "https://fa-fa-fa.onrender.com",
    'penalty-shootout'     => "https://penulty-shootout.onrender.com",
    'dragonboat'            => "https://dragon-boat-agqg.onrender.com",
    'mythicalphoenix'            => "https://mythical-phoenix.onrender.com",
    'goldenempire'            => "https://golden-empire.onrender.com",
    'megawheel'            => "https://mega-wheel.onrender.com",
    'megawheel'            => "https://mega-wheel.onrender.com",
    'megawheel'            => "https://mega-wheel.onrender.com",
    'megawheel'            => "https://mega-wheel.onrender.com",
    'megawheel'            => "https://mega-wheel.onrender.com",
    'megawheel'            => "https://mega-wheel.onrender.com",
    'megawheel'            => "https://mega-wheel.onrender.com",
    'miniroulette'         => "https://mini-roulette.onrender.com",
    'lightningroulette'    => "https://lighting-roulette.onrender.com",
    'dragon ball fishing'  => "https://dragon-ball-fishing.onrender.com",
    'betlover24fishing'    => "https://betlover24-fishing.onrender.com",
    'fightercrash'         => "https://fighter-crash.onrender.com",
    'moneytree'            => "https://money-tree-s21m.onrender.com"
];

// // ৩. অটো-ইন্টারсеপ্টর গেটওয়ে ড্রাইভার লুপ (আপনার স্ক্রিনশটের ৫৯ নম্বর লাইনের সেই ওল্ড ব্রোকেন জ্যাম ওয়ান-শটে ফিক্সড লক!)
if (array_key_exists($game_get_type, $gameRoutingMap)) {
    $targetBaseUrl = $gameRoutingMap[$game_get_type];
    $game_url = $targetBaseUrl . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else {
    // গেম শর্টকোড না milলে ফলব্যাক ডিফল্ট হিসেবে লাকি হুইল অন ফায়ার রেডি
    $game_url = $gameRoutingMap['luckywheel'] . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
}

// 💰 আপনার পিএইচপি ডাটাবেজ টেবিলের মেইন টাকার কলাম এবং বোনাস ওয়ালেট কলাম সিঙ্ক
$display_balance = $user_data['balance'] ?? $user_data['money'] ?? $user_data['wallet'] ?? 0.00;
$display_pb      = $user_data['pb_balance'] ?? $user_data['pb_wallet'] ?? 0.00;
$display_bonus   = $user_data['bonus_balance'] ?? $user_data['bonus_wallet'] ?? 0.00;
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Casino Framework - Betlover247</title>
    <style>
        html, body { margin: 0; padding: 0; width: 100%; height: 100%; background: #000; overflow: hidden; font-family: sans-serif; }
        .game-header { background: #07090e; height: 50px; display: flex; justify-content: space-between; align-items: center; padding: 0 15px; border-bottom: 1px solid #161b29; box-sizing: border-box; }
        .back-btn { background: linear-gradient(180deg, #ffbb00 0%, #d48800 100%); color: #000; text-decoration: none; padding: 6px 14px; border-radius: 6px; font-weight: 900; font-size: 12px; transition: all 0.15s ease; text-transform: uppercase; }
        .back-btn:active, .history-btn:active { transform: scale(0.96); }
        .wallet-select { background: #111522; color: #ffbb00; border: 1px solid #20293d; padding: 6px 12px; border-radius: 8px; font-weight: bold; font-size: 13px; outline: none; }
        .history-btn { background: #111522; border: 1px solid #20293d; color: #ffffff; text-decoration: none; padding: 6px 14px; border-radius: 6px; font-weight: bold; font-size: 12px; }
        .game-container { width: 100%; height: calc(100vh - 50px); background: #05070c; }
        iframe { width: 100%; height: 100%; border: none; }
    </style>
</head>
<body>

<div class="game-header">
    <a href="index.php" class="back-btn">◀ BACK</a>
    
    <!-- 🎯 [আপনার ওরিজিনাল ডাটাবেজ কলাম সিঙ্ক]: ওয়ালেট সিলেকশন ড্রপডাউন নিয়ন প্যানেল বার -->
    <select id="active_wallet" class="wallet-select" onchange="updateWallet(this.value)">
        <option value="main" <?php if($active_wallet == 'main') echo 'selected'; ?>>Main: ৳<?php echo number_format($display_balance, 2); ?></option>
        <option value="pb" <?php if($active_wallet == 'pb') echo 'selected'; ?>>PB: ৳<?php echo number_format($display_pb, 2); ?></option>
        <option value="bonus" <?php if($active_wallet == 'bonus') echo 'selected'; ?>>Bonus: ৳<?php echo number_format($display_bonus, 2); ?></option>
    </select>

    <a href="bet_logs.php" class="history-btn">HISTORY</a>
</div>

<!-- 🏟️ মেইন ক্যাসিনো লাক্সারি আইফ্রেম রেন্ডারিং সারফেস এরিয়া -->
<div class="game-container">
    <iframe src="<?php echo $game_url; ?>" id="game_frame" allow="autoplay; fullscreen; clipboard-write"></iframe>
</div>

<script>
    // 💰 ১. ডাইনামিক ওয়ালেট সুইচার স্ক্রিপ্ট বর্ম
    function updateWallet(walletType) {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('wallet', walletType);
        window.location.href = currentUrl.toString();
    }

    // 🔄 ২. International রিয়েল-টাইম ব্যালেন্স সিঙ্ক (খেলা শেষ হতেই ওপরের ওয়ালেট ইনস্ট্যান্ট রিফ্রেশ ট্রিক!)
    window.addEventListener("message", function(event) {
        if (event.data && event.data.action === "refresh_wallet") {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    const selectors = ['#active_wallet', '#balance', '#main-balance', '.user-balance', '.balance-amount'];
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
