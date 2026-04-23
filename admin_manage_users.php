<?php
session_start();
include 'db.php';

// ১. ইউজারকে এফিলিয়েট পারমিশন দেওয়ার লজিক
if (isset($_GET['make_aff'])) {
    $u = $_GET['make_aff'];
    $conn->query("UPDATE users SET is_affiliate = 1 WHERE username = '$u'");
    echo "<script>alert('$u এখন একজন এফিলিয়েট মেম্বার!'); location.href='admin_manage_users.php';</script>";
}

// ২. ইউজার লিস্ট নিয়ে আসা
$users = $conn->query("SELECT username, is_affiliate FROM users WHERE role = 'user' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        body { background: #0a0f0d; color: white; font-family: sans-serif; padding: 20px; }
        .u-card { background: #111; padding: 15px; border-radius: 10px; border: 1px solid #333; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; }
        .btn { background: #00ff88; color: #000; padding: 8px 15px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 12px; }
    </style>
</head>
<body>
    <h2 style="color:#00ff88;">👥 MANAGE USERS</h2>
    <hr style="border:0.1px solid #222; margin-bottom:20px;">

    <?php while($row = $users->fetch_assoc()): ?>
        <div class="u-card">
            <div>
                <b><?php echo $row['username']; ?></b> 
                <br>
                <small style="color:<?php echo $row['is_affiliate'] ? '#00ff88' : '#888'; ?>">
                    Status: <?php echo $row['is_affiliate'] ? 'Affiliate Partner' : 'General User'; ?>
                </small>
            </div>
            <?php if(!$row['is_affiliate']): ?>
                <a href="admin_manage_users.php?make_aff=<?php echo $row['username']; ?>" class="btn">Make Affiliate</a>
            <?php else: ?>
                <span style="color:#00ff88;">✅ Active</span>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</body>
</html>
