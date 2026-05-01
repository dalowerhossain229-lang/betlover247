<?php
include 'db.php';
session_start();

// ১. সেশন থেকে ইউজার চেক
$u = $_SESSION['username'] ?? '';
if (empty($u)) { header("Location: index.php"); exit(); }

// ২. ডাটাবেস থেকে ইউজারের অ্যাফিলিয়েট তথ্য আনা
$query = $conn->query("SELECT * FROM users WHERE username = '$u'");
$user = $query->fetch_assoc();

// ৩. যদি সে অ্যাফিলিয়েট মেম্বার না হয়, তবে এক্সেস বন্ধ
if (($user['is_affiliate'] ?? 0) != 1) {
    echo "<body style='background:#000; color:red; text-align:center; padding-top:50px;'><h2>Access Denied!</h2><p>আপনি অ্যাফিলিয়েট মেম্বার নন।</p></body>";
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
        .stat-box { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 20px; }
        .stat { background: #1a1a1a; padding: 15px; border-radius: 10px; border: 1px solid #222; }
        .ref-code { font-size: 24px; color: #ffdf1b; font-weight: bold; letter-spacing: 2px; margin: 15px 0; }
        .btn-copy { background: #ffdf1b; color: #000; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>

    <h2 style="color: #ffdf1b;">🤝 AFFILIATE PANEL</h2>

    <div class="card">
        <p style="color: #888; font-size: 14px;">আপনার ইউনিক রেফার কোড</p>
        <div class="ref-code" id="refCode"><?php echo $user['ref_code']; ?></div>
        <button class="btn-copy" onclick="copyCode()">COPY CODE</button>
    </div>

    <div class="stat-box">
        <div class="stat">
            <small style="color: #00ff88;">ডিপোজিট কমিশন (৫%)</small>
            <h3 style="margin: 10px 0;">৳<?php echo number_format($user['aff_balance'], 2); ?></h3>
        </div>
        <div class="stat">
            <small style="color: #ff4d4d;">লস/প্রফিট শেয়ার (৪৭%)</small>
            <h3 style="margin: 10px 0;">৳<?php echo number_format($user['player_loss_profit'], 2); ?></h3>
        </div>
    </div>

    <div class="card" style="margin-top: 20px; text-align: left;">
        <h4 style="margin: 0; color: #ffdf1b;">কিভাবে কাজ করে?</h4>
        <ul style="font-size: 13px; color: #bbb; padding-left: 20px;">
            <li>আপনার কোড দিয়ে কেউ জয়েন করলে সে আপনার মেম্বার।</li>
            <li>সে যতবার ডিপোজিট করবে, আপনি সাথে সাথে ৫% পাবেন।</li>
            <li>মাসের শেষে প্লেয়ারের নিট লস/প্রফিটের ওপর ৪৭% সেটেলমেন্ট হবে।</li>
        </ul>
    </div>

    <script>
        function copyCode() {
            var code = document.getElementById("refCode").innerText;
            navigator.clipboard.writeText(code);
            alert("কোড কপি করা হয়েছে: " + code);
        }
    </script>

</body>
</html>
