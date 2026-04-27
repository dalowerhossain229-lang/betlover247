<?php
session_start();
include 'db.php';

// ইউজারের সেশন থেকে নাম নেওয়া
$u = $_SESSION['username'];

// ১. ডাটাবেস থেকে ইউজারের তথ্য আনা
$query = $conn->query("SELECT * FROM users WHERE username = '$u'");
$user_data = $query->fetch_assoc();

// ২. টার্নওভার লজিক (আপনার ডাটাবেস কলাম অনুযায়ী)
$done = isset($user_data['main_t']) ? (float)$user_data['main_t'] : 0;
$target = isset($user_data['t_main']) ? (float)$user_data['t_main'] : 1000;

// ৩. চেক: টার্নওভার সম্পন্ন হয়েছে কি না
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

            <div style="margin-top: 20px; background: #111; height: 10px; border-radius: 10px; overflow: hidden; border: 1px solid #333;">
                <?php $percent = ($target > 0) ? ($done / $target) * 100 : 0; ?>
                <div style="width: <?php echo ($percent > 100) ? 100 : $percent; ?>%; background: #ff4d4d; height: 100%;"></div>
            </div>
            <p style="font-size: 13px; margin-top: 12px; color: #aaa;">
                প্রগ্রেস: <?php echo number_format($done); ?> / <?php echo number_format($target); ?>
            </p>
        </div>
    <?php else: ?>
        <!-- ৫. উইথড্র ফর্ম (টার্নওভার শেষ হলে এটি দেখাবে) -->
        <div style="background: rgba(0, 255, 136, 0.05); border: 1px solid #333; padding: 20px; border-radius: 15px; text-align: left;">
            <label style="color: #888; font-size: 12px;">পেমেন্ট মেথড সিলেক্ট করুন:</label>
            <select id="w_method" style="width: 100%; padding: 12px; background: #111; color: #fff; border: 1px solid #333; border-radius: 8px; margin: 10px 0;">
                <?php if(!empty($user_data['bkash'])): ?>
                    <option value="bkash">Bkash (<?php echo $user_data['bkash']; ?>)</option>
                <?php endif; ?>
                <?php if(!empty($user_data['nagad'])): ?>
                    <option value="nagad">Nagad (<?php echo $user_data['nagad']; ?>)</option>
                <?php endif; ?>
                <?php if(empty($user_data['bkash']) && empty($user_data['nagad'])): ?>
                    <option value="">আগে প্রোফাইল থেকে নম্বর সেট করুন</option>
                <?php endif; ?>
            </select>

            <label style="color: #888; font-size: 12px;">উইথড্র অ্যামাউন্ট লিখুন:</label>
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
