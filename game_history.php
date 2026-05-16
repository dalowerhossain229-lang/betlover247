<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
$user = $_SESSION['user_id'];

// সরাসরি 'bets' টেবিল থেকে এভিয়েটর গেমের লাইভ ডেটা আনা হবে
$logs = $conn->query("SELECT * FROM bets WHERE username = '$user' ORDER BY id DESC LIMIT 20");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Game History</title>
    <style>
        body { background: #000; color: #fff; font-family: sans-serif; padding: 15px; }
        .log-card { background: #111; padding: 12px; border-radius: 8px; border: 1px solid #333; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; }
        .bet-status { font-weight: bold; padding: 4px 10px; border-radius: 4px; font-size: 13px; }
        .win { background: rgba(0, 255, 185, 0.15); color: #00ffb9; }
        .loss { background: rgba(255, 68, 68, 0.15); color: #ff4444; }
        .pending { background: rgba(255, 204, 0, 0.15); color: #ffcc00; }
    </style>
</head>
<body>
    <h3>🎮 Game History</h3>
    <?php if($logs && $logs->num_rows > 0): while($row = $logs->fetch_assoc()): ?>
        <div class="log-card">
            <div>
                <small><?php echo $row['created_at'] ?? 'Just Now'; ?></small><br>
                <b><?php echo htmlspecialchars($row['game_id'] ?? 'Aviator'); ?></b>
            </div>
            <div>
                <span style="margin-right: 15px; color: #aaa;">Stake: ৳<?php echo number_format($row['amount'], 2); ?></span>
                
                <?php 
                $status = strtolower($row['status'] ?? 'bet');
                if ($status == 'win') {
                    echo '<span class="bet-status win">WIN  </span>';
                } elseif ($status == 'loss') {
                    echo '<span class="bet-status loss">LOSS ❌</span>';
                } else {
                    echo '<span class="bet-status pending">PENDING ⏳</span>';
                }
                ?>
            </div>
        </div>
    <?php endwhile; else: ?>
        <p style="color: #555; text-align: center; margin-top: 50px;">কোনো গেম হিস্ট্রি পাওয়া যায়নি।</p>
    <?php endif; ?>
</body>
</html>
