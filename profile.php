<?php 
session_start();
include 'header.php'; 
include 'db.php'; 

// ১. লগইন চেক
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$u = $_SESSION['user_id'];
$u_data = $conn->query("SELECT * FROM users WHERE username = '$u'")->fetch_assoc();

// ২. অ্যাডমিন সেটিংস থেকে টার্গেটগুলো আনা
$st = $conn->query("SELECT * FROM settings WHERE id = 1")->fetch_assoc();
$t_bonus = (float)($st['bonus_target'] ?? 5000);
$t_pb = (float)($st['pb_target'] ?? 10000);

// ৩. মেইন টার্নওভার টার্গেট = ইউজারের মোট সফল ডিপোজিট (আপনার নকশা অনুযায়ী)
$dep_res = $conn->query("SELECT SUM(amount) as t_dep FROM deposits WHERE username = '$u' AND status = 'success'")->fetch_assoc();
$t_main = (float)($dep_res['t_dep'] ?? 1000); 

// ৪. ৩টি ব্যালেন্সের ডাটা নেওয়া
$main_b = (float)($u_data['balance'] ?? 0);
$pb_b = (float)($u_data['pb_balance'] ?? 0);
$bonus_b = (float)($u_data['bonus_balance'] ?? 0);

// ৫. ৩টি টার্নওভারের ডাটা নেওয়া (রানিং)
$main_t = (float)($u_data['turnover'] ?? 0);
$bonus_t = (float)($u_data['bonus_turnover'] ?? 0);
$pb_t = (float)($u_data['pb_turnover'] ?? 0);

// প্রগ্রেস বার ক্যালকুলেশন ফাংশন
function getBar($done, $target) {
    $p = ($target > 0) ? ($done / $target) * 100 : 0;
    return ($p > 100) ? 100 : $p;
}
?>

<div style="padding: 15px; text-align: center; color: white; font-family: sans-serif; background: #0a0b10; min-height: 100vh;">
    
    <!-- ৩টি ব্যালেন্স বক্স (নকশা অনুযায়ী) -->
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; margin-bottom: 20px;">
        <div style="background:#111; border:1px solid #00ff88; padding:10px; border-radius:10px;">
            <small style="font-size:9px; color:#888; text-transform:uppercase;">Main</small>
            <div style="color:#00ff88; font-size:12px; font-weight:bold;">৳<?php echo number_format($main_b, 2); ?></div>
        </div>
        <div style="background:#111; border:1px solid #00bcd4; padding:10px; border-radius:10px;">
            <small style="font-size:9px; color:#888; text-transform:uppercase;">PB</small>
            <div style="color:#00bcd4; font-size:12px; font-weight:bold;">৳<?php echo number_format($pb_b, 2); ?></div>
        </div>
        <div style="background:#111; border:1px solid #ffdf1b; padding:10px; border-radius:10px;">
            <small style="font-size:9px; color:#888; text-transform:uppercase;">Bonus</small>
            <div style="color:#ffdf1b; font-size:12px; font-weight:bold;">৳<?php echo number_format($bonus_b, 2); ?></div>
        </div>
    </div>

    <!-- ৩টি টার্নওভার লাইন (আপনার হাতে লেখা নকশা অনুযায়ী) -->
    <div style="background: #111; padding: 20px; border-radius: 20px; text-align: left; border: 1px solid #222;">
        
        <!-- লাইন ১: মেইন টার্নওভার -->
        <div style="margin-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 8px;">
                <span style="color: #00ff88; font-weight: bold;">MAIN TURNOVER</span>
                <span>
                    <?php if($main_t >= $t_main): ?> <b style="color:#00ff88;">COMPLETE ✅</b> 
                    <?php else: echo number_format($main_t, 0)." / ".number_format($t_main, 0); endif; ?>
                </span>
            </div>
            <div style="width: 100%; background: #222; height: 5px; border-radius: 10px;">
                <div style="width: <?php echo getBar($main_t, $t_main); ?>%; background: #00ff88; height: 100%; border-radius: 10px;"></div>
            </div>
        </div>

        <!-- লাইন ২: পিবি টার্নওভার -->
        <div style="margin-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 8px;">
                <span style="color: #00bcd4; font-weight: bold;">PB TURNOVER</span>
                <span>
                    <?php if($pb_t >= $t_pb): ?> <b style="color:#00bcd4;">COMPLETE ✅</b> 
                    <?php else: echo number_format($pb_t, 0)." / ".number_format($t_pb, 0); endif; ?>
                </span>
            </div>
            <div style="width: 100%; background: #222; height: 5px; border-radius: 10px;">
                <div style="width: <?php echo getBar($pb_t, $t_pb); ?>%; background: #00bcd4; height: 100%; border-radius: 10px;"></div>
            </div>
        </div>

        <!-- লাইন ৩: বোনাস টার্নওভার -->
        <div style="margin-bottom: 10px;">
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 8px;">
                <span style="color: #ffdf1b; font-weight: bold;">BONUS TURNOVER</span>
                <span>
                    <?php if($bonus_t >= $t_bonus): ?> <b style="color:#ffdf1b;">COMPLETE ✅</b> 
                    <?php else: echo number_format($bonus_t, 0)." / ".number_format($t_bonus, 0); endif; ?>
                </span>
            </div>
            <div style="width: 100%; background: #222; height: 5px; border-radius: 10px;">
                <div style="width: <?php echo getBar($bonus_t, $t_bonus); ?>%; background: #ffdf1b; height: 100%; border-radius: 10px;"></div>
            </div>
        </div>
    </div>

    <!-- কুইক মেনু বাটনসমূহ -->
    <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 30px;">
        <button class="p-btn" onclick="location.href='deposit.php'">Deposit Funds</button>
        <button class="p-btn" onclick="location.href='withdraw.php'">Withdraw Money</button>
        <button class="p-btn" onclick="location.href='logout.php'" style="border-color: #ff4d4d; color: #ff4d4d; margin-top: 10px;">Logout Account</button>
    </div>
</div>

<style>
    .p-btn { width: 100%; padding: 15px; background: #0d1117; border: 1px solid #1a2a22; border-radius: 12px; color: white; text-align: left; font-weight: 600; font-size: 13px; cursor: pointer; transition: 0.2s; margin-bottom: 5px; }
    .p-btn:active { transform: scale(0.98); }
</style>

<?php include 'footer.php'; ?>
