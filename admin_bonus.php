<?php
session_start();
include 'db.php';

// বোনাস প্রসেসিং লজিক
if (isset($_POST['send_bonus'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $amount = floatval($_POST['amount']);
    $target = intval($_POST['target']);

    // ইউজারের বোনাস ব্যালেন্স এবং টার্নওভার টার্গেট আপডেট
    $sql = "UPDATE users SET 
                    bonus_balance = bonus_balance + $amount, 
        bonus_target = $target 

            WHERE username = '$user'";

    if ($conn->query($sql)) {
        echo "<script>alert('৳$amount বোনাস সফলভাবে পাঠানো হয়েছে!'); location.href='admin_bonus.php';</script>";
    }
}

$users = $conn->query("SELECT username, bonus_balance, bonus_target FROM users ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body { background: #0a0f0d; color: white; font-family: sans-serif; padding: 15px; }
        .card { background: #111; border: 1px solid #ffdf1b; padding: 15px; border-radius: 12px; margin-bottom: 15px; }
        input { width: 100%; padding: 12px; margin: 10px 0; background: #000; border: 1px solid #333; color: #ffdf1b; border-radius: 8px; box-sizing: border-box; }
        .btn { background: #ffdf1b; color: #000; border: none; padding: 15px; width: 100%; border-radius: 8px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <h2 style="color: #ffdf1b; text-align: center;">🎁 SEND BONUS</h2>
    <p style="text-align:center;"><a href="admin_panel.php" style="color:#888; text-decoration:none;">← Back</a></p>

    <?php while($u = $users->fetch_assoc()): ?>
        <div class="card">
            <h4 style="margin:0;">User: <?php echo $u['username']; ?></h4>
            <small style="color:#888;">Bonus: <?php echo $u['bonus_balance']; ?> | Target: <?php echo $u['bonus_target']; ?></small>

            <form method="POST">
                <input type="hidden" name="username" value="<?php echo $u['username']; ?>">
                <input type="number" name="amount" placeholder="বোনাস পরিমাণ (৳)" required>
                <input type="number" name="target" placeholder="টার্নওভার টার্গেট (৳)" required>
                <button type="submit" name="send_bonus" class="btn">SEND BONUS NOW</button>
            </form>
        </div>
    <?php endwhile; ?>
</body>
</html>
