<?php
session_start();
include 'db.php';

// ১. সেশন চেক করা
if (!isset($_SESSION['username'])) {
    die("অনুগ্রহ করে আগে লগইন করুন।");
}

$u = $_SESSION['username'];

// ২. Prepared Statement ব্যবহার করে তথ্য আনা (নিরাপদ পদ্ধতি)
$stmt = $conn->prepare("SELECT balance, main_t, t_main, bkash, nagad FROM users WHERE username = ?");
$stmt->bind_param("s", $u);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("ব্যবহারকারী পাওয়া যায়নি।");
}

// ৩. টার্নওভার ক্যালকুলেশন
$done   = (float)($user['main_t'] ?? 0);
$target = (float)($user['t_main'] ?? 1000);
$is_done = ($done >= $target);

// ৪. প্রগ্রেস বার পারসেন্টেজ
$percent = ($target > 0) ? ($done / $target) * 100 : 0;
$display_percent = ($percent > 100) ? 100 : $percent;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw</title>
</head>
<body style="background:#000; color:#fff; text-align:center; font-family:sans-serif; padding:15px; margin:0;">
    <h2>💰 WITHDRAW</h2>
    
    <!-- ব্যালেন্স সেকশন -->
    <div style="border:1px solid #07ff88; padding:15px; border-radius:12px; margin-bottom:20px; background:rgba(0,255,136,0.05);">
        <p style="color:#888; font-size:12px; margin-bottom:5px;">Current Balance</p>
        <h2 style="color:#ffdf1b; margin:0;">৳ <?php echo number_format($user['balance'] ?? 0, 2); ?></h2>
    </div>

    <!-- টার্নওভার চেক -->
    <?php if (!$is_done): ?>
        <div style="border:1px solid #ff4d4d; padding:20px; border-radius:12px; background:rgba(255,0,0,0.1);">
            <p style="color:#ff4d4d; font-weight:bold; margin-top:0;">⚠️ টার্নওভার অসম্পূর্ণ!</p>
            <p style="font-size:13px; color:#ccc;">প্রগ্রেস: <?php echo number_format($done, 2) . " / " . number_format($target, 2); ?></p>
            <div style="width:100%; background:#222; height:8px; border-radius:5px; margin-top:10px; border:1px solid #333; overflow:hidden;">
                <div style="width:<?php echo $display_percent; ?>%; background:#ff4d4d; height:100%; border-radius:5px; transition: 0.3s;"></div>
            </div>
        </div>
    <?php else: ?>
        <!-- উইথড্র ফর্ম -->
        <div style="border:1px solid #333; padding:20px; border-radius:12px; text-align:left; background:rgba(255,255,255,0.03);">
            <p style="color:#00ff88; font-weight:bold; margin-top:0; text-align:center;">✅ আপনি উইথড্র দিতে পারবেন!</p>
            <hr style="border:0.5px solid #222; margin:15px 0;">
            
            <label style="font-size:12px; color:#888;">পেমেন্ট মেথড:</label>
            <select id="w_method" style="width:100%; padding:12px; background:#111; color:#fff; border:1px solid #333; border-radius:8px; margin:8px 0;">
                <option value="bkash">Bkash (<?php echo htmlspecialchars($user['bkash'] ?? 'Not Set'); ?>)</option>
                <option value="nagad">Nagad (<?php echo htmlspecialchars($user['nagad'] ?? 'Not Set'); ?>)</option>
            </select>

            <label style="font-size:12px; color:#888;">অ্যামাউন্ট:</label>
            <input type="number" id="w_amount" min="100" placeholder="Min: 100" style="width:100%; padding:12px; background:#111; color:#fff; border:1px solid #333; border-radius:8px; margin:8px 0; box-sizing:border-box;">

            <button onclick="sendWithdraw()" style="width:100%; padding:14px; background:#00ff88; color:#000; border:none; border-radius:8px; font-weight:bold; margin-top:10px; cursor:pointer;">
                SUBMIT WITHDRAW
            </button>
        </div>
    <?php endif; ?>

    <?php if(file_exists('footer.php')) include 'footer.php'; ?>

    <script>
    function sendWithdraw() {
        const amount = document.getElementById('w_amount').value;
        if(amount < 100) {
            alert('সর্বনিম্ন ১০০ টাকা উইথড্র দিতে হবে।');
            return;
        }
        alert('আপনার রিকোয়েস্ট প্রসেস করা হচ্ছে...');
        // এখানে আপনার AJAX বা ফর্ম সাবমিট লজিক দিতে পারেন
    }
    </script>
</body>
</html>
