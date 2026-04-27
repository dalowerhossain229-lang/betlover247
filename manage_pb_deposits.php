<?php
ob_start();
session_start();
include 'db.php';

// ১. PB রিকোয়েস্ট অ্যাপ্রুভ করার মাস্টার লজিক
if (isset($_POST['approve_pb'])) {
    $id = intval($_POST['id']);
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $final_amt = (float)$_POST['final_amount']; 
    $t_target = (float)$_POST['turnover_target']; 

    if($final_amt > 0) {
        // পিবি ব্যালেন্স বাড়ানো এবং পুরনো পিবি টার্নওভার রিসেট করা
        $conn->query("UPDATE users SET pb_balance = pb_balance + $final_amt, pb_turnover = 0 WHERE username = '$user'");
        
        // অ্যাডমিন সেটিংস টেবিলে এই ইউজারের জন্য নতুন পিবি টার্গেট আপডেট করা
        // (যদি আপনার settings টেবিল গ্লোবাল হয়, তবে এটি সবার প্রোফাইলে আপডেট হবে)
        $conn->query("UPDATE settings SET pb_target = $t_target WHERE id = 1");
        
        // রিকোয়েস্টটি পেন্ডিং থেকে অ্যাপ্রুভ করা
        $conn->query("UPDATE pb_deposits SET status = 'approved' WHERE id = $id");
        
        echo "<script>alert('PB Deposit Approved Successfully!'); location.href='manage_pb_deposits.php';</script>";
    }
}

// ২. রিজেক্ট করার লজিক
if (isset($_GET['reject_id'])) {
    $rid = intval($_GET['reject_id']);
    $conn->query("UPDATE pb_deposits SET status = 'rejected' WHERE id = $rid");
    header("Location: manage_pb_deposits.php");
}

// ৩. ডাটাবেস থেকে পেন্ডিং PB রিকোয়েস্টগুলো আনা
$pending = $conn->query("SELECT * FROM pb_deposits WHERE status = 'pending' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage PB Deposits</title>
    <style>
        body { background: #0a0f0d; color: white; font-family: sans-serif; padding: 15px; }
        .pb-card { background: #111; border: 1px solid #00bcd4; padding: 15px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(0,188,212,0.1); }
        .info { font-size: 14px; margin-bottom: 10px; border-bottom: 1px solid #222; padding-bottom: 10px; }
        .info p { margin: 5px 0; }
        label { font-size: 11px; color: #888; display: block; margin-top: 10px; }
        input { width: 100%; padding: 12px; margin: 5px 0; background: #000; border: 1px solid #333; color: #00bcd4; border-radius: 8px; box-sizing: border-box; font-weight: bold; outline: none; }
        .btn-approve { background: #00bcd4; color: #000; border: none; padding: 15px; width: 100%; border-radius: 8px; font-weight: bold; cursor: pointer; text-transform: uppercase; margin-top: 10px; }
        .btn-reject { display: block; text-align: center; color: #ff4d4d; font-size: 11px; text-decoration: none; margin-top: 10px; }
    </style>
</head>
<body>
    <h2 style="color: #00bcd4; text-align: center; text-transform: uppercase;">Pending PB Requests</h2>
    <p style="text-align: center;"><a href="admin_panel.php" style="color: #888; text-decoration: none;">← Back to Dashboard</a></p>
    <hr style="border: 0.1px solid #222; margin: 20px 0;">

    <?php if($pending && $pending->num_rows > 0): while($row = $pending->fetch_assoc()): ?>
        <div class="pb-card">
            <div class="info">
                <p><strong>User:</strong> <span style="color:#00bcd4;"><?php echo $row['username']; ?></span></p>
                <p><strong>Amount:</strong> ৳ <?php echo number_format($row['amount'], 2); ?></p>
                <p><strong>Method:</strong> <?php echo $row['method']; ?></p>
                <p><strong>TrxID:</strong> <span style="color:#ffdf1b;"><?php echo $row['trx_id']; ?></span></p>
            </div>
            
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="username" value="<?php echo $row['username']; ?>">
                
                <label>FINAL PB AMOUNT TO ADD (৳):</label>
                <input type="number" name="final_amount" value="<?php echo $row['amount']; ?>" required>
                
                <label>PB TURNOVER TARGET (৳):</label>
                <input type="number" name="turnover_target" placeholder="Example: 10000" required>
                
                <button type="submit" name="approve_pb" class="btn-approve">Approve & Lock Target</button>
            </form>
            <a href="?reject_id=<?php echo $row['id']; ?>" class="btn-reject" onclick="return confirm('Reject this request?')">Reject Request</a>
        </div>
    <?php endwhile; else: ?>
        <div style="text-align: center; color: #555; margin-top: 50px;">
            <p>No pending PB requests found.</p>
        </div>
    <?php endif; ?>
</body>
</html>
