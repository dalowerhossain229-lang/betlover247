<?php
include 'db.php';

// ১. নম্বর আপডেট লজিক
if(isset($_POST['update_nums'])){
    $bk_p = $_POST['bk_p']; $bk_a = $_POST['bk_a'];
    $ng_p = $_POST['ng_p']; $ng_a = $_POST['ng_a'];
    
    $sql = "UPDATE settings SET 
            admin_bkash_p='$bk_p', admin_bkash_a='$bk_a', 
            admin_nagad_p='$ng_p', admin_nagad_a='$ng_a' 
            WHERE id=1";
            
    $conn->query($sql);
    echo "<script>alert('নম্বর আপডেট হয়েছে!'); window.location.href='manage_payment_methods.php';</script>";
}

// ২. ডাটা আনা
$st = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();
?>

<body style="background:#000; color:#fff; font-family:sans-serif; padding:50px; text-align:center;">
    <h2 style="color:#ffdf1b;">💰 Manage Deposit Numbers</h2>
    <div style="background:#111; padding:30px; border-radius:15px; border:1px solid #333; display:inline-block; text-align:left;">
        <form method="POST">
            বিকাশ পারসোনাল: <br><input type="text" name="bk_p" value="<?php echo $st['admin_bkash_p']; ?>" style="width:250px; padding:10px; margin-bottom:10px; background:#222; color:#fff; border:1px solid #444;"><br>
            বিকাশ এজেন্ট: <br><input type="text" name="bk_a" value="<?php echo $st['admin_bkash_a']; ?>" style="width:250px; padding:10px; margin-bottom:10px; background:#222; color:#fff; border:1px solid #444;"><br>
            নগদ পারসোনাল: <br><input type="text" name="ng_p" value="<?php echo $st['admin_nagad_p']; ?>" style="width:250px; padding:10px; margin-bottom:10px; background:#222; color:#fff; border:1px solid #444;"><br>
            নগদ এজেন্ট: <br><input type="text" name="ng_a" value="<?php echo $st['admin_nagad_a']; ?>" style="width:250px; padding:10px; margin-bottom:20px; background:#222; color:#fff; border:1px solid #444;"><br>
            <button type="submit" name="update_nums" style="width:100%; background:#ffdf1b; color:#000; padding:12px; border:none; border-radius:8px; font-weight:bold; cursor:pointer;">UPDATE NOW</button>
        </form>
    </div>
    <br><br><a href="admin_panel.php" style="color:#888; text-decoration:none;">← ব্যাকে যান</a>
</body>
