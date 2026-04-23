<?php
session_start();
include 'db.php';

// ১. অ্যাডমিন অ্যাকশন (Approve/Reject)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    $res = $conn->query("SELECT * FROM deposits WHERE id = $id");
    if ($res->num_rows > 0) {
        $dep = $res->fetch_assoc();
        $user = $dep['username'];
        $amount = $dep['amount'];

                if ($action == 'approve') {
            // ১. ইউজারের ব্যালেন্স এবং ডিপোজিট স্ট্যাটাস আপডেট
            $conn->query("UPDATE users SET balance = balance + $amount WHERE username = '$user'");
            $conn->query("UPDATE deposits SET status = 'approved' WHERE id = $id");

            // ২. অ্যাফিলিয়েট ৫% ইনস্ট্যান্ট কমিশন লজিক
            $instant_comm = $amount * 0.05; 
            $ref_res = $conn->query("SELECT ref_by FROM users WHERE username = '$user'");
            if ($ref_res && $ref_res->num_rows > 0) {
                $ref_data = $ref_res->fetch_assoc();
                $ref_code = $ref_data['ref_by'];
                if (!empty($ref_code)) {
                    $conn->query("UPDATE users SET balance = balance + $instant_comm, aff_instant_earned = aff_instant_earned + $instant_comm WHERE ref_code = '$ref_code'");
                }
            }

            echo "<script>alert('ডিপোজিট সফলভাবে অ্যাপ্রুভ হয়েছে!'); location.href='manage_deposits.php';</script>";

        } elseif ($action == 'reject') {
            $conn->query("UPDATE deposits SET status = 'rejected' WHERE id = $id");
            echo "<script>alert('ডিপোজিট রিজেক্ট করা হয়েছে!'); location.href='manage_deposits.php';</script>";
        }
    }
}
            

$pending_res = $conn->query("SELECT * FROM deposits WHERE status = 'pending' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Deposits | Admin</title>
    <style>
        body { background: #0a0f0d; color: white; font-family: sans-serif; padding: 15px; }
        .dep-card { background: #073128; border: 1px solid #00ff88; padding: 15px; border-radius: 12px; margin-bottom: 15px; }
        .method-tag { background: #ffdf1b; color: #000; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .trx-id { color: #ffdf1b; font-family: monospace; background: #000; padding: 2px 5px; border-radius: 4px; }
        .btn-group { display: flex; gap: 10px; margin-top: 15px; }
        .btn { flex: 1; padding: 12px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; text-decoration: none; text-align: center; font-size: 12px; }
        .btn-approve { background: #00ff88; color: #000; }
        .btn-reject { background: #ff4d4d; color: #fff; }
    </style>
</head>
<body>
    <h2 style="color: #00ff88; text-align: center;">PENDING DEPOSITS</h2>
    <a href="admin_panel.php" style="color: #aaa; text-decoration: none;">← ফিরে যান</a>
    <hr style="border: 0.5px solid #1a2a22; margin: 20px 0;">

    <?php if($pending_res->num_rows > 0): while($row = $pending_res->fetch_assoc()): ?>
        <div class="dep-card">
            <p><strong>ইউজার:</strong> <?php echo $row['username']; ?></p>
            <p><strong>পরিমাণ:</strong> ৳ <?php echo number_format($row['amount'], 2); ?></p>
            <p><strong>মেথড:</strong> <span class="method-tag"><?php echo $row['method']; ?></span></p> <!-- নতুন মেথড কলাম -->
            <p><strong>TrxID:</strong> <span class="trx-id"><?php echo $row['trx_id']; ?></span></p>
            
            <div class="btn-group">
                <a href="manage_deposits.php?action=approve&id=<?php echo $row['id']; ?>" class="btn btn-approve" onclick="return confirm('আপনি কি নিশ্চিত?')">APPROVE</a>
                <a href="manage_deposits.php?action=reject&id=<?php echo $row['id']; ?>" class="btn btn-reject">REJECT</a>
            </div>
        </div>
    <?php endwhile; else: ?>
        <p style="text-align: center; color: #555;">কোনো পেন্ডিং রিকোয়েস্ট নেই।</p>
    <?php endif; ?>
</body>
</html>
