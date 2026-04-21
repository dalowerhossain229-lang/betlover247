<?php
session_start();
include 'db.php';
include 'header.php'; // আপনার সেই সুন্দর হেডারটি এখানেও থাকবে

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user_id'];
$res = $conn->query("SELECT * FROM users WHERE username = '$user'");
$userData = $res->fetch_assoc();

// ডাটাবেস থেকে তথ্য (আপনার প্রয়োজন অনুযায়ী)
$balance = $userData['balance'] ?? 0.00;
$bonus = $userData['bonus_balance'] ?? 0.00;
$turnover_done = $userData['turnover_done'] ?? 0;
$turnover_target = $userData['turnover_target'] ?? 1000; // উদাহরণ
$turnover_percent = ($turnover_done / $turnover_target) * 100;
?>

<div class="profile-container" style="padding: 15px; background: var(--dark); min-height: 100vh;">
    
    <!-- ১. ব্যালেন্স ও বোনাস কার্ড -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px;">
        <div style="background: rgba(0,255,136,0.1); border: 1px solid var(--neon); padding: 15px; border-radius: 10px; text-align: center;">
            <small style="color: #ccc;">Main Balance</small>
            <h2 style="color: var(--gold); margin: 5px 0;">৳ <?php echo number_format($balance, 2); ?></h2>
        </div>
        <div style="background: rgba(255,223,27,0.1); border: 1px solid var(--gold); padding: 15px; border-radius: 10px; text-align: center;">
            <small style="color: #ccc;">Bonus Balance</small>
            <h2 style="color: var(--neon); margin: 5px 0;">৳ <?php echo number_format($bonus, 2); ?></h2>
        </div>
    </div>

    <!-- ২. টার্নওভার লাইন (Progress Bar) -->
    <div style="background: #111; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #333;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 12px;">
            <span>Turnover Progress</span>
            <span><?php echo $turnover_done; ?> / <?php echo $turnover_target; ?></span>
        </div>
        <div style="width: 100%; height: 8px; background: #222; border-radius: 10px; overflow: hidden;">
            <div style="width: <?php echo $turnover_percent; ?>%; height: 100%; background: var(--neon); box-shadow: 0 0 10px var(--neon);"></div>
        </div>
    </div>

    <!-- ৩. মেনু অপশনসমূহ -->
    <div class="profile-menu" style="display: flex; flex-direction: column; gap: 10px;">
        <button class="p-menu-btn" onclick="location.href='live_chat.php'"><i class="fa-solid fa-headset"></i> Live Chat</button>
        <button class="p-menu-btn" onclick="location.href='transactions.php'"><i class="fa-solid fa-list-check"></i> Transaction History</button>
        <button class="p-menu-btn" onclick="location.href='bet_history.php'"><i class="fa-solid fa-clock-rotate-left"></i> Bet History</button>
        <button class="p-menu-btn" onclick="openUpdatePass()"><i class="fa-solid fa-key"></i> Update Password</button>
        <button class="p-menu-btn" style="color: #ff4d4d; border-color: #ff4d4d;" onclick="handleLogout()"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
    </div>
</div>

<style>
.p-menu-btn {
    width: 100%;
    padding: 15px;
    background: #0a1510;
    border: 1px solid #1a2a22;
    border-radius: 8px;
    color: white;
    text-align: left;
    font-weight: bold;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: 0.3s;
}
.p-menu-btn i { color: var(--neon); width: 20px; }
.p-menu-btn:hover { background: #073128; border-color: var(--neon); }
</style>

<?php include 'footer.php'; ?>
