<?php
session_start();
include 'db.php';

// সেশন থেকে ইউজার চেক
$u = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// ডাটাবেস থেকে তথ্য আনা
$query = $conn->query("SELECT * FROM users WHERE username = '$u'");
$user_data = $query->fetch_assoc();

// টার্নওভার লজিক
$done = isset($user_data['main_t']) ? (float)$user_data['main_t'] : 0;
$target = isset($user_data['t_main']) ? (float)$user_data['t_main'] : 1000;
$is_turnover_done = ($done >= $target);
?>

<div style="padding: 20px; text-align: center; background: #000; color: #fff; min-height: 100vh; font-family: sans-serif;">
    <h2 style="color:#00ff88;">💰 WITHDRAW</h2>

    <!-- ব্যালেন্স কার্ড -->
    <div style="background: rgba(7, 49, 40, 0.4); border: 1px solid #07ff88; padding: 20px; border-radius: 15px; margin-bottom: 20px;">
        <small style="color: #888; font-size: 10px;">Current Balance</small>
        <h2 style="color:#ffdf1b; margin: 10px 0; font-size: 32px;">৳ <?php echo number_format($user_data['balance'] ?? 0, 2); ?></h2>
    </div>

    <?php if (!$is_turnover_done): ?>
        <!-- ৪. টার্নওভার অসম্পূর্ণ সেকশন -->
        <div style="background: rgba(255, 77, 77, 0.1); border: 1px solid #ff4d4d; padding: 25px; border-radius: 15px;">
            <p style="font-weight: bold; margin-bottom: 10px;">⚠️ টার্নওভার অসম্পূর্ণ!</p>
            <div style="margin-top: 20px; background: #111; height: 10px; border-radius: 10px; overflow: hidden; border: 1px solid #333;">
                <?php $percent = ($target > 0) ? ($done / $target) * 100 : 0; ?>
                <div style="width: <?php echo ($percent > 100) ? 100 : $percent; ?>%; background: #ff4d4d; height: 100%;"></div>
            </div>
            <p style="font-size: 13px; margin-top: 12px; color: #aaa;">
                প্রগ্রেস: <?php echo number_format($done); ?> / <?php echo number_format($target); ?>
            </p>
        </div>
    <?php else: ?>
        <!-- ৫. উইথড্র ফর্ম সেকশন -->
        <div style="background: rgba(255, 255, 255, 0.05); border: 1px solid #333; padding: 20px; border-radius: 15px; text-align: left;">
            <label style="color: #888; font-size: 12px;">পেমেন্ট মেথড:</label>
            <select id="w_method" style="width: 100%; padding: 12px; background: #111; color: #fff; border: 1px solid #333; border-radius: 8px; margin: 10px 0;">
                <?php if(!empty($user_data['bkash'])): ?>
                    <option value="bkash">Bkash (<?php echo $user_data['bkash']; ?>)</option>
                <?php endif; ?>
                <?php if(!empty($user_data['nagad'])): ?>
                    <option value="nagad">Nagad (<?php echo $user_data['nagad']; ?>)</option>
                <?php endif; ?>
                <?php if(empty($user_data['bkash']) && empty($user_data['nagad'])): ?>
                    <option value="">প্রোফাইল থেকে নম্বর সেট করুন</option>
                <?php endif; ?>
            </select>

            <label style="color: #888; font-size: 12px;">উইথড্র অ্যামাউন্ট:</label>
            <input type="number" id="w_amount" placeholder="Min: 100" style="width: 100%; padding: 12px; background: #111; color: #fff; border: 1px solid #333; border-radius: 8px; margin: 10px 0; box-sizing: border-box;">

            <button onclick="submitWithdraw()" id="w_btn" style="width: 100%; padding: 15px; background: #00ff88; color: #000; border: none; border-radius: 8px; font-weight: bold; margin-top: 10px; cursor: pointer;">
                SUBMIT WITHDRAW
            </button>
        </div>
    <?php endif; ?>
</div>

<script>
function submitWithdraw() {
    const method = document.getElementById('w_method').value;
    const amount = document.getElementById('w_amount').value;
    const btn = document.getElementById('w_btn');

    if(!method) { alert("পেমেন্ট মেথড ঠিক নেই!"); return; }
    if(amount < 100) { alert("সর্বনিম্ন উইথড্র ১০০ টাকা!"); return; }
    
    btn.disabled = true;
    btn.innerText = "Processing...";
    
    // আপনার প্রসেসিং লজিক এখানে আসবে
    alert("রিকোয়েস্ট পাঠানো হয়েছে!");
}
</script>

<?php include 'footer.php'; ?>
