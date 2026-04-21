<?php
session_start();
include 'db.php';

// ১. অ্যাডমিন অ্যাকশন (Approve/Reject)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    // ডিপোজিট তথ্য নিয়ে আসা
    $res = $conn->query("SELECT * FROM deposits WHERE id = $id");
    if ($res->num_rows > 0) {
        $dep = $res->fetch_assoc();
        $user = $dep['username'];
        $amount = $dep['amount'];

        if ($action == 'approve' && $dep['status'] == 'pending') {
            // ইউজারের মেইন ব্যালেন্সে টাকা যোগ করা (SQL Update)
            $update_user = $conn->query("UPDATE users SET balance = balance + $amount WHERE username = '$user'");
            // ডিপোজিট স্ট্যাটাস আপডেট করা
            $update_dep = $conn->query("UPDATE deposits SET status = 'approved' WHERE id = $id");
            
            echo "<script>alert('ডিপোজিট সফলভাবে অ্যাপ্রুভ হয়েছে এবং ইউজারের ব্যালেন্সে টাকা যোগ হয়েছে!'); location.href='manage_deposits.php';</script>";
        } elseif ($action == 'reject' && $dep['status'] == 'pending') {
            $conn->query("UPDATE deposits SET status = 'rejected' WHERE id = $id");
            echo "<script>alert('ডিপোজিট রিকোয়েস্ট বাতিল করা হয়েছে!'); location.href='manage_deposits.php';</script>";
        }
    }
}

// ২. পেন্ডিং ডিপোজিট লিস্ট নিয়ে আসা
$pending_res = $conn->query("SELECT * FROM deposits WHERE status = 'pending' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Deposits | BetLover777</title>
    <style>
        body { background: #0a0f0d; color: white; font-family: 'Segoe UI', sans-serif; margin: 0; padding: 15px; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #00ff88; padding-bottom: 10px; margin-bottom: 20px; }
        .dep-card { background: #073128; border: 1px solid #00ff88; padding: 15px; border-radius: 12px; margin-bottom: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); }
        .dep-info { margin: 5px 0; font-size: 14px; }
        .trx-id { color: #ffdf1b; font-weight: bold; font-family: monospace; background: #000; padding: 2px 5px; border-radius: 4px; }
        .btn-group { display: flex; gap: 10px; margin-top: 15px; }
        .btn { flex: 1; padding: 12px; border: none; border-radius: 8px; font-weight: 900; cursor: pointer; text-transform: uppercase; text-decoration: none; text-align: center; font-size: 12px; }
        .btn-approve { background: #00ff88; color: #000; }
        .btn-reject { background: #ff4d4d; color: #fff; }
        .no-data { text-align: center; margin-top: 50px; color: #555; font-weight: bold; }
        .back-link { color: #00ff88; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

    <div class="header">
        <a href="admin_panel.php" class="back-link">← ড্যাশবোর্ড</a>
        <h3 style="margin: 0;">পেন্ডিং ডিপোজিট</h3>
    </div>

    <?php if($pending_res->num_rows > 0): while($row = $pending_res->fetch_assoc()): ?>
        <div class="dep-card">
            <div class="dep-info"><strong>ইউজার:</strong> <?php echo $row['username']; ?></div>
            <div class="dep-info"><strong>পরিমাণ:</strong> ৳ <?php echo number_format($row['amount'], 2); ?></div>
            <div class="dep-info"><strong>TrxID:</strong> <span class="trx-id"><?php echo $row['trx_id']; ?></span></div>
            <div class="dep-info" style="font-size: 11px; color: #888; margin-top: 10px;"><?php echo $row['created_at']; ?></div>
            
            <div class="btn-group">
                <a href="manage_deposits.php?action=approve&id=<?php echo $row['id']; ?>" class="btn btn-approve" onclick="return confirm('আপনি কি নিশ্চিত যে TrxID সঠিক?')">APPROVE</a>
                <a href="manage_deposits.php?action=reject&id=<?php echo $row['id']; ?>" class="btn btn-reject" onclick="return confirm('আপনি কি নিশ্চিত যে এটি বাতিল করবেন?')">REJECT</a>
            </div>
        </div>
    <?php endwhile; else: ?>
        <div class="no-data">
            <i class="fa-solid fa-folder-open" style="font-size: 40px; display: block; margin-bottom: 10px;"></i>
            বর্তমানে কোনো পেন্ডিং ডিপোজিট নেই
        </div>
    <?php endif; ?>

</body>
</html>
