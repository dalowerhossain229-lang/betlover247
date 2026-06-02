<?php
// ===================================================================================
// 🎰 BETLOVER777 / DKWIN ওরিজিনাল গ্লোবাল ইউনিভার্সাল এপিআই কলব্যাক মাস্টার বর্ম
// ===================================================================================
ob_start();
session_start();
header("Access-Control-<?php
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
-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

include 'db.php';

// 🔌 ১. রিয়েল-টাইম মাল্টিপ্লেয়ার গেম বাজি ও উইন প্রসেসর গেটওয়ে (POST)
$json_input = file_get_contents('php://input');
$data = json_decode($json_input, true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "🚨 Invalid JSON API Payload Input Reference!"]);
    exit();
}

$action = isset($data['action']) ? $data['action'] : '';
$username = isset($data['username']) ? mysqli_real_escape_string($conn, $data['username']) : '';
$amount = isset($data['amount']) ? floatval($data['amount']) : 0;
$wallet = isset($data['wallet']) ? mysqli_real_escape_string($conn, $data['wallet']) : 'main';

// 🔌 [API_CALLBACK ওরিজিনাল গেম নেম ফিল্টার বর্ম ভাই ভাই]
$dynamic_game_name = 'Casino Game'; // Default Fallback
if (!empty($data['game'])) {
    $dynamic_game_name = mysqli_real_escape_string($conn, $data['game']);
} else {
    $get_game_type = isset($_GET['game']) ? mysqli_real_escape_string($conn, $_GET['game']) : '';
    if (!empty($get_game_type)) {
        $dynamic_game_name = $get_game_type;
    } else {
        $dynamic_game_name = 'Present-Game'; // অটো-বট লুপের জন্য লাকি ব্যাকআপ লক ওস্তাদ!
    }
}


if (empty($username)) {
    echo json_encode(["status" => "error", "message" => "❌ Empty Player Username Credentials!"]);
    exit();
}

// ইউজার ব্যালেন্স ও ডাটা যাচাই লুপ ভাই ভাই
$user_query = $conn->query("SELECT * FROM users WHERE username = '$username' OR id = '$username'");
$u_data = $user_query->fetch_assoc();

if (!$u_data) {
    echo json_encode(["status" => "error", "message" => "Player Account Profile Not Found inside Main Database Node!"]);
    exit();
}

// 🎛️ ৪. প্লেয়ারের ওয়ালেট অ্যাকাউন্ট লাইভ মেকানিজম (pb, bonus, main এলাইনমেন্ট লক ভাই)
$bal_col = 'balance';
$turn_col = 'main_t';

$wallet = strtolower($wallet);
if ($wallet === 'pb') {
    $bal_col = 'pb_balance';
    $turn_col = 'pb_t';
} elseif ($wallet === 'bonus') {
    $bal_col = 'bonus_balance';
    $turn_col = 'bonus_t';
}

$user_current_balance = floatval($u_data[$bal_col]);

// 🛫 ২. বাজি ধরার রিয়েল-টাইম একশন প্রসেসর (`action == "bet"`)
if ($action == "bet") {
    if ($user_current_balance < $amount || $user_current_balance <= 0) {
        echo json_encode(["status" => "error", "message" => "❌ Insufficient Balance in Selected Wallet!"]);
        exit();
    }

    $update = $conn->query("UPDATE users SET $bal_col = $bal_col - $amount, $turn_col = $turn_col + $amount WHERE username = '{$u_data['username']}'");
    
    if ($update) {
        // 🔒 ডাটাবেজের bets টেবিলে গেমের আসল নাম সহ ওয়ান-শটে বাজি লগ এন্ট্রি মারা ভাই ভাই
        $conn->query("INSERT INTO bets (username, amount, game_id, status) VALUES ('{$u_data['username']}', '$amount', '$dynamic_game_name', 'bet')");
        $new_balance = $user_current_balance - $amount;
        echo json_encode(["status" => "ok", "message" => "Bet Accepted Successfully", "balance" => $new_balance]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Wallet Debit Sync Failed!"]);
    }
} 
// 💰 ৩. বাজি জেতা বা হারার রিয়েল-টাইম সেটেলমেন্ট একশন প্রসেসর (`action == "win"`)
else if ($action == "win") {
    $update = $conn->query("UPDATE users SET $bal_col = $bal_col + $amount, $turn_col = $turn_col + $amount WHERE username = '{$u_data['username']}'");
    
    if ($update) {
        // 🔒 [মাস্টারস্ট্রোক ফিক্সড বর্ম]: বাজি ধরার মূল amount কলাম এক চুলও নড়চড় না করে—শুধুমাত্র status কলামটি 'win' এ আপডেট করার চাবি ভাই ভাই
        $conn->query("UPDATE bets SET status = 'win' WHERE username = '{$u_data['username']}' ORDER BY id DESC LIMIT 1");
        
        $fresh_user_query = $conn->query("SELECT * FROM users WHERE username = '{$u_data['username']}'");
        $fresh_user = $fresh_user_query->fetch_assoc();
        echo json_encode(["status" => "ok", "message" => "Win Settled Successfully", "balance" => floatval($fresh_user[$bal_col])]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Wallet Credit Sync Failed!"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "🚨 Bypassed B2B Callback Action Endpoint Command Route!"]);
}
ob_end_flush();
?>
