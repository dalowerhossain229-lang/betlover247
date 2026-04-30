<?php
ob_start();
session_start();
include 'db.php';

$u = $_SESSION['username'] ?? $_SESSION['user_id'] ?? '';
if (empty($u)) { header("Location: index.php"); exit(); }

$query = $conn->query("SELECT * FROM users WHERE username = '$u' OR id = '$u'");
$user_data = $query->fetch_assoc();

// ১. ইউজারের সেভ করা নম্বরগুলো ধরা (যদি আগে সেভ করে থাকে)
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
        .card { background: #111; border: 1px solid #333; border-radius: 15px; padding: 20px; margin-bottom: 20px; }
        .balance-box { border: 1px solid #00ff88; padding: 20px; border-radius: 15px; text-align: center; background: rgba(0,255,136,0.05); margin-bottom: 25px; }
        label { color: #888; font-size: 13px; display: block; margin-bottom: 8px; }
        input, select { width: 100%; background: #222; color: #fff; padding: 12px; border-radius: 10px; border: 1px solid #444; box-sizing: border-box; font-size: 15px; margin-bottom: 15px; }
        input:focus { border-color: #00ff88; outline: none; }
        .btn-submit { width: 100%; background: #00ff88; color: #000; padding: 15px; border-radius: 10px; border: none; font-weight: bold; font-size: 16px; cursor: pointer; text-transform: uppercase; }
        .save-btn { background: #555; font-size: 11px; padding: 5px 10px; border-radius: 5px; color: #fff; text-decoration: none; float: right; margin-top: -30px; position: relative; }
    </style>
</head>
<body>

    <h2 style="color: #00ff88; text-align: center;">🏧 WITHDRAW</h2>

    <!-- ১. ব্যালেন্স কার্ড -->
    <div class="balance-box">
        <small style="color: #888;">AVAILABLE BALANCE</small>
        <h1 style="color: #ffdf1b; margin: 5px 0;">৳ <?php echo number_format($user_data['balance'], 2); ?></h1>
        <small style="color: #555;">Limit: ৳100 - ৳25,000</small>
    </div>

    <!-- ২. নম্বর সেভ করার অপশন (বিকাশ ও নগদ) -->
    <div class="card">
        <h3 style="color: #00ff88; font-size: 16px; margin-top: 0;">📋 পেমেন্ট নম্বর সেটআপ</h3>
        
        <form action="save_payment_numbers.php" method="POST">
            <label>বিকাশ পার্সোনাল:</label>
            <input type="text" name="bkash" value="<?php echo $saved_bkash; ?>" placeholder="নম্বর লিখুন">
            
            <label>নগদ পার্সোনাল:</label>
            <input type="text" name="nagad" value="<?php echo $saved_nagad; ?>" placeholder="নম্বর লিখুন">
            
            <button type="submit" style="background: #333; color: #fff; border: 1px solid #555; padding: 8px; border-radius: 5px; width: auto; font-size: 12px;">নম্বর সেভ করুন</button>
        </form>
    </div>

    <!-- ৩. আসল উইথড্র ফর্ম -->
    <div class="card">
        <h3 style="color: #ffdf1b; font-size: 16px; margin-top: 0;">📤 উইথড্র রিকোয়েস্ট</h3>
        <form action="process_withdraw.php" method="POST">
            
            <label>পেমেন্ট মেথড:</label>
            <select name="method" required id="methodSelect">
                <option value="bkash">বিকাশ (<?php echo $saved_bkash ? $saved_bkash : 'নম্বর সেট নেই'; ?>)</option>
                <option value="nagad">নগদ (<?php echo $saved_nagad ? $saved_nagad : 'নম্বর সেট নেই'; ?>)</option>
            </select>

            <label>টাকার পরিমাণ:</label>
            <input type="number" name="amount" min="100" max="25000" placeholder="৳১০০ - ৳২৫০০০" required>

            <button type="submit" class="btn-submit">রিকোয়েস্ট পাঠান</button>
        </form>
    </div>

    <p style="text-align: center; color: #444; font-size: 11px;">⚠️ টার্নওভার পূরণ না হলে রিকোয়েস্ট অটো-ক্যানসেল হবে।</p>

</body>
</html>
