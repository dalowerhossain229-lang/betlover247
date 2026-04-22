<?php
// ১. সেশন এবং সিকিউরিটি চেক (সবার আগে থাকতে হবে)
session_start();
include 'db.php';

// ইউজার লগইন না থাকলে ইনডেক্স পেজে পাঠিয়ে দিবে
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// ২. ডাটাবেস থেকে ইউজারের সব তথ্য নিয়ে আসা
$user = $_SESSION['user_id'];
$res = $conn->query("SELECT * FROM users WHERE username = '$user'");
$userData = $res->fetch_assoc();

// ডাটাবেস ভ্যালু (যদি ডাটাবেসে কলাম না থাকে তবে ০ দেখাবে)
$balance = (float)($userData['balance'] ?? 0.00);
$bonus = (float)($userData['bonus_balance'] ?? 0.00);
$turnover_done = (float)($userData['turnover_done'] ?? 0.00);
$turnover_target = (float)($userData['turnover_target'] ?? 1000.00);

// টার্নওভার পার্সেন্টেজ হিসাব
$turnover_percent = ($turnover_target > 0) ? ($turnover_done / $turnover_target) * 100 : 0;
if($turnover_percent > 100) $turnover_percent = 100;

// ৩. হেডার ইনক্লুড করা (সব রিডাইরেক্ট চেক শেষ করার পর)
include 'header.php'; 
?>

<div class="profile-page" style="padding: 15px; max-width: 500px; margin: auto;">
    
    <!-- ব্যালেন্স কার্ড সেকশন -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 10px;">
        <div style="background: linear-gradient(135deg, #073128, #000); border: 1.5px solid var(--neon); padding: 15px; border-radius: 12px; text-align: center; box-shadow: 0 5px 15px rgba(0,255,136,0.1);">
            <small style="color: #aaa; text-transform: uppercase; font-size: 10px;">Main Balance</small>
            <h2 style="color: var(--gold); margin: 5px 0; font-size: 20px;">৳ <?php echo number_format($balance, 2); ?></h2>
        </div>
        
        <div style="background: linear-gradient(135deg, #2d2600, #000); border: 1.5px solid var(--gold); padding: 15px; border-radius: 12px; text-align: center; box-shadow: 0 5px 15px rgba(255,223,27,0.1);">
            <small style="color: #aaa; text-transform: uppercase; font-size: 10px;">Bonus Balance</small>
            <h2 style="color: var(--neon); margin: 5px 0; font-size: 20px;">৳ <?php echo number_format($bonus, 2); ?></h2>
        </div>
    </div>

    <!-- টার্নওভার প্রগ্রেস বার -->
    <div style="background: #0d1210; padding: 18px; border-radius: 12px; margin-top: 20px; border: 1px solid #1a2a22;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 12px; font-weight: 900;">
            <span style="color: #fff;">TURNOVER PROGRESS</span>
            <span style="color: var(--neon);"><?php echo number_format($turnover_done, 0); ?> / <?php echo number_format($turnover_target, 0); ?></span>
        </div>
        <div style="width: 100%; height: 10px; background: #222; border-radius: 10px; overflow: hidden; border: 1px solid #333;">
            <div style="width: <?php echo $turnover_percent; ?>%; height: 100%; background: var(--neon); box-shadow: 0 0 10px var(--neon);"></div>
        </div>
        <p style="font-size: 10px; color: #777; margin-top: 8px; text-align: center;">বোনাস আনলক করতে টার্নওভার সম্পন্ন করুন।</p>
    </div>

    <!-- মেনু লিস্ট -->
    <div style="margin-top: 25px; display: flex; flex-direction: column; gap: 12px; padding-bottom: 30px;">
        
        <button class="p-btn" onclick="location.href='live_chat.php'">
            <i class="fa-solid fa-headset"></i> Live Chat <span style="margin-left:auto; color:#555;">&gt;</span>
        </button>

        <button class="p-btn" onclick="location.href='transactions.php'">
            <i class="fa-solid fa-receipt"></i> Transaction History <span style="margin-left:auto; color:#555;">&gt;</span>
        </button>

        <button class="p-btn" onclick="location.href='bet_history.php'">
            <i class="fa-solid fa-dice"></i> Bet History <span style="margin-left:auto; color:#555;">&gt;</span>
        </button>

        <button class="p-btn" onclick="openUpdatePass()">
            <i class="fa-solid fa-shield-halved"></i> Update Password <span style="margin-left:auto; color:#555;">&gt;</span>
        </button>

        <button class="p-btn" style="border-color: #ff4d4d22; background: #1a0a0a;" onclick="handleLogout()">
            <i class="fa-solid fa-power-off" style="color: #ff4d4d;"></i> <span style="color: #ff4d4d;">Logout</span>
        </button>

    </div>
</div>

<style>
    .p-btn {
        width: 100%;
        padding: 16px;
        background: #0a0f0d;
        border: 1px solid #1a2a22;
        border-radius: 10px;
        color: white;
        text-align: left;
        font-weight: 700;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 15px;
        cursor: pointer;
        transition: 0.2s ease;
    }
    .p-btn i { color: var(--neon); font-size: 18px; width: 25px; text-align: center; }
    .p-btn:hover { background: #073128; border-color: var(--neon); transform: translateX(5px); }
    .p-btn:active { transform: scale(0.98); }
</style>
<!-- ১. পাসওয়ার্ড মোডাল এবং স্ক্রিপ্ট সরাসরি এখানে দেওয়া হলো যাতে মিস না হয় -->
<div id="passModal" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); justify-content: center; align-items: center; z-index: 10000;">
    <div style="background: #073128; border: 2px solid #00ff88; padding: 25px; border-radius: 15px; width: 280px; text-align: center; position: relative;">
        <span onclick="this.parentElement.parentElement.style.display='none'" style="position: absolute; top: 10px; right: 15px; color: #fff; cursor: pointer; font-size: 24px;">&times;</span>
        <h3 style="color: #00ff88; margin-bottom: 20px;">UPDATE PASSWORD</h3>
        <input type="password" id="oldPass" placeholder="Old Password" style="width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 5px; border: 1px solid #1a2a22; background: #000; color: #fff;">
        <input type="password" id="newPass" placeholder="New Password" style="width: 100%; padding: 10px; margin-bottom: 20px; border-radius: 5px; border: 1px solid #1a2a22; background: #000; color: #fff;">
        <button onclick="alert('Password Update Coming Soon!')" style="width: 100%; padding: 12px; background: #00ff88; color: #000; border: none; font-weight: 900; border-radius: 5px; cursor: pointer;">SAVE CHANGES</button>
    </div>
</div>

<script>
// ২. সরাসরি ফাংশনটি এখানেই দিয়ে দিলাম যাতে auth.js এর ওপর নির্ভর করতে না হয়
function openUpdatePass() {
    const pModal = document.getElementById('passModal');
    if(pModal) {
        pModal.style.display = 'flex';
    } else {
        alert("মোডাল খুঁজে পাওয়া যাচ্ছে না!");
    }
}
<!-- পেমেন্ট নম্বর লক সেকশন -->
<?php
$u = $_SESSION['user_id'];
$u_data = $conn->query("SELECT p_bkash, p_nagad FROM users WHERE username = '$u'")->fetch_assoc();
?>
<div style="margin: 15px 0; background: #111; padding: 15px; border-radius: 12px; border: 1px solid #222;">
    <h4 style="color: #00ff88; margin-top: 0; font-size: 14px;">🔒 সেভ করা পেমেন্ট নম্বর</h4>
    <div style="display: flex; flex-direction: column; gap: 10px;">
        
        <!-- বিকাশ -->
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <small style="color: #888;">Bkash:</small>
            <?php if(empty($u_data['p_bkash'])): ?>
                <input type="number" id="new_bkash" placeholder="নম্বর দিন" style="background:#000; border:1px solid #333; color:#fff; padding:5px; border-radius:4px; width:120px;">
            <?php else: ?>
                <span style="color: #ffdf1b; font-weight: bold;"><?php echo $u_data['p_bkash']; ?> ✅</span>
            <?php endif; ?>
        </div>

        <!-- নগদ -->
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <small style="color: #888;">Nagad:</small>
            <?php if(empty($u_data['p_nagad'])): ?>
                <input type="number" id="new_nagad" placeholder="নম্বর দিন" style="background:#000; border:1px solid #333; color:#fff; padding:5px; border-radius:4px; width:120px;">
            <?php else: ?>
                <span style="color: #ffdf1b; font-weight: bold;"><?php echo $u_data['p_nagad']; ?> ✅</span>
            <?php endif; ?>
        </div>

        <!-- বাটন শুধুমাত্র তখনই দেখাবে যখন নম্বর সেভ করা থাকবে না -->
        <?php if(empty($u_data['p_bkash']) || empty($u_data['p_nagad'])): ?>
            <button onclick="saveNumbers()" style="background: #00ff88; color: #000; border: none; padding: 10px; border-radius: 8px; font-weight: bold; margin-top: 10px; cursor: pointer; width: 100%;">নম্বর সেভ ও লক করুন</button>
        <?php endif; ?>
    </div>
</div>


    
function handleLogout() {
    if(confirm("আপনি কি নিশ্চিতভাবে লগআউট করতে চান?")) {
        window.location.href = 'logout.php';
    }
}
function saveNumbers() {
    const bkash = document.getElementById('new_bkash')?.value || "";
    const nagad = document.getElementById('new_nagad')?.value || "";

    if(!bkash && !nagad) { alert("অন্তত একটি নম্বর দিন!"); return; }
    if(!confirm("আপনি কি নিশ্চিত? এই নম্বরগুলো আর কখনো পরিবর্তন করা যাবে না!")) return;

    let fd = new FormData();
    fd.append('bkash', bkash);
    fd.append('nagad', nagad);

    fetch('process_save_numbers.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.status === 'success') location.reload();
    })
    .catch(() => alert("সার্ভার এরর!"));
}
    
</script>


<?php include 'footer.php'; ?>
