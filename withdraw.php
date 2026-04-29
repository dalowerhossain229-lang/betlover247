<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

// ১. সেশন থেকে ইউজার চেক
$u = $_SESSION['username'] ?? $_SESSION['user_id'] ?? '';

if (empty($u)) {
    header("Location: index.php");
    exit();
}

// ২. ডাটাবেস থেকে ইউজারের সব তথ্য আনা (সঠিক কুয়েরি)
$query = $conn->query("SELECT * FROM users WHERE username = '$u' OR id = '$u'");
$user_data = $query->fetch_assoc();

// ৪. টার্নওভার লজিক (আপনার প্রোফাইল পেজের সাথে মিল রেখে)
$done = (float)($user_data['main_t'] ?? 0); 
$target = (float)($user_data['t_main'] ?? 700); 

// ৫. কন্ডিশন: এটি পূরণ হলেই উইথড্র ফরম আসবে
$is_turnover_done = ($done >= $target);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw - BetLover</title>
</head>
<body style="background: #000; color: #fff; font-family: sans-serif; margin: 0; padding-bottom: 80px;">

<div style="padding: 20px; text-align: center;">
    <h2 style="color:#00ff88; text-shadow: 0 0 10px rgba(0,255,136,0.3);">💰 WITHDRAW</h2>

    <!-- ব্যালেন্স কার্ড -->
    <div style="background: rgba(7, 49, 40, 0.4); border: 1px solid #07ff88; padding: 20px; border-radius: 15px; margin-bottom: 20px;">
        <small style="color: #888; text-transform: uppercase; font-size: 10px; letter-spacing: 1px;">Current Balance</small>
        <h2 style="color:#ffdf1b; margin: 10px 0; font-size: 32px;">৳ <?php echo number_format($user_data['balance'] ?? 0, 2); ?></h2>
    </div>

    <!-- ৫. টার্নওভার চেক সেকশন -->
    <?php if (!$is_turnover_done): ?>
        <div style="background: rgba(255, 77, 77, 0.1); border: 1px solid #ff4d4d; padding: 25px; border-radius: 15px;">
            <p style="font-weight: bold; margin-bottom: 10px; color: #ff4d4d;">⚠️ টার্নওভার অসম্পূর্ণ!</p>
            <small style="color: #ccc;">উইথড্র দিতে হলে আগে মেইন টার্নওভার টার্গেট সম্পন্ন করা প্রয়োজন।</small>

            <div style="margin-top: 20px; background: #111; height: 10px; border-radius: 10px; overflow: hidden; border: 1px solid #333;">
<?php 
    // ১. প্রগ্রেস পার্সেন্টেজ হিসাব (এটি উপরে থাকা জরুরি)
    $percent = ($target > 0) ? ($done / $target) * 100 : 0; 
?>

<div style="margin-top: 20px; background: #111; height: 10px; border-radius: 10px; overflow: hidden; border: 1px solid #333;">
    <!-- ৫৫ নম্বর লাইন: প্রগ্রেস বার -->
    <div style="width: <?php echo ($percent > 100) ? 100 : $percent; ?>%; background: #ff4d4d; height: 100%;"></div>
</div>

<p style="font-size: 13px; margin-top: 10px; color: #aaa;">
    <!-- ৫৮ নম্বর লাইন: প্রগ্রেস টেক্সট -->
    প্রগ্রেস: <?php echo number_format($done, 0); ?> / <?php echo number_format($target, 0); ?>
</p>

        </div>
    <?php else: ?>
        <!-- ৬. উইথড্র ফর্ম (টার্নওভার শেষ হলে এটি দেখাবে) -->
        <div style="background: rgba(255, 255, 255, 0.05); border: 1px solid #333; padding: 20px; border-radius: 15px; text-align: left;">
            <p style="color: #00ff88; font-weight: bold; text-align:center; margin-top:0;">✅ আপনি এখন উইথড্র দিতে পারবেন!</p>
            <hr style="border: 0.5px solid #222; margin: 15px 0;">
            
            <label style="color: #888; font-size: 12px;">পেমেন্ট মেথড সিলেক্ট করুন:</label>
            <select id="w_method" style="width: 100%; padding: 12px; background: #111; color: #fff; border: 1px solid #333; border-radius: 8px; margin: 10px 0;">
                <?php if(!empty($user_data['bkash'])): ?>
                    <option value="bkash">Bkash (<?php echo $user_data['bkash']; ?>)</option>
                <?php endif; ?>
                <?php if(!empty($user_data['nagad'])): ?>
                    <option value="nagad">Nagad (<?php echo $user_data['nagad']; ?>)</option>
                <?php endif; ?>
                <?php if(empty($user_data['bkash']) && empty($user_data['nagad'])): ?>
                    <option value="">আগে প্রোফাইল থেকে পেমেন্ট নম্বর সেট করুন</option>
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
    const btn = document.getElementById('w_btn');

    if(!method) { alert("আগে প্রোফাইল থেকে নম্বর সেট করুন!"); return; }
    if(amount < 100) { alert("সর্বনিম্ন উইথড্র ১০০ টাকা!"); return; }
    
    btn.disabled = true;
    btn.innerText = "প্রসেসিং...";
    
    // টেস্ট অ্যালার্ট
    alert("আপনার উইথড্র রিকোয়েস্ট পাঠানো হয়েছে!");
    location.reload();
}
</script>

</body>
</html>
