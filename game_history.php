<?php
session_start();
include 'db.php';
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
$user = $_SESSION['user_id'];
$logs = $conn->query("SELECT * FROM game_logs WHERE username = '$user' ORDER BY id DESC LIMIT 20");
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body { background: #000; color: #fff; font-family: sans-serif; padding: 15px; }
        .log-card { background: #111; padding: 12px; border-radius: 8px; border: 1px solid #333; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; }
        .bet { color: #ff4d4d; } .win { color: #00ff88; }
    </style>
</head>
<body>
    <h3>🎮 Game History</h3>
    <?php while($row = $logs->fetch_assoc()): ?>
        <div class="log-card">
            <div>
                <small><?php echo $row['created_at']; ?></small><br>
                <b><?php echo $row['game_name']; ?></b>
            </div>
            <div class="<?php echo $row['action']; ?>">
                <?php echo ($row['action'] == 'bet' ? '-' : '+'); ?> ৳<?php echo $row['amount']; ?>
            </div>
        </div>
    <?php endwhile; ?>
</body>
</html>
