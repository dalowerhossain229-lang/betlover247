<?php
session_start();
include 'db.php';

// ১. আজকের রিপোর্ট (পয়েন্ট ১ অনুযায়ী)
$today = date('Y-m-d');

$dep_res = $conn->query("SELECT SUM(amount) as total FROM deposits WHERE DATE(created_at) = '$today' AND status = 'approved'");
$total_dep = $dep_res->fetch_assoc()['total'] ?? 0;

$wd_res = $conn->query("SELECT SUM(amount) as total FROM withdraws WHERE DATE(created_at) = '$today' AND status = 'approved'");
$total_wd = $wd_res->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BetLover777 | Admin Panel</title>
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        body { background: #0a0f0d; color: white; font-family: 'Segoe UI', sans-serif; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #00ff88; padding-bottom: 10px; margin-bottom: 20px; }
        .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 25px; }
        .stat-card { background: #073128; padding: 20px; border-radius: 12px; border: 1px solid #00ff88; text-align: center; }
        .stat-card h2 { color: #00ff88; margin: 5px 0; }
        .menu-grid { display: grid; grid-template-columns: 1fr; gap: 12px; }
        .admin-btn { background: #111; color: white; padding: 15px; text-decoration: none; border-radius: 8px; border-left: 5px solid #00ff88; display: flex; align-items: center; gap: 15px; font-weight: bold; transition: 0.3s; }
        .admin-btn:hover { background: #073128; transform: translateX(5px); }
        .admin-btn i { width: 25px; color: #00ff88; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BETLOVER777 ADMIN</h1>
        <p>সঠিক তথ্যই আপনার ব্যবসার শক্তি</p>
    </div>

    <!-- আজকের সামারি -->
    <div class="stats-grid">
        <div class="stat-card">
            <small>আজকের ডিপোজিট</small>
            <h2>৳ <?php echo number_format($total_dep, 2); ?></h2>
        </div>
        <div class="stat-card">
            <small>আজকের উইথড্র</small>
            <h2>৳ <?php echo number_format($total_wd, 2); ?></h2>
        </div>
    </div>

    <!-- মেইন মেনু (আপনার ১৫টি পয়েন্টের প্রতিফলন) -->
    <div class="menu-grid">
        <a href="manage_deposits.php" class="admin-btn"><i class="fa-solid fa-money-bill-wave"></i> ডিপোজিট রিকোয়েস্ট (Approve/Cancel)</a>
        <a href="manage_withdraws.php" class="admin-btn"><i class="fa-solid fa-hand-holding-dollar"></i> উইথড্র রিকোয়েস্ট (Paid/Cancel)</a>
        <a href="manage_users.php" class="admin-btn"><i class="fa-solid fa-users-gear"></i> ইউজার কন্ট্রোল ও ব্যালেন্স এডিট</a>
        <a href="manage_staff.php" class="admin-btn"><i class="fa-solid fa-user-shield"></i> স্টাফ, এজেন্ট ও সাব-এডমিন</a>
        <a href="manage_games.php" class="admin-btn"><i class="fa-solid fa-gamepad"></i> গেম এপিআই ও আরটিপি (RTP)</a>
        <a href="manage_site.php" class="admin-btn"><i class="fa-solid fa-gears"></i> স্লাইডার ও নোটিশ বোর্ড কন্ট্রোল</a>
        <a href="manage_promo.php" class="admin-btn"><i class="fa-solid fa-gift"></i> প্রোমো আবেদন টেবিল</a>
        <a href="logout.php" class="admin-btn" style="border-left-color: #ff4d4d; color: #ff4d4d;"><i class="fa-solid fa-power-off"></i> লগআউট</a>
    </div>
</body>
</html>
