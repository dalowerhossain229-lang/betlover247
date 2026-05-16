<?php
session_start();
include 'header.php';
include 'db.php';

if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
$u = $_SESSION['user_id'];

// 🎰 'bets' টেবিল থেকে প্লেয়ারের এভিয়েটর গেমের ডেটা আনা হচ্ছে
$bet_query = "SELECT * FROM bets WHERE username = '$u' ORDER BY id DESC LIMIT 30";
$bet_res = $conn->query($bet_query);
?>

<div style="padding: 20px; color: white; font-family: sans-serif; min-height: 80vh;">
    <h3 style="color:#00ff88; text-align: center; margin-bottom: 20px; text-transform: uppercase;">🎮 API Game Logs</h3>
    
    <?php if($bet_res && $bet_res->num_rows > 0): while($row = $bet_res->fetch_assoc()): ?>
        <div style="background: #111; border: 1px solid #222; padding: 15px; border-radius: 12px; margin-bottom: 12px;">
            <div style="display:flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:5px;">
                        <span style="background:#333; color:#fff; font-size:9px; padding:2px 6px; border-radius:4px;"><?php echo htmlspecialchars($row['game_id'] ?? 'Aviator'); ?></span>
                        <small style="color:#666; font-size:10px;"><?php echo $row['created_at'] ?? 'Just Now'; ?></small>
                    </div>
                    <span style="font-size:14px; font-weight:bold; color:#fff;">Stake: ৳<?php echo number_format($row['amount'], 2); ?></span>
                </div>
                
                <div style="text-align: right;">
                    <?php 
                    // ছোট হাতের অক্ষরে রূপান্তর করে নিখুঁতভাবে স্ট্যাটাস ম্যাচিং
                    $status = strtolower($row['status'] ?? 'bet');
                    
                    if($status == 'win'): ?>
                        <span style="color:#00ff88; font-weight:bold; font-size:14px;">+ ৳<?php echo number_format($row['amount'], 2); ?></span>
                        <div style="font-size:9px; color:#00ff88; text-transform:uppercase; margin-top:3px;">WIN  </div>
                        
                    <?php elseif($status == 'loss'): ?>
                        <span style="color:#ff4d4d; font-weight:bold; font-size:14px;">- ৳<?php echo number_format($row['amount'], 2); ?></span>
                        <div style="font-size:9px; color:#ff4d4d; text-transform:uppercase; margin-top:3px;">LOSS ❌</div>
                        
                    <?php else: ?>
                        <span style="color:#ff04fd; font-weight:bold; font-size:14px;">৳<?php echo number_format($row['amount'], 2); ?></span>
                        <div style="font-size:9px; color:#ffdf1b; text-transform:uppercase; margin-top:3px;">PENDING ⏳</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endwhile; else: ?>
        <div style="text-align:center; margin-top:100px; color:#555;">
            <p>আপনার কোনো গেম হিস্ট্রি পাওয়া যায়নি।</p>
            <a href="index.php" style="color:#00ff88; text-decoration:none;">হোম পেজে ফিরে যান</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
