<?php
session_start();
include 'db.php';

// ১. নিরাপদ উপায়ে সেশন থেকে ইউজারনেম নেওয়া
$u = $_SESSION['username'] ?? '';

// ২. Prepared Statement ব্যবহার করে ডাটাবেস থেকে তথ্য আনা (SQL Injection সুরক্ষা)
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $u);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

// ৩. টার্নওভার লজিক
$done = isset($user_data['main_t']) ? (float)$user_data['main_t'] : 0;
$target = isset($user_data['t_main']) ? (float)$user_data['t_main'] : 1000;
$is_turnover_done = ($done >= $target);

// ৪. ব্যালেন্স ফরম্যাট করা
$balance = isset($user_data['balance']) ? number_format($user_data['balance'], 2) : "0.00";
?>

<div style="padding: 20px; text-align: center; background: #000; min-height: 100vh; color: #fff; font-family: sans-serif;">
    <h2 style="color:#00ff88;">💰 WITHDRAW</h2>

    <!-- ব্যালেন্স কার্ড -->
    <div style="background: rgba(7, 49, 40, 0.4); border: 1px solid #07ff88; padding: 20px; border-radius: 15px; margin-bottom: 20px;">
        <small style="color: #888; font-size: 10px;">Current Balance</small>
        <h2 style="color:#ffdf1b; margin: 10px 0; font-size: 32px;">৳ <?php echo $balance; ?></h2>
    </div>

    <!-- ৫. টার্নওভার চেক সেকশন -->
    <?php if (!$is_turnover_done): ?>
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
        <!-- ৬. উইথড্র ফর্ম -->
        <div style="background: rgba(255, 255, 255, 0.05); border: 1px solid #333; padding: 20px; border-radius: 15px; text-align: left;">
            <p style="color: #00ff88; font-weight: bold; text-align:center;">✅ আপনি উইথড্র দিতে পারবেন!</p>
            <hr style="border: 0.5px solid #222; margin: 15px 0;">
            
            <label style="color: #888; font-size: 12px;">পেমেন্ট মেথড:</label>
            <select id="w_method" style="width: 100%; padding: 12px; background: #111; color: #fff; border: 1px solid #333; border-radius: 8px; margin: 10px 0;">
                <option value="bkash">Bkash (<?php echo htmlspecialchars($user_data['bkash'] ?? 'Not Set'); ?>)</option>
                <option value="nagad">Nagad (<?php echo htmlspecialchars($user_data['nagad'] ?? 'Not Set'); ?>)</option>
            </select>

            <label style="color: #888; font-size: 12px;">উইথড্র অ্যামাউন্ট:</label>
            <input type="number" id="w_amount" placeholder="Min: 100" style="width: 100%; padding: 12px; background: #111; color: #fff; border: 1px solid #333; border-radius: 8px; margin: 10px 0; box-sizing: border-box;">

            <button onclick="alert('Request Sent!')" style="width: 100%; padding: 15px; background: #00ff88; color: #000; border: none; border-radius: 8px; font-weight: bold; margin-top: 10px; cursor: pointer;">
                SUBMIT WITHDRAW
            </button>
        </div>
    <?php endif; ?>
</div>
