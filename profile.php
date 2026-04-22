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
$u_res = $conn->query("SELECT * FROM users WHERE username = '$u'");
$u_data = $u_res->fetch_assoc();

// ২. টার্নওভার ক্যালকুলেশন
$target = $u_data['turnover_target'] ?? 1000;
$completed = $u_data['turnover_completed'] ?? 0;
$percent = ($target > 0) ? ($completed / $target) * 100 : 0;
if($percent > 100) $percent = 100;
?>

<div style="padding: 15px; text-align: center; color: white; font-family: sans-serif;">
    
    <!-- ব্যালেন্স সেকশন -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px;">
        <div style="background:rgba(0,255,136,0.05); border:1px solid #00ff88; padding:15px; border-radius:12px; box-shadow: 0 0 10px rgba(0,255,136,0.1);">
            <small style="color:#aaa; font-size:10px; text-transform:uppercase;">Main Balance</small>
            <h2 style="color:#ffdf1b; margin:5px 0;">৳ <?php echo number_format($u_data['balance'], 2); ?></h2>
        </div>
        <div style="background:rgba(255,223,27,0.05); border:1px solid #ffdf1b; padding:15px; border-radius:12px;">
            <small style="color:#aaa; font-size:10px; text-transform:uppercase;">Bonus Balance</small>
            <h2 style="color:#00ff88; margin:5px 0;">৳ <?php echo number_format($u_data['bonus_balance'] ?? 0, 2); ?></h2>
        </div>
    </div>

    <!-- টার্নওভার প্রগ্রেস বার -->
    <div style="background: #0a0f0d; border: 1px solid #1a2a22; padding: 15px; border-radius: 12px; margin-bottom: 20px; text-align: left;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
            <span style="font-size:11px; font-weight:bold; color:#888;">TURNOVER PROGRESS</span>
            <span style="font-size:11px; font-weight:bold; color:#00ff88;"><?php echo number_format($completed); ?> / <?php echo number_format($target); ?></span>
        </div>
        <div style="width: 100%; height: 8px; background: #111; border-radius: 10px; overflow: hidden; border: 1px solid #222;">
            <div style="width: <?php echo $percent; ?>%; height: 100%; background: linear-gradient(90deg, #00ff88, #00aa5d); box-shadow: 0 0 8px #00ff88;"></div>
        </div>
        <p style="color:#444; font-size:9px; margin-top:8px; text-align:center;">বোনাস আনলক করতে টার্নওভার সম্পন্ন করুন।</p>
    </div>

    <!-- মেনু বাটন লিস্ট -->
    <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
        <button class="p-btn" onclick="location.href='live_chat.php'">Live Chat ></button>
        <button class="p-btn" onclick="location.href='history.php'">Transaction History ></button>
        <button class="p-btn" onclick="location.href='bet_history.php'">Bet History ></button>
        
        <!-- পেমেন্ট নম্বর বাটন (লক বক্স খুলবে) -->
        <button class="p-btn" onclick="openPaymentSettings()" style="border-color: #00ff88; color: #00ff88; background: rgba(0,255,136,0.02);">Payment Numbers</button>
        
        <button class="p-btn" onclick="location.href='update_password.php'">Update Password ></button>
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
            
