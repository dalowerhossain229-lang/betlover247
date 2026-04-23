 <?php
session_start();
include 'db.php';

// ১. PB রিকোয়েস্ট অ্যাপ্রুভ করার মাস্টার লজিক
if (isset($_POST['approve_pb'])) {
    $id = intval($_POST['id']);
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $final_amt = intval($_POST['final_amount']); 
    $t_target = intval($_POST['turnover_target']); 

    if($final_amt > 0) {
        // মেইন ব্যালেন্স বাড়ানো এবং ৩ নম্বর টার্নওভার বারে টার্গেট সেট করা
        $conn->query("UPDATE users SET balance = balance + $final_amt, pb_t_target = pb_t_target + $t_target WHERE username = '$user'");
        
        // রিকোয়েস্টটি পেন্ডিং থেকে অ্যাপ্রুভ করা
        $conn->query("UPDATE pb_deposits SET status = 'approved' WHERE id = $id");
        
        echo "<script>alert('PB সফলভাবে অ্যাপ্রুভ হয়েছে!'); location.href='manage_pb_deposits.php';</script>";
    }
}

// ২. রিজেক্ট করার লজিক
if (isset($_GET['reject_id'])) {
    $rid = intval($_GET['reject_id']);
    $conn->query("UPDATE pb_deposits SET status = 'rejected' WHERE id = $rid");
    header("Location: manage_pb_deposits.php");
}

// ৩. ডাটাবেস থেকে পেন্ডিং PB রিকোয়েস্টগুলো চেক করা
$pending = $conn->query("SELECT * FROM pb_deposits WHERE status = 'pending' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body { background: #0a0f0d; color: white; font-family: sans-serif; padding: 15px; }
        .pb-card { background: #111; border: 1px solid #00ff88; padding: 15px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(0,255,136,0.1); }
        .info { font-size: 14px; margin-bottom: 10px; border-bottom: 1px solid #222; padding-bottom: 10px; }
        input { width: 100%; padding: 12px; margin: 10px 0; background: #000; border: 1px solid #333; color: #00ff88; border-radius: 8px; box-sizing: border-box; font-weight: bold; }
        .btn-approve { background: #00ff88; color: #000; border: none; padding: 15px; width: 100%; border-radius: 8px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <h2 style="color: #00ff88; text-align: center;">PENDING PB REQUESTS</h2>
    <p style="text-align: center;"><a href="admin_panel.php" style="color: #888; text-decoration: none;">← ব্যাক টু ড্যাশবোর্ড</a></p>
    <hr style="border: 0.1px solid #222; margin: 20px 0;">

    <?php if($pending && $pending->num_rows > 0): while($row = $pending->fetch_assoc()): ?>
        <div class="pb-card">
            <div class="info">
                <p><strong>ইউজার:</strong> <?php echo $row['username']; ?></p>
                <p><strong>পরিমাণ:</strong> ৳ <?php echo $row['amount']; ?></p>
                <p><strong>TrxID:</strong> <?php echo $row['trx_id']; ?> (<?php echo $row['method']; ?>)</p>
            </div>
            
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="username" value="<?php echo $row['username']; ?>">
                
                <label style="font-size:11px; color:#888;">ফাইনাল ব্যালেন্স এড (৳):</label>
                <input type="number" name="final_amount" placeholder="যেমন: ২০০০" required>
                
                <label style="font-size:11px; color:#888;">PB টার্নওভার টার্গেট (৳):</label>
                <input type="number" name="turnover_target" placeholder="যেমন: ৪০০০০" required>
                
                <button type="submit" name="approve_pb" class="btn-approve">APPROVE NOW</button>
            </form>
            <p style="text-align:center;"><a href="manage_pb_deposits.php?reject_id=<?php echo $row['id']; ?>" style="color:#ff4d4d; font-size:11px; text-decoration:none;">Reject This</a></p>
        </div>
    <?php endwhile; else: ?>
        <p style="text-align: center; color: #555; margin-top: 50px;">কোনো পেন্ডিং PB রিকোয়েস্ট পাওয়া যায়নি।</p>
    <?php endif; ?>
</body>
</html>
   
