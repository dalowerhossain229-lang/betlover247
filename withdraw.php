<?php 
ob_start();
session_start();
include 'db.php'; 
// ১. লগইন চেক
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$u = $_SESSION['user_id'];

// ২. ডাটাবেস থেকে তথ্য আনা (নিশ্চিত পদ্ধতি)
$u_query = "SELECT balance, p_bkash, p_nagad, turnover_target, turnover_completed FROM users WHERE username = '$u'";
$u_res = $conn->query($u_query);

// ৩. এরর হ্যান্ডেলিং: ডাটা ঠিকমতো আসছে কি না চেক করা
if ($u_res && $u_res->num_rows > 0) {
    $user = $u_res->fetch_assoc();
} else {
    die("ইউজার তথ্য পাওয়া যায়নি। দয়া করে আবার লগইন করুন।");
}

// ৪. টার্নওভার ক্যালকুলেশন
$target = floatval($user['turnover_target'] ?? 1000);
$done = floatval($user['turnover_completed'] ?? 0);
$is_turnover_done = ($done >= $target);

include 'header.php'; 
?>

<div style="padding: 20px; text-align: center; color: white; font-family: sans-serif; min-height: 80vh;">
    <h2 style="color:#00ff88; text-shadow: 0 0 10px rgba(0,255,136,0.3);">💰 WITHDRAW</h2>
    
    <!-- ব্যালেন্স কার্ড -->
    <div style="background: rgba(7, 49, 40, 0.6); border: 1px solid #00ff88; padding: 20px; border-radius: 15px; margin-bottom: 25px;">
        <small style="color:#888; text-transform: uppercase; font-size: 10px;">Current Balance</small>
        <h2 style="color:#ffdf1b; margin: 10px 0;">৳ <?php echo number_format($user['balance'], 2); ?></h2>
    </div>

    <!-- ৫. টার্নওভার কন্ডিশন -->
    <?php if (!$is_turnover_done): ?>
        <div style="background: rgba(255, 77, 77, 0.1); border: 1px solid #ff4d4d; padding: 20px; border-radius: 15px; color: #ff4d4d;">
            <p style="font-weight: bold;">⚠️ টার্নওভার অসম্পূর্ণ!</p>
            <small>উইথড্র দিতে আগে টার্নওভার টার্গেট সম্পন্ন করুন।</small>
            
            <div style="margin-top: 20px; background: #111; height: 10px; border-radius: 10px; overflow: hidden; border: 1px solid #333;">
                <div style="width: <?php echo ($target > 0) ? ($done / $target) * 100 : 0; ?>%; background: #ff4d4d; height: 100%; box-shadow: 0 0 10px #ff4d4d;"></div>
            </div>
            <p style="font-size: 12px; margin-top: 10px; color: #888;">
                <?php echo number_format($done); ?> / <?php echo number_format($target); ?>
            </p>
        </div>
    <?php else: ?>
        <!-- উইথড্র ফর্ম (যখন টার্নওভার শেষ হবে) -->
        <div style="background: #111; padding: 25px; border-radius: 15px; border: 1px solid #333; text-align: left;">
            <label style="color: #888; font-size: 12px;">পেমেন্ট নম্বর সিলেক্ট করুন:</label>
            <select id="w_method" style="width: 100%; padding: 15px; background: #000; color: white; border: 1px solid #444; border-radius: 10px; margin-top: 10px; outline: none;">
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

            <label style="color: #888; font-size: 12px; display: block; margin-top: 25px;">উইথড্র পরিমাণ (৳ ১০০ - ৳ ২৫,০০০):</label>
            <input type="number" id="w_amount" placeholder="৳ ০.০০" style="width: 100%; padding: 15px; background: #000; border: 1px solid #444; color: white; border-radius: 10px; margin-top: 10px; outline: none;">
            
            <button onclick="submitWithdraw()" id="wBtn" style="width: 100%; padding: 18px; background: #00ff88; color: #000; border: none; border-radius: 10px; font-weight: bold; margin-top: 30px; cursor: pointer; font-size: 16px;">রিকোয়েস্ট পাঠান</button>
        </div>
    <?php endif; ?>
</div>

<script>
function submitWithdraw() {
    const method = document.getElementById('w_method').value;
    const amount = document.getElementById('w_amount').value;
    const btn = document.getElementById('wBtn');

    if(!method) { alert("আগে প্রোফাইল থেকে নম্বর সেট করুন!"); return; }
    if(!amount || amount < 100 || amount > 25000) { alert("উইথড্র লিমিট ১০০ থেকে ২৫,০০০ টাকা!"); return; }

    btn.disabled = true; btn.innerText = "প্রসেসিং...";

    let fd = new FormData();
    fd.append('method', method);
    fd.append('amount', amount);

    fetch('process_withdraw.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.status === 'success') location.href = 'history.php';
        else { btn.disabled = false; btn.innerText = "রিকোয়েস্ট পাঠান"; }
    });
}
</script>

<?php include 'footer.php'; ?>                    
