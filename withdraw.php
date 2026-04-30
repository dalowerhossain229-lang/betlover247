<?php
ob_start();
session_start();
include 'db.php';

$u = $_SESSION['username'] ?? $_SESSION['user_id'] ?? '';
if (empty($u)) { header("Location: index.php"); exit(); }

$query = $conn->query("SELECT balance, bkash_number, nagad_number FROM users WHERE username = '$u'");
$user_data = $query->fetch_assoc();

$saved_bkash = $user_data['bkash_number'] ?? '';
$saved_nagad = $user_data['nagad_number'] ?? '';
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw - BetLover</title>
    <style>
        body { background: #000; color: #fff; font-family: sans-serif; margin: 0; padding: 15px; }
        .card { background: #111; border: 1px solid #333; border-radius: 15px; padding: 25px; margin-top: 20px; }
        .balance-box { border: 1px solid #00ff88; padding: 20px; border-radius: 15px; text-align: center; background: rgba(0,255,136,0.05); margin-bottom: 25px; }
        label { color: #888; font-size: 13px; display: block; margin-bottom: 8px; }
        input, select { width: 100%; background: #222; color: #fff; padding: 14px; border-radius: 12px; border: 1px solid #444; box-sizing: border-box; font-size: 15px; margin-bottom: 20px; }
        .btn-submit { width: 100%; background: #00ff88; color: #000; padding: 16px; border-radius: 12px; border: none; font-weight: bold; font-size: 16px; cursor: pointer; text-transform: uppercase; }
        .limit-text { color: #555; font-size: 12px; display: block; margin-top: 5px; }
    </style>
</head>
<body>

    <h2 style="color: #00ff88; text-align: center; margin-bottom: 30px;">🏧 WITHDRAW</h2>

    <div class="balance-box">
        <small style="color: #888;">AVAILABLE BALANCE</small>
        <h1 style="color: #ffdf1b; margin: 5px 0;">৳ <?php echo number_format($user_data['balance'], 2); ?></h1>
        <span class="limit-text">লিমিট: ৳১০০ - ৳২৫,০০০</span>
    </div>

    <div class="card">
        <form action="process_withdraw.php" method="POST">
            
            <label>পেমেন্ট মেথড সিলেক্ট করুন:</label>
            <select name="method" required>
                <option value="bkash">বিকাশ (<?php echo $saved_bkash ?: 'নম্বর সেট নেই'; ?>)</option>
                <option value="nagad">নগদ (<?php echo $saved_nagad ?: 'নম্বর সেট নেই'; ?>)</option>
            </select>

            <label>টাকার পরিমাণ:</label>
            <input type="number" name="amount" min="100" max="25000" placeholder="৳১০০ - ৳২৫০০০" required>

            <button type="submit" class="btn-submit">রিকোয়েস্ট পাঠান</button>
        </form>
    </div>

    <p style="text-align: center; color: #444; font-size: 12px; margin-top: 20px;">
        নম্বর পরিবর্তন করতে <a href="profile.php" style="color: #00ff88; text-decoration: none;">প্রোফাইলে যান</a>
    </p>

</body>
</html>
