<?php
include 'db.php';
echo "<body style='background:#000; color:#fff; font-family:sans-serif; padding:20px;'>";
echo "<h2 style='color:#ffdf1b;'>💰 Affiliate Monthly Settlement (47%)</h2>";

// ১. সেটেলমেন্ট বাটন ক্লিক করলে টাকা আপডেট হবে
if(isset($_POST['settle_now'])){
    $aff_id = $_POST['aff_id'];
    $amount = $_POST['settle_amount'];
    
    // অ্যাফিলিয়েটের aff_balance এ টাকা যোগ করা এবং প্লেয়ারের স্ট্যাটাস রিসেট করা
    $conn->query("UPDATE users SET aff_balance = aff_balance + $amount, player_loss_profit = 0 WHERE id = '$aff_id'");
    echo "<script>alert('সফলভাবে ৪৭% সেটেলমেন্ট সম্পন্ন হয়েছে!'); window.location.href='admin_aff_settle.php';</script>";
}

// ২. অ্যাফিলিয়েট মেম্বারদের লিস্ট এবং তাদের ৪৭% হিসাব দেখানো
$affiliates = $conn->query("SELECT * FROM users WHERE is_affiliate = 1");

echo "<table border='1' style='width:100%; border-collapse:collapse; background:#111; text-align:center;'>";
echo "<tr style='background:#222; color:#00ff88;'><th>Username</th><th>Net Loss/Profit</th><th>47% Share</th><th>Action</th></tr>";

while($row = $affiliates->fetch_assoc()){
    $net = $row['player_loss_profit']; // এটি গেমের লজিক থেকে আসবে
    $share = $net * 0.47; 
    
    echo "<tr>";
    echo "<td>".$row['username']."</td>";
    echo "<td>৳".number_format($net, 2)."</td>";
    echo "<td style='color:#ffdf1b; font-weight:bold;'>৳".number_format($share, 2)."</td>";
    echo "<td>
            <form method='POST'>
                <input type='hidden' name='aff_id' value='".$row['id']."'>
                <input type='hidden' name='settle_amount' value='".$share."'>
                <button type='submit' name='settle_now' style='background:#00ff88; border:none; padding:8px 15px; border-radius:5px; cursor:pointer; font-weight:bold;'>Settle 47%</button>
            </form>
          </td>";
    echo "</tr>";
}
echo "</table></body>";
?>
