<?php
session_start();
include 'db.php';

// ১. প্রফিট ক্লিয়ার করার লজিক
if (isset($_POST['clear_ngr'])) {
    // যাদের NGR ০ এর বেশি শুধু তাদের টাকা ব্যালেন্সে যোগ হবে এবং NGR ০ হয়ে যাবে
    $sql = "UPDATE users SET 
            balance = balance + aff_monthly_ngr, 
            aff_monthly_ngr = 0 
            WHERE aff_monthly_ngr > 0";

    if ($conn->query($sql)) {
        echo "<script>alert('সফলভাবে সবার মাসিক ৪৭% প্রফিট মেইন ব্যালেন্সে যোগ করা হয়েছে!'); location.href='admin_aff_clear.php';</script>";
    }
}

// ২. বর্তমান মাসের জমানো প্রফিট রিপোর্ট
$total_pending = $conn->query("SELECT SUM(aff_monthly_ngr) as total FROM users")->fetch_assoc()['total'];
$aff_list = $conn->query("SELECT username, aff_monthly_ngr FROM users WHERE aff_monthly_ngr > 0");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body { background: #0a0f0d; color: white; font-family: sans-serif; padding: 20px; text-align: center; }
        .report-card { background: #111; border: 1px solid #ffdf1b; padding: 25px; border-radius: 15px; margin-bottom: 25px; }
        .btn-clear { background: #ffdf1b; color: #000; border: none; padding: 15px 30px; border-radius: 10px; font-weight: bold; cursor: pointer; font-size: 16px; }
        .list-table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        .list-table th, .list-table td { border-bottom: 1px solid #222; padding: 12px; text-align: left; }
    </style>
</head>
<body>
    <h2 style="color:#ffdf1b;">💰 AFFILIATE MONTHLY SETTLEMENT</h2>
    <p style="text-align:center;"><a href="admin_panel.php" style="color:#888; text-decoration:none;">← ব্যাক টু ড্যাশবোর্ড</a></p>

    <div class="report-card">
        <small style="color:#888;">মোট প্রেন্ডিং প্রফিট শেয়ার (৪৭%)</small>
        <h1 style="color:#ffdf1b; margin:10px 0;">৳ <?php echo number_format($total_pending ?? 0, 2); ?></h1>
        
        <form method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে সবার মাসিক প্রফিট ক্লিয়ার করবেন?')">
            <button type="submit" name="clear_ngr" class="btn-clear">RELEASE ALL PROFITS NOW</button>
        </form>
    </div>

    <h4 style="text-align:left; color:#00ff88;">অ্যাফিলিয়েট রিপোর্ট:</h4>
    <table class="list-table">
        <tr><th>User</th><th>Pending Profit</th></tr>
        <?php while($row = $aff_list->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['username']; ?></td>
                <td style="color:#ffdf1b;">৳ <?php echo number_format($row['aff_monthly_ngr'], 2); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
