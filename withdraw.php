<?php 
ob_start();
session_start();
include 'db.php'; 

// ১. লগইন চেক (সবার আগে)
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$u = $_SESSION['user_id'];

// ২. ডাটাবেস থেকে ইউজারের ব্যালেন্স ও টার্নওভার তথ্য আনা
$u_res = $conn->query("SELECT * FROM users WHERE username = '$u'");
$user = $u_res->fetch_assoc();

// যদি ডাটাবেস থেকে ডাটা না আসে তবে এরর হ্যান্ডেল করা
if (!$user) {
    die("ইউজার তথ্য পাওয়া যায়নি। দয়া করে আবার লগইন করুন।");
}

// ৩. টার্নওভার চেক লজিক
$target = $user['turnover_target'] ?? 1000;
$done = $user['turnover_completed'] ?? 0;
$is_turnover_done = ($done >= $target);

include 'header.php'; 
?>

<div style="padding: 20px; text-align: center; color: white; font-family: sans-serif; min-height: 100vh;">
    <h2 style="color:#00ff88; text-shadow: 0 0 10px rgba(0,255,136,0.3);">💰 WITHDRAW</h2>
    
    <!-- ব্যালেন্স কার্ড -->
    <div style="background:#073128; border:1px solid #00ff88; padding:15px; border-radius:12px; margin-bottom:20px; box-shadow: 0 4px 15px rgba(0,255,136,0.1);">
        <small style="color:#888;">Current Balance</small>
        <h2 style="color:#ffdf1b; margin:5px 0;">৳ <?php echo number_format($user['balance'], 2); ?></h2>
    </div>

    <!-- ৪. টার্নওভার শর্ত চেক -->
    <?php if (!$is_turnover_done): ?>
        <div style="background:rgba(255,77,77,0.1); border:1px solid #ff4d4d; padding:20px; border-radius:15px; color:#ff4d4d; margin-top:20px;">
            <p style="font-weight:bold; margin-bottom:10px;">⚠️ টার্নওভার অসম্পূর্ণ!</p>
            <small>উইথড্র দিতে আগে টার্নওভার টার্গেট সম্পন্ন করুন।</small>
            <div style="margin-top:15px; background:#111; height:8px; border-radius:10px; overflow:hidden; border:1px solid #333;">
                <div style="width:<?php echo ($target > 0) ? ($done / $target) * 100 : 0; ?>%; background:#ff4d4d; height:100%;"></div>
            </div>
            <p style="font-size:11px; margin-top:8px;"><?php echo number_format($done); ?> / <?php echo number_format($target); ?></p>
        </div>
    <?php else: ?>
        <!-- উইথড্র ফর্ম (টার্নওভার শেষ হলে দেখাবে) -->
        <div style="background:#111; padding:20px; border-radius:15px; border:1px solid #333; text-align: left;">
            <label style="color:#888; font-size:12px;">নম্বর সিলেক্ট করুন (প্রোফাইলে লক করা):</label>
            <select id="w_method" style="width:100%; padding:15px; background:#000; color:white; border:1px solid #444; border-radius:10px; margin-top:8px; outline:none;">
                <?php if(!empty($user['p_bkash'])): ?>
                    <option value="Bkash: <?php echo $user['p_bkash']; ?>">বিকাশ (<?php echo $user['p_bkash']; ?>)</option>
                <?php endif; ?>
                <?php if(!empty($user['p_nagad'])): ?>
                    <option value="Nagad: <?php echo $user['p_nagad']; ?>">নগদ (<?php echo $user['p_nagad']; ?>)</option>
                <?php endif; ?>
                <?php if(empty($user['p_bkash']) && empty($user['p_nagad'])): ?>
                    <option value="">আগে প্রোফাইল থেকে নম্বর সেট করুন!</option>
                <?php endif; ?>
            </select>

            <label style="color:#888; font-size:12px; display:block; margin-top:20px;">উইথড্র পরিমাণ (৳ ১০০ - ৳ ২৫,০০০):</label>
            <input type="number" id="w_amount" placeholder="৳ পরিমাণ লিখুন" style="width:100%; padding:15px; background:#000; border:1px solid #444; color:white; border-radius:10px; margin-top:8px; outline:none; box-sizing: border-box;">
            
            <button onclick="submitWithdraw()" id="wBtn" style="width:100%; padding:16px; background:#00ff88; color:#000; border:none; border-radius:10px; font-weight:bold; margin-top:25px; cursor:pointer; font-size:16px;">উইথড্র রিকোয়েস্ট পাঠান</button>
        </div>
    <?php endif; ?>
</div>

<script>
function submitWithdraw() {
    const method = document.getElementById('w_method').value;
    const amount = document.getElementById('w_amount').value;
    const btn = document.getElementById('wBtn');

    if(!method) { alert("আগে প্রোফাইল থেকে নম্বর সেট করুন!"); return; }
    if(amount < 100 || amount > 25000) { alert("উইথড্র লিমিট ১০০ থেকে ২৫,০০০ টাকা!"); return; }

    btn.disabled = true; btn.innerText = "প্রসেসিং...";

    let fd = new FormData();
    fd.append('method', method);
    fd.append('amount', amount);

    fetch('process_withdraw.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.status === 'success') location.href='history.php';
        else { btn.disabled = false; btn.innerText = "উইথড্র রিকোয়েস্ট পাঠান"; }
    })
    .catch(() => { alert("সার্ভার এরর!"); btn.disabled = false; });
}
</script>

<?php include 'footer.php'; ?>
                
