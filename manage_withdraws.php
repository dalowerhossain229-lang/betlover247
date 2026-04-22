<?php
session_start();
include 'db.php';

// ১. উইথড্র রিকোয়েস্ট প্রসেসিং (Approve/Reject)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    $res = $conn->query("SELECT * FROM withdraws WHERE id = $id");
    if ($res->num_rows > 0) {
        $wd = $res->fetch_assoc();
        $user = $wd['username'];
        $amount = $wd['amount'];

        if ($action == 'approve' && $wd['status'] == 'pending') {
            // উইথড্র সফল করা
            $conn->query("UPDATE withdraws SET status = 'approved' WHERE id = $id");
            echo "<script>alert('উইথড্র সফলভাবে অ্যাপ্রুভ হয়েছে!'); location.href='manage_withdraws.php';</script>";
        } elseif ($action == 'reject' && $wd['status'] == 'pending') {
            // উইথড্র বাতিল এবং ইউজারের ব্যালেন্সে টাকা ফেরত দেওয়া
            $conn->query("UPDATE users SET balance = balance + $amount WHERE username = '$user'");
            $conn->query("UPDATE withdraws SET status = 'rejected' WHERE id = $id");
            echo "<script>alert('উইথড্র বাতিল এবং টাকা ফেরত দেওয়া হয়েছে!'); location.href='manage_withdraws.php';</script>";
        }
    }
}

// ২. পেন্ডিং রিকোয়েস্টগুলো ডাটাবেস থেকে নেওয়া
$pending_res = $conn->query("SELECT * FROM withdraws WHERE status = 'pending' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Withdrawals | Admin</title>
    <style>
        body { background: #0a0f0d; color: white; font-family: sans-serif; padding: 15px; }
        .wd-card { background: #111; border: 1px solid #ff4d4d; padding: 15px; border-radius: 12px; margin-bottom: 15px; box-shadow: 0 4px 10px rgba(255,77,77,0.1); }
        .method-tag { background: #ffdf1b; color: #000; padding: 3px 10px; border-radius: 5px; font-weight: bold; font-size: 12px; text-transform: uppercase; }
        .btn-group { display: flex; gap: 10px; margin-top: 15px; }
        .btn { flex: 1; padding: 12px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; text-decoration: none; text-align: center; font-size: 13px; }
        .btn-approve { background: #00ff88; color: #000; }
        .btn-reject { background: #ff4d4d; color: #fff; }
    </style>
</head>
<body>
    <h2 style="color: #ff4d4d; text-align: center;">PENDING WITHDRAWALS</h2>
    <a href="admin_panel.php" style="color: #888; text-decoration: none; font-size: 14px;">← Back to Dashboard</a>
    <hr style="border: 0.1px solid #222; margin: 20px 0;">

    <?php if($pending_res->num_rows > 0): while($row = $pending_res->fetch_assoc()): ?>
        <div class="wd-card">
            <p style="margin: 5px 0;"><strong>ইউজার:</strong> <?php echo $row['username']; ?></p>
            <p style="margin: 5px 0;"><strong>পরিমাণ:</strong> <span style="color:#ffdf1b; font-size: 18px; font-weight:bold;">৳ <?php echo number_format($row['amount'], 2); ?></span></p>
            <p style="margin: 10px 0;"><strong>মেথড ও নম্বর:</strong> <span class="method-tag"><?php echo $row['method']; ?></span></p>
            
            <div class="btn-group">
                <a href="manage_withdraws.php?action=approve&id=<?php echo $row['id']; ?>" class="btn btn-approve" onclick="return confirm('আপনি কি নিশ্চিত যে এই উইথড্রটি অ্যাপ্রুভ করবেন?')">APPROVE</a>
                <a href="manage_withdraws.php?action=reject&id=<?php echo $row['id']; ?>" class="btn btn-reject" onclick="return confirm('বাতিল করলে টাকা ইউজারের ব্যালেন্সে ফেরত যাবে। আপনি কি নিশ্চিত?')">REJECT</a>
            </div>
        </div>
    <?php endwhile; else: ?>
        <div style="text-align: center; margin-top: 50px;">
            <p style="color: #555; font-size: 18px;">কোনো পেন্ডিং উইথড্র রিকোয়েস্ট নেই।</p>
        </div>
    <?php endif; ?>
</body>
</html>
