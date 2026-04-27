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
// ইউজারের সব ডাটা একবারেই আনা
$u_data = $conn->query("SELECT * FROM users WHERE username = '$u'")->fetch_assoc();

// ২. অ্যাডমিন সেটিংস থেকে টার্গেটগুলো আনা
$st = $conn->query("SELECT * FROM settings WHERE id = 1")->fetch_assoc();
$t_bonus = (float)($st['bonus_target'] ?? 5000);
$t_pb = (float)($st['pb_target'] ?? 10000);

// ৩. মেইন টার্নওভার টার্গেট = ইউজারের মোট সফল ডিপোজিট
$dep_res = $conn->query("SELECT SUM(amount) as t_dep FROM deposits WHERE username = '$u' AND status = 'success'")->fetch_assoc();
$t_main = (float)($dep_res['t_dep'] ?? 1000); 

// ৪. ৩টি ব্যালেন্স আলাদা করা (মেইন ও পিবি একসাথে যোগ করা)
$main_b = (float)($u_data['balance'] ?? 0);
$pb_b = (float)($u_data['pb_balance'] ?? 0);
$bonus_b = (float)($u_data['bonus_balance'] ?? 0);
$total_display_balance = $main_b + $pb_b;

// ৫. ৩টি টার্নওভারের ডাটা নেওয়া
$main_t = (float)($u_data['turnover'] ?? 0);
$bonus_t = (float)($u_data['bonus_turnover'] ?? 0);
$pb_t = (float)($u_data['pb_turnover'] ?? 0);

// প্রগ্রেস বার ফাংশন
function getBar($done, $target) {
    $p = ($target > 0) ? ($done / $target) * 100 : 0;
    return ($p > 100) ? 100 : $p;
}
?>

<div style="padding: 15px; text-align: center; color: white; font-family: sans-serif; background: #0a0b10; min-height: 100vh;">
    
    <!-- ব্যালেন্স ও টার্নওভার সেকশন (বামে রানিং / ডানে টার্গেট) -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px;">
        
        <!-- বাম পাশ: মেইন টার্নওভার বক্স -->
        <div style="background:rgba(0,255,136,0.05); border:1px solid #00ff88; padding:15px; border-radius:15px; text-align: left;">
            <small style="color:#888; font-size:10px; text-transform:uppercase; display:block; margin-bottom:5px;">Main Turnover</small>
            <div style="font-weight:bold; font-size:15px;">
                <?php if($main_t >= $t_main): ?>
                    <span style="color:#00ff88;">COMPLETED ✅</span>
                <?php else: ?>
                    <span style="color:#00ff88;"><?php echo number_format($main_t, 0); ?></span>
                    <span style="color:#555; font-size:11px;"> / <?php echo number_format($t_main, 0); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <!-- ডান পাশ: মেইন ব্যালেন্স বক্স (Main + PB) -->
        <div style="background:rgba(255,223,27,0.05); border:1px solid #ffdf1b; padding:15px; border-radius:15px; text-align: right;">
            <small style="color:#888; font-size:10px; text-transform:uppercase; display:block; margin-bottom:5px;">Main Balance</small>
            <div style="color:#ffdf1b; font-weight:bold; font-size:18px;">
                ৳ <?php echo number_format($total_display_balance, 2); ?>
            </div>
        </div>
    </div>

    <!-- ৩টি প্রগ্রেস বার সেকশন -->
    <div style="background: #111; padding: 20px; border-radius: 20px; text-align: left; border: 1px solid #222;">
        <!-- ১. Main Bar -->
        <div style="margin-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 8px;">
                <span style="color: #00ff88; font-weight: bold;">MAIN PROGRESS</span>
                <span style="color: #888;"><?php echo number_format($main_t, 0); ?> / <?php echo number_format($t_main, 0); ?></span>
            </div>
            <div style="width: 100%; background: #222; height: 6px; border-radius: 10px;">
                <div style="width: <?php echo getBar($main_t, $t_main); ?>%; background: #00ff88; height: 100%; border-radius: 10px;"></div>
            </div>
        </div>

        <!-- ২. Bonus Bar -->
        <div style="margin-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 8px;">
                <span style="color: #ffdf1b; font-weight: bold;">BONUS TURNOVER</span>
                <span style="color: #888;"><?php echo number_format($bonus_t, 0); ?> / <?php echo number_format($t_bonus, 0); ?></span>
            </div>
            <div style="width: 100%; background: #222; height: 6px; border-radius: 10px;">
                <div style="width: <?php echo getBar($bonus_t, $t_bonus); ?>%; background: #ffdf1b; height: 100%; border-radius: 10px;"></div>
            </div>
        </div>

        <!-- ৩. PB Bar -->
        <div style="margin-bottom: 10px;">
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 8px;">
                <span style="color: #00bcd4; font-weight: bold;">PB TURNOVER</span>
                <span style="color: #888;"><?php echo number_format($pb_t, 0); ?> / <?php echo number_format($t_pb, 0); ?></span>
            </div>
            <div style="width: 100%; background: #222; height: 6px; border-radius: 10px;">
                <div style="width: <?php echo getBar($pb_t, $t_pb); ?>%; background: #00bcd4; height: 100%; border-radius: 10px;"></div>
            </div>
        </div>
    </div>

    <!-- ৪. BONUS CLAIM BUTTON -->
    <div style="margin-top: 20px; margin-bottom: 25px;">
        <?php if($bonus_t >= $t_bonus && $bonus_b > 0): ?>
            <button onclick="claimBonus()" style="width:100%; padding:15px; background:#00ff88; border:none; border-radius:12px; color:#000; font-weight:bold; cursor:pointer; text-transform:uppercase; font-size:13px;">CLAIM BONUS TO MAIN</button>
        <?php else: ?>
            <button disabled style="width:100%; padding:15px; background:#1a1a1a; border:1px solid #333; border-radius:12px; color:#666; font-weight:bold; text-transform:uppercase; font-size:13px;">🔒 BONUS LOCKED</button>
        <?php endif; ?>
    </div>

    <!-- ৫. মেনু বাটন লিস্ট -->
    <div style="display: flex; flex-direction: column; gap: 8px;">
        <button class="p-btn" onclick="location.href='live_chat.php'">Live Chat Support ></button>
        <button class="p-btn" onclick="location.href='history.php'">Transaction History ></button>
        <button class="p-btn" onclick="openPaymentSettings()" style="border-color: #00ff88; color: #00ff88;">Payment Accounts (Lock)</button>
        <button class="p-btn" onclick="location.href='update_password.php'">Change Password ></button>
        <button class="p-btn" onclick="handleLogout()" style="border-color: #ff4d4d; color: #ff4d4d; margin-top: 10px;">Logout Account</button>
    </div>

    <!-- ৬. পেমেন্ট নম্বর লক সেকশন -->
    <div id="paymentSection" style="display:none; background: #111; padding: 20px; border-radius: 15px; border: 1px solid #00ff88; margin-top: 15px; text-align: left;">
        <h4 style="color: #00ff88; margin-top: 0; font-size: 14px;">🔒 LOCK ACCOUNTS</h4>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div style="display: flex; justify-content: space-between;">
                <small style="color: #888;">Bkash:</small>
                <?php if(empty($u_data['p_bkash'])): ?>
                    <input type="number" id="new_bkash" style="background:#000; border:1px solid #333; color:#fff; padding:5px; width:120px;">
                <?php else: ?>
                    <span style="color: #00ff88; font-weight: bold;"><?php echo $u_data['p_bkash']; ?> 🔒</span>
                <?php endif; ?>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <small style="color: #888;">Nagad:</small>
                <?php if(empty($u_data['p_nagad'])): ?>
                    <input type="number" id="new_nagad" style="background:#000; border:1px solid #333; color:#fff; padding:5px; width:120px;">
                <?php else: ?>
                    <span style="color: #00ff88; font-weight: bold;"><?php echo $u_data['p_nagad']; ?> 🔒</span>
                <?php endif; ?>
            </div>
            <?php if(empty($u_data['p_bkash']) || empty($u_data['p_nagad'])): ?>
                <button onclick="saveNumbers()" style="background:#00ff88; color:#000; border:none; padding:10px; border-radius:8px; font-weight:bold; cursor:pointer;">LOCK NOW</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .p-btn { width: 100%; padding: 15px; background: #0a0f0d; border: 1px solid #1a2a22; border-radius: 12px; color: white; text-align: left; font-weight: 600; font-size: 13px; cursor: pointer; }
</style>

<script>
function openPaymentSettings() {
    const box = document.getElementById('paymentSection');
    box.style.display = (box.style.display === 'none') ? 'block' : 'none';
}
function handleLogout() {
    if(confirm("Are you sure you want to logout?")) location.href = 'logout.php';
}
function saveNumbers() {
    const bkash = document.getElementById('new_bkash')?.value || "";
    const nagad = document.getElementById('new_nagad')?.value || "";
    if(!bkash && !nagad) return alert("Enter a number!");
    if(confirm("Lock these numbers? Cannot be changed later!")) {
        let fd = new FormData();
        fd.append('bkash', bkash); fd.append('nagad', nagad);
        fetch('process_save_numbers.php', { method: 'POST', body: fd })
        .then(res => res.json()).then(data => {
            alert(data.message); if(data.status === 'success') location.reload();
        });
    }
}
</script>
<?php include 'footer.php'; ?>


        
