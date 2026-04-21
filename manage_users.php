
<?php
session_start();
include 'db.php';

// ১. ইউজারের ব্যালেন্স বা স্ট্যাটাস আপডেট করার লজিক
if (isset($_POST['update_user'])) {
    $target_user = mysqli_real_escape_string($conn, $_POST['username']);
    $new_balance = mysqli_real_escape_string($conn, $_POST['balance']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $sql = "UPDATE users SET balance = '$new_balance', status = '$new_status' WHERE username = '$target_user'";
    
    if ($conn->query($sql)) {
        echo "<script>alert('ইউজার তথ্য সফলভাবে আপডেট হয়েছে!'); location.href='manage_users.php';</script>";
    } else {
        echo "<script>alert('এরর: আপডেট করা যায়নি।');</script>";
    }
}

// ২. সব ইউজারের লিস্ট ডাটাবেস থেকে নিয়ে আসা
$users_res = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Control | BetLover777</title>
    <style>
        body { background: #0a0f0d; color: white; font-family: 'Segoe UI', sans-serif; margin: 0; padding: 15px; }
        .user-card { background: #111; border: 1px solid #333; padding: 20px; border-radius: 12px; margin-bottom: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.5); }
        .user-id { color: #00ff88; font-weight: 900; font-size: 18px; margin-bottom: 15px; display: block; }
        .form-group { margin-bottom: 15px; display: flex; align-items: center; justify-content: space-between; }
        label { font-size: 13px; color: #aaa; }
        .edit-input { background: #000; color: #ffdf1b; border: 1px solid #444; padding: 10px; border-radius: 6px; width: 120px; font-weight: bold; text-align: right; }
        .status-select { background: #222; color: white; border: 1px solid #444; padding: 10px; border-radius: 6px; width: 120px; }
        .btn-save { width: 100%; padding: 14px; background: #00ff88; color: #000; border: none; font-weight: 900; border-radius: 8px; cursor: pointer; text-transform: uppercase; margin-top: 10px; }
    </style>
</head>
<body>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <a href="admin_panel.php" style="color: #00ff88; text-decoration: none; font-weight: bold;">← ড্যাশবোর্ড</a>
        <h3 style="margin: 0;">ইউজার ম্যানেজমেন্ট</h3>
    </div>

    <?php if($users_res->num_rows > 0): while($row = $users_res->fetch_assoc()): ?>
        <div class="user-card">
            <span class="user-id">👤 <?php echo $row['username']; ?></span>
            <?php
$user_ip = $row['last_ip'] ?? '0.0.0.0';
$ip_check = $conn->query("SELECT COUNT(*) as total FROM users WHERE last_ip = '$user_ip'");
$ip_count = ($ip_check) ? $ip_check->fetch_assoc()['total'] : 1;
?>
<div style="font-size: 12px; margin-bottom: 10px; color: <?php echo ($ip_count > 1) ? '#ff4d4d' : '#00ff88'; ?>;">
    <b>IP:</b> <?php echo $user_ip; ?> <?php if($ip_count > 1) echo "(Duplicate: $ip_count)"; ?>
</div>

            <form method="POST">
                <input type="hidden" name="username" value="<?php echo $row['username']; ?>">
                
                <div class="form-group">
                    <label>ব্যালেন্স (৳):</label>
                    <input type="number" name="balance" value="<?php echo $row['balance']; ?>" class="edit-input" step="0.01">
                </div>

                <div class="form-group">
                    <label>ইউজার স্ট্যাটাস:</label>
                    <select name="status" class="status-select">
                        <option value="active" <?php echo ($row['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="banned" <?php echo ($row['status'] == 'banned') ? 'selected' : ''; ?>>Banned</option>
                    </select>
                </div>

                <button type="submit" name="update_user" class="btn-save">Save Changes</button>
            </form>
        </div>
    <?php endwhile; else: ?>
        <p style="text-align: center; color: #555;">কোনো ইউজার পাওয়া যায়নি।</p>
    <?php endif; ?>

</body>
</html>
