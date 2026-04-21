<?php
session_start();
include 'db.php';

// ১. অ্যাডমিন অ্যাকশন (Approve/Reject)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == 'approve') {
        // স্ট্যাটাস শুধু অ্যাপ্রুভ হবে (টাকা উইথড্র দেওয়ার সময় ইউজার সাইট থেকেই কেটে নেওয়া হয়েছে)
        $conn->query("UPDATE withdraws SET status = 'approved' WHERE id = $id");
        echo "<script>alert('উইথড্র সফলভাবে অ্যাপ্রুভ (Paid) করা হয়েছে!'); location.href='manage_withdraws.php';</script>";
    } elseif ($action == 'reject') {
        // বাতিল করলে ইউজারের টাকা তার ব্যালেন্সে ফেরত দেওয়া
        $wd = $conn->query("SELECT * FROM withdraws WHERE id = $id")->fetch_assoc();
        $user = $wd['username'];
        $amount = $wd['amount'];
        
        $conn->query("UPDATE users SET balance = balance + $amount WHERE username = '$user'");
        $conn->query("UPDATE withdraws SET status = 'rejected' WHERE id = $id");
        echo "<script>alert('রিকোয়েস্ট বাতিল এবং ইউজারের টাকা ব্যালেন্সে ফেরত দেওয়া হয়েছে!'); location.href='manage_withdraws.php';</script>";
    }
}

// ২. পেন্ডিং লিস্ট নিয়ে আসা
$res = $conn->query("SELECT * FROM withdraws WHERE status = 'pending' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Withdraw Management | BetLover777</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background: #0a0f0d; color: white; font-family: sans-serif; padding: 15px; }
        .wd-card { background: #1a0a0a; border: 1px solid #ff4d4d; padding: 18px; border-radius: 12px; margin-bottom: 15px; box-shadow: 0 4px 15px rgba(255,77,77,0.1); }
        .info { margin: 8px 0; font-size: 14px; }
        .amount { color: #ffdf1b; font-weight: 900; font-size: 18px; }
        .method-tag { background: #333; padding: 2px 8px; border-radius: 4px; font-size: 11px; text-transform: uppercase; }
        .btn-group { display: flex; gap: 10px; margin-top: 15px; }
        .btn { flex: 1; padding: 12px; border: none; border-radius: 8px; font-weight: 900; cursor: pointer; text-align: center; text-decoration: none; font-size: 12px; }
        .btn-pay { background: #00ff88; color: #000; }
        .btn-cancel { background: #ff4d4d; color: #fff; }
    </style>
</head>
<body>
    <h2 style="color: #ff4d4d; text-align: center;">PENDING WITHDRAWS</h2>
    <a href="admin_panel.php" style="color: #aaa; text-decoration: none;">← ড্যাশবোর্ড</a>
    <hr style="border: 0.5px solid #1a1a1a; margin: 20px 0;">

    <?php if($res->num_rows > 0): while($row = $res->fetch_assoc()): ?>
        <div class="wd-card">
            <div class="info"><strong>ইউজার:</strong> <?php echo $row['username']; ?></div>
            <div class="info"><strong>পরিমাণ:</strong> <span class="amount">৳ <?php echo number_format($row['amount'], 2); ?></span></div>
            <div class="info"><strong>নম্বর:</strong> <?php echo $row['number']; ?> <span class="method-tag"><?php echo $row['method']; ?></span></div>
            <div class="info" style="color: #666; font-size: 11px; margin-top: 10px;"><?php echo $row['created_at']; ?></div>
            
            <div class="btn-group">
                <a href="manage_withdraws.php?action=approve&id=<?php echo $row['id']; ?>" class="btn btn-pay" onclick="return confirm('আপনি কি টাকা পাঠিয়েছেন?')">APPROVE (PAID)</a>
                <a href="manage_withdraws.php?action=reject&id=<?php echo $row['id']; ?>" class="btn btn-cancel" onclick="return confirm('বাতিল করলে টাকা ইউজারের একাউন্টে ফেরত যাবে। নিশ্চিত?')">CANCEL</a>
            </div>
        </div>
    <?php endwhile; else: ?>
        <p style="text-align: center; color: #444; margin-top: 50px;">বর্তমানে কোনো উইথড্র রিকোয়েস্ট নেই।</p>
    <?php endif; ?>
</body>
</html>
