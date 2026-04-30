   <?php
ob_start();
session_start();
include 'db.php';

// ১. সেশন থেকে ইউজার চেক
$u = $_SESSION['username'] ?? $_SESSION['user_id'] ?? '';
if (empty($u)) {
    header("Location: index.php");
    exit();
}

// ২. ডাটাবেস থেকে ইউজারের সব তাজা তথ্য আনা
$query = $conn->query("SELECT * FROM users WHERE username = '$u' OR id = '$u'");
$user_data = $query->fetch_assoc();

// ৩. টার্নওভার লজিক (এটি ব্যাকএন্ডে কাজ করবে)
$done = (float)($user_data['main_t'] ?? 0); 
$target = (float)($user_data['t_main'] ?? 3250); 
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw - BetLover</title>
    <style>
        body { background: #000; color: #fff; font-family: sans-serif; text-align: center; margin: 0; padding: 20px; }
        .balance-box { border: 1px solid #00ff88; padding: 20px; border-radius: 15px; margin-bottom: 25px; background: rgba(0,255,136,0.05); }
        .withdraw-card { background: #111; border: 1px solid #333; border-radius: 15px; padding: 25px; text-align: left; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .input-group { margin-bottom: 15px; }
        label { color: #888; font-size: 13px; display: block; margin-bottom: 8px; }
        select, input { width: 100%; background: #222; color: #fff; padding: 12px; border-radius: 8px; border: 1px solid #444; box-sizing: border-box; font-size: 15px; }
        select:focus, input:focus { border-color: #00ff88; outline: none; }
        .btn-submit { width: 100%; background: #00ff88; color: #000; padding: 15px; border-radius: 8px; border: none; font-weight: bold; font-size: 16px; margin-top: 15px; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; }
        .note { color: #555; font-size: 12px; text-align: center; margin-top: 20px; line-height: 1.5; }
    </style>
</head>
<body>

    <h2 style="color: #00ff88; margin-bottom: 30px;">🏧 WITHDRAW</h2>

    <!-- ১. বর্তমান ব্যালেন্স প্রদর্শন -->
    <div class="balance-box">
        <small style="color: #888; letter-spacing: 1px;">AVAILABLE BALANCE</small>
        <h1 style="color: #ffdf1b; margin: 10px 0;">৳ <?php echo number_format($user_data['balance'], 2); ?></h1>
    </div>

    <!-- ২. সরাসরি উইথড্র ফর্ম -->
    <div class="withdraw-card">
        <form action="process_withdraw.php" method="POST">
            
            <div class="input-group">
                <label>পেমেন্ট মেথড সিলেক্ট করুন:</label>
                <select name="method" required>
                    <option value="bkash">বিকাশ (bKash)</option>
                    <option value="nagad">নগদ (Nagad)</option>
                    <option value="rocket">রকেট (Rocket)</option>
                </select>
            </div>

            <div class="input-group">
                <label>আপনার নম্বর দিন:</label>
                <input type="text" name="number" placeholder="017XXXXXXXX" required pattern="[0-9]{11}">
            </div>

            <div class="input-group">
                <label>উইথড্র পরিমাণ (৳১০০ - ৳<?php echo number_format($user_data['balance'], 0, '.', ''); ?>):</label>
                <input type="number" name="amount" min="100" max="<?php echo $user_data['balance']; ?>" placeholder="টাকার পরিমাণ লিখুন" required>
            </div>

            <button type="submit" class="btn-submit">রিকোয়েস্ট পাঠান</button>
        </form>

        <p class="note">
            ⚠️ তথ্য ভুল হলে রিকোয়েস্ট বাতিল হতে পারে। <br>
            আপনার বর্তমান টার্নওভার প্রগ্রেস: <b><?php echo number_format($done, 0); ?> / <?php echo number_format($target, 0); ?></b>
        </p>
    </div>

</body>
</html>
         
