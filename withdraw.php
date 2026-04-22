<?php 
session_start();
include 'header.php'; 
include 'db.php'; 
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); 
    exit();
}
$u = $_SESSION['user_id'];
$u_res = $conn->query("SELECT balance, p_bkash, p_nagad, turnover_target, turnover_completed FROM users WHERE username = '$u'");
$user = $u_res->fetch_assoc();

// টার্নওভার চেক
$is_turnover_done = ($user['turnover_completed'] >= $user['turnover_target']);
?>

<div style="padding: 20px; text-align: center; color: white;">
    <h2 style="color:#00ff88;">WITHDRAW</h2>
    <div style="background:#073128; border:1px solid #00ff88; padding:15px; border-radius:12px; margin-bottom:20px;">
        <small style="color:#aaa;">ব্যালেন্স:</small>
        <h2 style="color:#ffdf1b; margin:5px 0;">৳ <?php echo number_format($user['balance'], 2); ?></h2>
    </div>

    <?php if (!$is_turnover_done): ?>
        <div style="background:rgba(255,77,77,0.1); border:1px solid #ff4d4d; padding:15px; border-radius:10px; color:#ff4d4d; font-size:13px;">
            ⚠️ <b>সতর্কতা:</b> আপনার টার্নওভার এখনো অসম্পূর্ণ। উইথড্র দিতে আগে টার্নওভার সম্পন্ন করুন।
        </div>
    <?php else: ?>
        <div style="background:#111; padding:20px; border-radius:15px; border:1px solid #333; text-align: left;">
            <label style="color:#888; font-size:11px;">পেমেন্ট নম্বর সিলেক্ট করুন:</label>
            <select id="w_method" style="width:100%; padding:15px; background:#000; color:white; border:1px solid #444; border-radius:10px; margin-top:8px;">
                <?php if(!empty($user['p_bkash'])): ?>
                    <option value="Bkash: <?php echo $user['p_bkash']; ?>">বিকাশ (<?php echo $user['p_bkash']; ?>)</option>
                <?php endif; ?>
                <?php if(!empty($user['p_nagad'])): ?>
                    <option value="Nagad: <?php echo $user['p_nagad']; ?>">নগদ (<?php echo $user['p_nagad']; ?>)</option>
                <?php endif; ?>
                <?php if(empty($user['p_bkash']) && empty($user['p_nagad'])): ?>
                    <option value="">আগে প্রোফাইল থেকে নম্বর লক করুন!</option>
                <?php endif; ?>
            </select>

            <label style="color:#888; font-size:11px; display:block; margin-top:20px;">পরিমাণ (৳ ১০০ - ৳ ২৫,০০০):</label>
            <input type="number" id="w_amount" placeholder="৳ ০.০০" style="width:100%; padding:15px; background:#000; border:1px solid #444; color:white; border-radius:10px; margin-top:8px;">
            
            <button onclick="submitWithdraw()" id="wBtn" style="width:100%; padding:16px; background:#00ff88; color:#000; border:none; border-radius:10px; font-weight:bold; margin-top:25px; cursor:pointer;">উইথড্র রিকোয়েস্ট পাঠান</button>
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
    });
}
</script>
