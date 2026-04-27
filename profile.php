<?php 
session_start();
include 'header.php'; 
include 'db.php'; 

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$u = $_SESSION['user_id'];
// ইউজারের সব ডাটা একবারেই আনা
$u_data = $conn->query("SELECT * FROM users WHERE username = '$u'")->fetch_assoc();


// অ্যাডমিন সেটিংস থেকে টার্গেটগুলো নিয়ে আসা
$st_res = $conn->query("SELECT * FROM settings WHERE id = 1");
$st = $st_res->fetch_assoc();

$t_main = (float)($st['main_target'] ?? 1000);
$t_bonus = (float)($st['bonus_target'] ?? 12000);
$t_pb = (float)($st['pb_target'] ?? 360000);

// ৩টি ব্যালেন্স আলাদা করা
$main_b = (float)($u_data['balance'] ?? 0);
$pb_b = (float)($u_data['pb_balance'] ?? 0);
$bonus_b = (float)($u_data['bonus_balance'] ?? 0);

// মেইন এবং পিবি ব্যালেন্স যোগ করে দেখানো (আপনার চাহিদা অনুযায়ী)
$total_display_balance = $main_b + $pb_b;

// ৩টি টার্নওভারের ডাটা নেওয়া
$main_t = (float)($u_data['turnover'] ?? 0);
$bonus_t = (float)($u_data['bonus_turnover'] ?? 0);
$pb_t = (float)($u_data['pb_turnover'] ?? 0);


// টার্নওভার প্রগ্রেস ফাংশন
function getBar($done, $target) {
    $p = ($target > 0) ? ($done / $target) * 100 : 0;
    return ($p > 100) ? 100 : $p;
}
?>
<div style="padding: 15px; text-align: center; color: white; font-family: sans-serif;">
    
    <!-- ব্যালেন্স সেকশন -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px;">
        
        <!-- মেইন + পিবি ব্যালেন্স (একত্রে) -->
        <div style="background:rgba(0,255,136,0.05); border:1px solid #00ff88; padding:15px; border-radius:12px;">
            <small style="color:#aaa; font-size:10px; text-transform:uppercase;">Main Balance</small>
            <h2 style="color:#ffdf1b; margin:5px 0;">৳ <?php echo number_format($total_display_balance, 2); ?></h2>
        </div>  
        <!-- বোনাস ব্যালেন্স বক্স -->
        <div style="background:rgba(255,223,27,0.05); border:1px solid #ffdf1b; padding:15px; border-radius:12px;">
            <small style="color:#aaa; font-size:10px; text-transform:uppercase;">Bonus Balance</small>
            <h2 style="color:#00ff88; margin:5px 0;">৳ <?php echo number_format($bonus_b, 2); ?></h2>
        </div>
    </div>

    <!-- ৩টি আলাদা টার্নওভার প্রগ্রেস বার -->
    <div style="background: #0a0f0d; border: 1px solid #1a2a22; padding: 15px; border-radius: 15px;">
        
        <div style="background: #111; padding: 15px; border-radius: 15px; border: 1px solid #1a2a22;">
        
        <!-- ১. MAIN TURNOVER -->
        <div style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                <span style="font-size:10px; font-weight:bold; color:#00ff88;">MAIN TURNOVER</span>
                <span style="font-size:10px; color:#888;"><?php echo number_format($main_t, 0); ?> / <?php echo number_format($t_main, 0); ?></span>
            </div>
            <div style="width: 100%; height: 6px; background: #222; border-radius: 10px;">
                <div style="width: <?php echo getBar($main_t, $t_main); ?>%; background: #00ff88; height: 100%; border-radius: 10px;"></div>
            </div>
        </div>

        <!-- ২. BONUS TURNOVER -->
        <div style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                <span style="font-size:10px; font-weight:bold; color:#ffdf1b;">BONUS TURNOVER</span>
                <span style="font-size:10px; color:#888;"><?php echo number_format($bonus_t, 0); ?> / <?php echo number_format($t_bonus, 0); ?></span>
            </div>
            <div style="width: 100%; height: 6px; background: #222; border-radius: 10px;">
                <div style="width: <?php echo getBar($bonus_t, $t_bonus); ?>%; background: #ffdf1b; height: 100%; border-radius: 10px;"></div>
            </div>
        </div>

        <!-- ৩. PB TURNOVER -->
        <div style="margin-bottom: 10px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                <span style="font-size:10px; font-weight:bold; color:#00bcd4;">PB TURNOVER</span>
                <span style="font-size:10px; color:#888;"><?php echo number_format($pb_t, 0); ?> / <?php echo number_format($t_pb, 0); ?></span>
            </div>
            <div style="width: 100%; height: 6px; background: #222; border-radius: 10px;">
                <div style="width: <?php echo getBar($pb_t, $t_pb); ?>%; background: #00bcd4; height: 100%; border-radius: 10px;"></div>
            </div>
        </div>      
  

    <!-- 8. BONUS CLAIM BUTTON -->
    <div style="margin-top: 20px;">
        <?php 
        // অ্যাডমিন টার্গেট এবং বোনাস ব্যালেন্স চেক
        if($bonus_t >= $t_bonus && $bonus_b > 0): 
        ?>
            <button onclick="claimBonus()" style="width:100%; padding:15px; background:#00ff88; border:none; border-radius:12px; color:#000; font-weight:bold; cursor:pointer; text-transform:uppercase; font-size:13px; box-shadow: 0 4px 15px rgba(0,255,136,0.3);">
                CLAIM BONUS TO MAIN
            </button>
        <?php else: ?>
            <button disabled style="width:100%; padding:15px; background:#1a1a1a; border:1px solid #333; border-radius:12px; color:#666; font-weight:bold; text-transform:uppercase; font-size:13px; cursor: not-allowed;">
                🔒 BONUS LOCKED (COMPLETE TURNOVER)
            </button>
        <?php endif; ?>
    </div>

    


    <!-- মেনু বাটন লিস্ট -->
    <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
        <button class="p-btn" onclick="location.href='live_chat.php'">Live Chat ></button>
        <button class="p-btn" onclick="location.href='history.php'">Transaction History ></button>
        <button class="p-btn" onclick="location.href='bet_logs.php'">Bet History ></button>

        
        <!-- পেমেন্ট নম্বর বাটন (লক বক্স খুলবে) -->
        <button class="p-btn" onclick="openPaymentSettings()" style="border-color: #00ff88; color: #00ff88; background: rgba(0,255,136,0.02);">Payment Numbers</button>
        
        <button class="p-btn" onclick="location.href='update_password.php'">Update Password ></button>
        <!-- শুধুমাত্র অনুমোদিত অ্যাফিলিয়েটদের জন্য বাটন -->
<?php if(isset($u_data['is_affiliate']) && $u_data['is_affiliate'] == 1): ?>
    <button class="p-btn" onclick="location.href='affiliate.php'" style="border-color: #ffdf1b; color: #ffdf1b;">🤝 Affiliate Program ></button>
<?php endif; ?>

        <button class="p-btn" onclick="handleLogout()" style="border-color: rgba(255,77,77,0.2); color: #ff4d4d; background: rgba(255,77,77,0.02);">Logout</button>
    </div>

    <!-- পেমেন্ট নম্বর লক সেকশন (ID সহ) -->
    <div id="paymentSection" style="display:none; background: #0d1117; padding: 15px; border-radius: 12px; border: 1px solid #00ff88; margin-top: 10px; animation: slideDown 0.3s ease-out;">
        <h4 style="color: #00ff88; margin-top: 0; font-size: 14px;">🔒 পেমেন্ট নম্বর সেভ ও লক</h4>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <small style="color: #888;">Bkash Personal:</small>
                <?php if(empty($u_data['p_bkash'])): ?>
                    <input type="number" id="new_bkash" placeholder="নম্বর দিন" style="background:#000; border:1px solid #333; color:#fff; padding:8px; border-radius:5px; width:140px; outline:none;">
                <?php else: ?>
                    <span style="color: #ffdf1b; font-weight: bold;"><?php echo $u_data['p_bkash']; ?> ✅</span>
                <?php endif; ?>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <small style="color: #888;">Nagad Personal:</small>
                <?php if(empty($u_data['p_nagad'])): ?>
                    <input type="number" id="new_nagad" placeholder="নম্বর দিন" style="background:#000; border:1px solid #333; color:#fff; padding:8px; border-radius:5px; width:140px; outline:none;">
                <?php else: ?>
                    <span style="color: #ffdf1b; font-weight: bold;"><?php echo $u_data['p_nagad']; ?> ✅</span>
                <?php endif; ?>
            </div>
            <?php if(empty($u_data['p_bkash']) || empty($u_data['p_nagad'])): ?>
                <button onclick="saveNumbers()" style="background:#00ff88; color:#000; border:none; padding:12px; border-radius:8px; font-weight:bold; cursor:pointer; margin-top:10px;">নম্বর লক করুন (স্থায়ী)</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .p-btn { width: 100%; padding: 15px; background: #0a0f0d; border: 1px solid #1a2a22; border-radius: 10px; color: white; text-align: left; font-weight: 600; font-size: 13px; cursor: pointer; transition: 0.2s; }
    .p-btn:active { transform: scale(0.98); }
    @keyframes slideDown { from {opacity:0; transform:translateY(-10px);} to {opacity:1; transform:translateY(0);} }
</style>

<script>
function openPaymentSettings() {
    const box = document.getElementById('paymentSection');
    box.style.display = (box.style.display === 'none' || box.style.display === '') ? 'block' : 'none';
    if(box.style.display === 'block') box.scrollIntoView({ behavior: 'smooth' });
}

function handleLogout() {
    if(confirm("আপনি কি নিশ্চিতভাবে লগআউট করতে চান?")) location.href = 'logout.php';
}

function saveNumbers() {
    const bkash = document.getElementById('new_bkash')?.value || "";
    const nagad = document.getElementById('new_nagad')?.value || "";
    if(!bkash && !nagad) { alert("অন্তত একটি নম্বর দিন!"); return; }
    if(!confirm("সাবধান! একবার সেভ করলে এই নম্বর আর বদলানো যাবে না। আপনি কি নিশ্চিত?")) return;

    let fd = new FormData();
    fd.append('bkash', bkash);
    fd.append('nagad', nagad);

    fetch('process_save_numbers.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.status === 'success') location.reload();
    })
    .catch(() => alert("সার্ভার কানেকশন এরর!"));
}
</script>
<?php include 'footer.php'; ?>
            
