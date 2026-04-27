<?php
include 'db.php'; 
session_start();
$u = $_SESSION['username']; // আপনার সেশন ভ্যারিয়েবল অনুযায়ী ঠিক করে নিন

// ১. ডাটাবেস থেকে ইউজারের সব তথ্য আনা
$query = $conn->query("SELECT * FROM users WHERE username = '$u'");
$user_data = $query->fetch_assoc();

// ২. মেইন টার্নওভারের মানগুলো সরাসরি ডাটাবেস থেকে নেওয়া
$done = floatval($user_data['main_t'] ?? 0); 
$target = floatval($user_data['t_main'] ?? 1000);

// ৩. টার্নওভার সম্পন্ন হয়েছে কি না চেক (২১১৮৯ >= ১০০০ হলে এটি True হবে)
$is_turnover_done = ($done >= $target);
?>

<div style="padding: 20px; text-align: center; background: #000; min-height: 100vh; color: #fff;">
    <h2 style="color:#00ff88;">💰 WITHDRAW</h2>

    <!-- ব্যালেন্স কার্ড -->
    <div style="background: rgba(7, 49, 40, 0.4); border: 1px solid #07ff88; padding: 20px; border-radius: 15px; margin-bottom: 20px;">
        <small style="color: #888; font-size: 10px;">Current Balance</small>
        <h2 style="color:#ffdf1b; margin: 10px 0; font-size: 32px;">৳ <?php echo number_format($user_data['balance'] ?? 0, 2); ?></h2>
    </div>

    <!-- ৪. টার্নওভার চেক সেকশন -->
    <?php if (!$is_turnover_done): ?>
        <div style="background: rgba(255, 77, 77, 0.1); border: 1px solid #ff4d4d; padding: 25px; border-radius: 15px;">
            <p style="font-weight: bold; margin-bottom: 10px;">⚠️ টার্নওভার অসম্পূর্ণ!</p>
            <small>উইথড্র দিতে হলে আগে টার্নওভার টার্গেট সম্পন্ন করা প্রয়োজন।</small>

            <div style="margin-top: 20px; background: #111; height: 10px; border-radius: 10px; overflow: hidden;">
                <div style="width: <?php echo ($target > 0) ? ($done / $target) * 100 : 0; ?>%; background: #ff4d4d; height: 100%;"></div>
            </div>
            <p style="font-size: 13px; margin-top: 12px; color: #aaa;">
                প্রগ্রেস: <?php echo number_format($done); ?> / <?php echo number_format($target); ?>
            </p>
        </div>
    <?php else: ?>
        <!-- টার্নওভার শেষ হলে এই মেসেজটি দেখাবে এবং ফর্মটি নিচে আসবে -->
        <p style="color: #00ff88; font-weight: bold;">✅ টার্নওভার সম্পন্ন হয়েছে! আপনি এখন উইথড্র দিতে পারবেন।</p>
    <?php endif; ?>

        <!-- ৫. উইথড্র ফর্ম (টার্নওভার শেষ হলে দেখাবে) -->
        <div style="background: #111; padding: 25px; border-radius: 15px; border: 1px solid #333; text-align: left; animation: slideUp 0.5s;">
            <label style="color: #888; font-size: 12px;">পেমেন্ট নম্বর সিলেক্ট করুন:</label>
            <select id="w_method" style="width: 100%; padding: 15px; background: #000; color: white; border: 1px solid #444; border-radius: 10px; margin-top: 10px; outline: none; -webkit-appearance: none;">
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
            <input type="number" id="w_amount" placeholder="৳ পরিমাণ লিখুন" style="width: 100%; padding: 15px; background: #000; border: 1px solid #444; color: white; border-radius: 10px; margin-top: 10px; outline: none; box-sizing: border-box;">
            
            <button onclick="submitWithdraw()" id="wBtn" style="width: 100%; padding: 18px; background: #00ff88; color: #000; border: none; border-radius: 10px; font-weight: bold; margin-top: 30px; cursor: pointer; font-size: 16px; transition: 0.3s;">রিকোয়েস্ট পাঠান</button>
        </div>
</div>
<script>
function submitWithdraw() {
    const method = document.getElementById('w_method').value;
    const amount = document.getElementById('w_amount').value;
    const btn = document.getElementById('wBtn');

    if(!method) { alert("আগে প্রোফাইল থেকে পেমেন্ট নম্বর সেট করুন!"); return; }
    if(amount < 100 || amount > 25000) { alert("উইথড্র লিমিট ১০০ থেকে ২৫,০০০ টাকা!"); return; }

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
    })
    .catch(() => { alert("সার্ভার এরর! দয়া করে আবার চেষ্টা করুন।"); btn.disabled = false; });
}
</script>

<style>
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>

<?php include 'footer.php'; ?>                
