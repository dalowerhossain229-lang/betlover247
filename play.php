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

// // ডাটাবেজ থেকে রিয়েল-টাইমে প্লেয়ারের ওরিজিনাল প্রোফাইল ও অ্যাকাউন্ট ব্যালেন্স ডেটা আনা
$query = $conn->query("SELECT * FROM users WHERE username = '$u' OR id = '$u'");
$user_data = $query->fetch_assoc();

$active_wallet = isset($_GET['wallet']) ? $_GET['wallet'] : ($_GET['wallet'] ?? 'main');
$game_get_type = isset($_GET['game']) ? $_GET['game'] : '';

// 🔒 [গ্র্যান্ড ব্যালেন্স জিরো ফিক্সড ট্রিক]: 
// গেমের ভেতরে আইডি পাস করার সময় ওরিজিনাল ডাটাবেজে থাকা প্লেয়ারের আসল 'username' এক শটে পাস লক করা হলো ওস্তাদ!
// এর ফলে নোড সার্ভার ব্যাকএন্ড এবং api_callback.php ওয়ান-শটে আসল প্লেয়ারের প্রোফাইল রিড করে ওরিজিনাল টাকা শো করবে ভাই ভাই!
$game_user_id = !empty($user_data['username']) ? $user_data['username'] : (!empty($u) ? $u : "guest");

// ===================================================================================
// // ২. গ্র্যান্ড ডাইনামিক গেম ইউআরএল অবজেক্ট ডিরেক্টরি (আপনার স্ক্রিনশটের হুবহু ওরিজিনাল স্লট ওッズ সিঙ্ক ওস্তাদ!)
// ===================================================================================
$gameRoutingMap = [
    'luckywheel'           => "https://onrender.com",
    'superace'             => "https://super-ace.onrender.com",
    'ludo'                 => "https://onrender.com",
    'color'                => "https://onrender.com",
    'cryptomultiply'       => "https://onrender.com",
    'royalkeno'            => "https://onrender.com",
    'royalderby'           => "https://onrender.com",
    'twistmultiplespin'    => "https://onrender.com",
    'billionaireslot'      => "https://onrender.com",
    'chicken'              => "https://onrender.com",
    'mines'                => "https://onrender.com",
    'andarbahar'           => "https://onrender.com",
    'baccaratmaster'       => "https://onrender.com",
    'blackjack21'          => "https://onrender.com",
    'dice3d'               => "https://onrender.com",
    'dragontiger'          => "https://onrender.com",
    'hilocard'             => "https://onrender.com",
    'jhandimunda'          => "https://onrender.com",
    'luckymarblepop'       => "https://onrender.com",
    'moneycoming'          => "https://onrender.com",
    'royalplinko'          => "https://onrender.com",
    'teenpatti'            => "https://onrender.com",
    'fish-prawn-crab'      => "https://onrender.com",
    'fantan'               => "https://onrender.com",
    'sicbo'                => "https://onrender.com",
    'penalty-shootout'     => "https://onrender.com",
    'megawheel'            => "https://onrender.com",
    'miniroulette'         => "https://onrender.com",
    'lightningroulette'    => "https://onrender.com",
    'dragon ball fishing'  => "https://onrender.com",
    'betlover24fishing'    => "https://onrender.com",
    'fightercrash'         => "https://onrender.com",
    'moneytree'            => "https://onrender.com"
];

// // ৩. অটো-ইন্টারসেপ্টর গেটওয়ে ড্রাইভার লুপ (আপনার স্ক্রিনশটের ৫৯ নম্বর লাইনের সেই ওল্ড ব্রোকেন জ্যাম ওয়ান-শটে ফিক্সড লক!)
if (array_key_exists($game_get_type, $gameRoutingMap)) {
    $targetBaseUrl = $gameRoutingMap[$game_get_type];
    $game_url = $targetBaseUrl . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
} else {
    // গেম শর্টকোড না মিললে ফলব্যাক ডিফল্ট হিসেবে লাকি হুইল অন ফায়ার রেডি
    $game_url = $gameRoutingMap['luckywheel'] . "/?userId=" . urlencode($game_user_id) . "&wallet=" . urlencode($active_wallet);
}

// 💰 আপনার পিএইচ局 ডাটাবেজ টেবিলের মেইন টাকার কলাম ফিল্টারিং চ্যাম সিঙ্ক
$display_balance = $user_data['money'] ?? $user_data['balance'] ?? $user_data['wallet'] ?? 0.00;
$display_pb      = $user_data['pb_wallet'] ?? $user_data['pb'] ?? 0.00;
$display_bonus   = $user_data['bonus_wallet'] ?? $user_data['bonus'] ?? 0.00;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Game Frame Test - Betlover247</title>
    <style>
        html, body { margin: 0; padding: 0; width: 100%; height: 100%; background: #000; overflow: hidden; font-family: sans-serif; }
        .game-header { background: #000; height: 50px; display: flex; justify-content: space-between; align-items: center; padding: 0 15px; border-bottom: 2px solid #00ff88; box-sizing: border-box; }
        .back-btn { background: #00ff88; color: #000; text-decoration: none; padding: 6px 15px; border-radius: 5px; font-weight: bold; font-size: 13px; transition: all 0.2s; }
        .back-btn:active { transform: scale(0.95); }
        .wallet-select { background: #111; color: #ffdd1b; border: 1px solid #333; padding: 6px 12px; border-radius: 6px; font-weight: bold; font-size: 14px; outline: none; }
        .game-container { width: 100%; height: calc(100vh - 50px); background: #111; }
        iframe { width: 100%; height: 100%; border: none; }
    </style>
</head>
<body>

<div class="game-header">
    <a href="index.php" class="back-btn">◀ BACK</a>
    
    <!-- 💰 ডাইনামিক ক্যাশ ওয়ালেট ডিসপ্লে প্যানেল সিঙ্ক -->
    <select id="active_wallet" class="wallet-select" onchange="updateWallet(this.value)">
        <option value="main" <?php echo $active_wallet == 'main' ? 'selected' : ''; ?>>Main: ৳<?php echo number_format($user_data['wallet'] ?? 0, 2); ?></option>
        <option value="pb" <?php echo $active_wallet == 'pb' ? 'selected' : ''; ?>>PB: ৳<?php echo number_format($user_data['pb_wallet'] ?? 0, 2); ?></option>
        <option value="bonus" <?php echo $active_wallet == 'bonus' ? 'selected' : ''; ?>>Bonus: ৳<?php echo number_format($user_data['bonus_wallet'] ?? 0, 2); ?></option>
    </select>
</div>

<!-- 🏟️ মেইন ক্যাসিনো লাক্সারি আইফ্রেম রেন্ডারিং সারফেস এরিয়া (১০০% সিকিউরড গেটওয়ে) -->
<div class="game-container">
    <iframe src="<?php echo $game_url; ?>" id="game_frame" allow="autoplay; fullscreen; clipboard-write"></iframe>
</div>

<script>
    // 💰 ১. ডাইনামিক ওয়ালেট সুইচার স্ক্রিপ্ট ইন্টারসেপ্টর বর্ম
    function updateWallet(walletType) {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('wallet', walletType);
        window.location.href = currentUrl.toString();
    }

    // 🔄 ২. ইন্টারন্যাশনাল রিয়েল-টাইম ব্যালেন্স সিঙ্ক (খেলা শেষ হতেই ওপরের ওয়ালেট ইনস্ট্যান্ট রিফ্রেশ ট্রিক!)
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
