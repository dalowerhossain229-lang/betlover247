<?php
session_start();
include 'db.php';

// ১. নতুন স্টাফ/এজেন্ট/এফিলিয়েট একাউন্ট তৈরি
if (isset($_POST['add_staff'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role']; // এখানে sub_admin, agent, staff, affiliate আসবে
    
    $conn->query("INSERT INTO admin_users (username, password, role, status) VALUES ('$user', '$pass', '$role', 'active')");
    echo "<script>alert('$role একাউন্ট সফলভাবে তৈরি হয়েছে!'); location.href='manage_staff.php';</script>";
}

// ২. সব মেম্বার লিস্ট নিয়ে আসা (অ্যাডমিন বাদে বাকি সবাই)
$staff_res = $conn->query("SELECT * FROM admin_users WHERE role != 'admin' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Staff, Agent & Affiliate Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background: #0a0f0d; color: white; font-family: sans-serif; padding: 15px; }
        .form-card { background: #073128; border: 1.5px solid #00ff88; padding: 20px; border-radius: 12px; margin-bottom: 25px; box-shadow: 0 5px 15px rgba(0,255,136,0.1); }
        .staff-card { background: #111; border: 1px solid #333; padding: 15px; border-radius: 10px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; border-left: 4px solid #00ff88; }
        input, select { width: 100%; padding: 12px; margin-bottom: 12px; border-radius: 6px; border: 1px solid #444; background: #000; color: #fff; box-sizing: border-box; }
        .btn { width: 100%; padding: 14px; background: #00ff88; color: #000; border: none; font-weight: 900; border-radius: 8px; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; }
        .role-tag { background: #004d2e; padding: 3px 10px; border-radius: 4px; font-size: 10px; text-transform: uppercase; color: #00ff88; font-weight: bold; border: 1px solid #00ff88; }
        .affiliate-tag { background: #2d2600; color: #ffdf1b; border: 1px solid #ffdf1b; } /* এফিলিয়েট এর জন্য আলাদা কালার */
    </style>
</head>
<body>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <a href="admin_panel.php" style="color: #00ff88; text-decoration: none; font-weight: bold;">← ড্যাশবোর্ড</a>
        <h3 style="margin: 0;">STAFF CONTROL</h3>
    </div>

    <!-- নতুন একাউন্ট তৈরির ফর্ম -->
    <div class="form-card">
        <form method="POST">
            <input type="text" name="username" placeholder="ইউজার আইডি (Username)" required>
            <input type="password" name="password" placeholder="পাসওয়ার্ড (Password)" required>
            <select name="role" required>
                <option value="">রোল নির্বাচন করুন</option>
                <option value="sub_admin">Sub Admin</option>
                <option value="agent">Agent Account</option>
                <option value="staff">Staff Account</option>
                <option value="affiliate">Affiliate Account (নতুন)</option>
            </select>
            <button type="submit" name="add_staff" class="btn">CREATE NOW</button>
        </form>
    </div>

    <!-- লিস্ট সেকশন -->
    <h3 style="font-size: 16px; border-left: 3px solid #ffdf1b; padding-left: 10px; color: #eee;">ACTIVE MEMBERS LIST</h3>
    <?php if($staff_res->num_rows > 0): while($row = $staff_res->fetch_assoc()): ?>
        <div class="staff-card">
            <div>
                <strong style="font-size: 15px;"><?php echo $row['username']; ?></strong><br>
                <!-- রোল অনুযায়ী আলাদা ট্যাগ -->
                <span class="role-tag <?php echo ($row['role'] == 'affiliate') ? 'affiliate-tag' : ''; ?>">
                    <?php echo str_replace('_', ' ', $row['role']); ?>
                </span>
            </div>
            <div style="text-align: right;">
                <span style="color: #00ff88; font-size: 11px; display: block;"><?php echo $row['status']; ?></span>
                <small style="color: #444; font-size: 9px;"><?php echo date('d M Y', strtotime($row['created_at'])); ?></small>
            </div>
        </div>
    <?php endwhile; else: ?>
        <p style="text-align: center; color: #444; margin-top: 20px;">কোনো মেম্বার এখনো তৈরি করা হয়নি।</p>
    <?php endif; ?>
</body>
</html>
