<?php
session_start();
include "header.php";
include "db.php";

// ১. সেশন চেক এবং মেম্বার লগইন সিকিউরিটি ভ্যালিডেশন
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$u_id = $_SESSION['user_id'];

// ২. মেম্বারের ইউজারনেম তুলে এনে 'bets' টেবিল থেকে হিস্টোরি রিড করা
$bet_query = "SELECT * FROM bets WHERE username = (SELECT username FROM users WHERE id = '$u_id') ORDER BY id DESC LIMIT 30";
$bet_res = $conn->query($bet_query);
?>

<div style="padding: 20px; color: white; font-family: sans-serif; min-height: 80vh;">
    <h3 style="color: #00FF88; text-align: center; margin-bottom: 20px; text-transform: uppercase;">🎰 API Game Logs</h3>

    <?php 
    if ($bet_res && $bet_res->num_rows > 0): 
        while ($row = $bet_res->fetch_assoc()): 
    ?>
        <div style="background: #111; border: 1px solid #222; padding: 15px; border-radius: 12px; margin-bottom: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                
                <!-- গেমের নাম প্রপারলি ডিসপ্লে করা -->
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 5px;">
                    <span style="background: #333; color: #fff; font-size: 11px; padding: 4px 8px; border-radius: 4px; text-transform: capitalize; font-weight: bold;">
                        <?php 
                        $game_title = !empty($row['game_id']) ? $row['game_id'] : 'Casino Game';
                        echo htmlspecialchars($game_title); 
                        ?>
                    </span>
                </div>
                
                <!-- তারিখ ও সময় ডিসপ্লে -->
                <div>
                    <small style="color: #666; font-size: 11px;">
                        📅 <?php echo !empty($row['created_at']) ? $row['created_at'] : (!empty($row['date']) ? $row['date'] : 'Just Now'); ?>
                    </small>
                </div>
                
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                <div>
                    <span style="font-size: 14px; color: #aaa;">Stake: <b style="color: #fff;">৳<?php echo number_format($row['amount'], 2); ?></b></span>
                </div>

                <!-- টাকা কাটা এবং যোগ হওয়ার সঠিক ডাইনামিক স্ট্যাটাস -->
                <div style="text-align: right;">
                    <?php
                    $status = strtolower(isset($row['status']) ? $row['status'] : 'pending');
                    $stake_amount = floatval($row['amount']);
                    $win_amount = isset($row['win_amount']) ? floatval($row['win_amount']) : 0;

                    if ($status === 'win') {
                        // যদি জিতে যায়, তবে ব্যালেন্স যোগ (উইন অ্যামাউন্ট) দেখাবে
                        $final_win = ($win_amount > 0) ? $win_amount : $stake_amount;
                        echo '<span style="color: #00FF88; font-weight: bold; font-size: 15px;">+ ৳' . number_format($final_win, 2) . '</span>';
                        echo '<div style="font-size: 9px; color: #00FF88; text-transform: uppercase; font-weight: bold; margin-top: 2px;">WIN</div>';
                    } elseif ($status === 'pending') {
                        // পেন্ডিং থাকলে অ্যামাউন্ট সাধারণ দেখাবে
                        echo '<span style="color: #ffaa00; font-weight: bold; font-size: 15px;">৳' . number_format($stake_amount, 2) . '</span>';
                        echo '<div style="font-size: 9px; color: #ffaa00; text-transform: uppercase; font-weight: bold; margin-top: 2px;">PENDING</div>';
                    } else {
                        // লস হলে ব্যালেন্স কাটা (মাইনাস অ্যামাউন্ট) দেখাবে
                        echo '<span style="color: #ff4444; font-weight: bold; font-size: 15px;">- ৳' . number_format($stake_amount, 2) . '</span>';
                        echo '<div style="font-size: 9px; color: #ff4444; text-transform: uppercase; font-weight: bold; margin-top: 2px;">LOST</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php 
        endwhile; 
    else: 
    ?>
        <!-- কোনো ডাটা না থাকলে এই মেসেজটি দেখাবে -->
        <div style="text-align: center; margin-top: 100px; color: #555;">
            <p>আপনার কোনো গেম হিস্টোরি পাওয়া যায়নি।</p>
            <a href="index.php" style="color: #00FF88; text-decoration: none; font-size: 14px;">↩️ গেম খেলতে হোমে ফিরে যান</a>
        </div>
    <?php 
    endif; 
    ?>
</div>

<?php include "footer.php"; ?>
