<?php
ob_start();
session_start();
include 'db.php';

// ১. লগইন চেক ও ইউজার ডাটা আনা
$u = $_SESSION['user_id'] ?? '';
if (empty($u)) { header("Location: index.php"); exit(); }

$u_res = $conn->query("SELECT * FROM users WHERE username = '$u'");
$user = $u_res->fetch_assoc();

// ২. অ্যাফিলিয়েট চেক
if (($user['is_affiliate'] ?? 0) != 1) {
    echo "<body style='background:#000; color:red; text-align:center; padding-top:50px;'><h2>অ্যাক্সেস ডিনাইড!</h2><p>আপনি অ্যাফিলিয়েট মেম্বার নন।</p></body>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affiliate Dashboard</title>
    <style>
        body { background: #000; color: #fff; font-family: sans-serif; margin: 0; padding: 15px; text-align: center; }
        .card { background: #111; border: 1px solid #333; padding: 20px; border-radius: 15px; margin-bottom: 15px; }
        .stat-box { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 15px; }
        .stat { background: #1a1a1a; padding: 15px; border-radius: 12px; border: 1px solid #222; text-align: center; }
        .ref-code { font-size: 26px; color: #ffdf1b; font-weight: bold; letter-spacing: 2px; margin: 15px 0; background: #222; padding: 10px; border-radius: 8px; }
        .btn-copy { background: #ffdf1b; color: #000; padding: 12px 25px; border-radius: 8px; border:none; font-weight: bold; cursor: pointer; width: 100%; }
        .back-btn { display:inline-block; margin-top: 20px; color: #888; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

    <h2 style="color: #ffdf1b; margin-top: 20px;">🤝 অ্যাফিলিয়েট ড্যাশবোর্ড</h2>

    <div class="card">
        <p style="color: #888; font-size: 14px; margin-bottom: 5px;">আপনার ইউনিক রেফার কোড</p>
        <div class="ref-code" id="refCode"><?php echo $user['ref_code']; ?></div>
        <button class="btn-copy" onclick="copyCode()">কপি কোড</button>
    </div>

    <div class="stat-box">
        <div class="stat">
            <small style="color: #00ff88;">ডিপোজিট কমিশন (৫%)</small>
            <h2 style="margin: 10px 0; font-size: 18px;">৳<?php echo number_format($user['aff_balance'], 2); ?></h2>
        </div>
        <div class="stat">
            <small style="color: #ff4d4d;">লস/প্রফিট শেয়ার (৪৭%)</small>
            <h2 style="margin: 10px 0; font-size: 18px;">৳<?php echo number_format($user['player_loss_profit'], 2); ?></h2>
        </div>
    </div>

    <div class="card" style="margin-top: 20px; text-align: left;">
        <h4 style="margin: 0 0 10px 0; color: #ffdf1b; font-size: 16px;">💡 এটি কিভাবে কাজ করে?</h4>
        <div style="font-size: 13px; color: #bbb; line-height: 1.6;">
            • আপনার কোড দিয়ে রেজিস্ট্রেশন করলে সে আপনার মেম্বার হবে।<br>
            • আপনার মেম্বার প্রতিবার ডিপোজিট করলে আপনি সাথে সাথে ৫% পাবেন।<br>
            • মাসের শেষে মেম্বারদের মোট লস বা প্রফিটের ৪৭% আপনার একাউন্টে যোগ হবে।
        </div>
    </div>
<!-- শুধুমাত্র ৫% ডিপোজিট কমিশন ট্রান্সফার বাটন -->
<div class="card" style="margin-top: 20px; background: #1a1a1a; border: 1px solid #ffdf1b33;">
    <p style="color: #888; font-size: 13px;">আপনার ৫% ডিপোজিট কমিশন ব্যালেন্স</p>
    <h2 style="color: #ffdf1b; margin: 5px 0;">৳<?php echo number_format($user['aff_balance'], 2); ?></h2>
    
    <form action="transfer_5percent.php" method="POST" style="margin-top: 15px;">
        <button type="submit" onclick="return confirm('আপনি কি নিশ্চিত?')" style="width: 85%; background: #ffdf1b; color: #000; padding: 12px; border-radius: 8px; border: none; font-weight: bold; cursor: pointer;">
            মেইন ব্যালেন্সে নিন
        </button>
    </form>
    <p style="font-size: 11px; color: #666; margin-top: 10px;">*ক্লিক করলে সব কমিশন আপনার গেমিং ব্যালেন্সে যোগ হবে।</p>
</div>

    <a href="profile.php" class="back-btn">← প্রোফাইলে ফিরে যান</a>

    <script>
        function copyCode() {
            var code = document.getElementById("refCode").innerText;
            navigator.clipboard.writeText(code).then(() => {
                alert("রেফার কোড কপি করা হয়েছে: " + code);
            });
        }
    </script>

</body>
</html>
<?php ob_end_flush(); ?>
