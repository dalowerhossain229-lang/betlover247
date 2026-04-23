<?php
session_start();
include 'db.php';

// ১. স্টাফ চেক
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Panel | BetLover777</title>
    <style>
        body { background: #0a0f0d; color: white; font-family: sans-serif; padding: 15px; }
        .menu-grid { display: grid; grid-template-columns: 1fr; gap: 15px; margin-top: 20px; }
        .staff-btn { display: flex; align-items: center; gap: 15px; padding: 20px; background: #111; border: 1px solid #00ff88; color: white; text-decoration: none; border-radius: 12px; font-weight: bold; }
        .staff-btn i { font-size: 20px; color: #00ff88; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 1px solid #222; padding-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="color: #00ff88; margin: 0;">👥 STAFF PANEL</h2>
        <small style="color: #888;">Logged in as: <?php echo $_SESSION['user_id']; ?></small>
    </div>

    <div class="menu-grid">
        <!-- ১. ডিপোজিট রিকোয়েস্ট (PB সহ) -->
        <a href="manage_deposits.php" class="staff-btn">💰 Deposit Requests</a>
        <a href="manage_pb_deposits.php" class="staff-btn">🎁 PB Deposit Requests</a>

        <!-- ২. উইথড্র রিকোয়েস্ট -->
        <a href="manage_withdraws.php" class="staff-btn">📤 Withdraw Requests</a>

        <!-- ৩. লাইভ সাপোর্ট (আপনার সাপোর্ট চ্যাট পেজ) -->
        <a href="live_chat_admin.php" class="staff-btn" style="border-color: #ffdf1b; color: #ffdf1b;">💬 Live Support Chat</a>

        <!-- ৪. লগআউট -->
        <a href="logout.php" class="staff-btn" style="border-color: #ff4d4d; color: #ff4d4d; margin-top: 20px;">🚪 Logout</a>
    </div>
</body>
</html>
