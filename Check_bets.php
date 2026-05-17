<?php
session_start();
include 'db.php';

echo '<!DOCTYPE html>
<html>
<head>
    <title>🎰 Bets Table Checker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background: #0b0f19; color: #f3f4f6; font-family: sans-serif; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; background: #111827; padding: 25px; border-radius: 12px; border: 1px solid #1f2937; box-shadow: 0 4px 6px rgba(0,0,0,0.3); }
        h2 { color: #10b981; border-bottom: 2px solid #1f2937; padding-bottom: 10px; margin-top: 0; display: flex; justify-content: space-between; align-items: center; }
        .refresh-btn { background: #3b82f6; color: #fff; border: none; padding: 8px 15px; border-radius: 6px; font-size: 14px; cursor: pointer; text-decoration: none; font-weight: bold; }
        .refresh-btn:hover { background: #2563eb; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
        th, td { padding: 12px; text-align: left; border: 1px solid #1f2937; }
        th { background: #1f2937; color: #10b981; }
        tr:nth-child(even) { background: #161e2e; }
        .badge { font-weight: bold; padding: 4px 8px; border-radius: 4px; font-size: 12px; text-transform: uppercase; }
        .badge-bet { background: rgba(59, 130, 246, 0.15); color: #60a5fa; }
        .badge-win { background: rgba(16, 185, 129, 0.15); color: #34d399; }
        .badge-loss { background: rgba(239, 68, 68, 0.15); color: #f87171; }
        .error { color: #ef4444; font-weight: bold; background: #451a03; padding: 10px; border-radius: 6px; border: 1px solid #78350f; text-align: center; }
        .no-data { text-align: center; color: #9ca3af; padding: 30px; font-style: italic; }
    </style>
</head>
<body>
<div class="container">
    <h2>
        <span>🎰 Live Bets Table Monitor</span>
        <a href="" class="refresh-btn">🔄 Refresh Data</a>
    </h2>';

// ১. ডাটাবেজ কানেকশন চেক
if (!isset($conn) || $conn->connect_error) {
    echo '<p class="error">❌ Database Connection Failed! Check db.php</p>';
    if (isset($conn)) echo '<p class="error">Error: ' . $conn->connect_error . '</p>';
    echo '</div></body></html>';
    exit;
}

// ২. bets টেবিল থেকে লেটেস্ট ২০টি ডাটা তুলে আনা
$query = "SELECT * FROM bets ORDER BY id DESC LIMIT 20";
$result = $conn->query($query);

if ($result) {
    if ($result->num_rows > 0) {
        echo '<table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username (Player)</th>
                        <th>Game ID</th>
                        <th>Amount (Stake/Win)</th>
                        <th>Status</th>
                        <th>Time (Created At)</th>
                    </tr>
                </thead>
                <tbody>';
        while ($row = $result->fetch_assoc()) {
            // স্ট্যাটাস অনুযায়ী ব্যাজ ডিজাইন
            $status = strtolower($row['status'] ?? 'bet');
            if ($status == 'win') {
                $badge = '<span class="badge badge-win">WIN ✅</span>';
                $amt_color = '#34d399';
            } elseif ($status == 'loss') {
                $badge = '<span class="badge badge-loss">LOSS ❌</span>';
                $amt_color = '#f87171';
            } else {
                $badge = '<span class="badge badge-bet">BETTING ⏳</span>';
                $amt_color = '#60a5fa';
            }

            echo '<tr>
                    <td>' . $row['id'] . '</td>
                    <td style="font-weight:bold; color:#e5e7eb;">' . htmlspecialchars($row['username']) . '</td>
                    <td>' . htmlspecialchars($row['game_id'] ?? 'Aviator') . '</td>
                    <td style="font-weight:bold; color:' . $amt_color . ';">৳' . number_format($row['amount'], 2) . '</td>
                    <td>' . $badge . '</td>
                    <td style="color:#9ca3af; font-size:12px;">' . ($row['created_at'] ?? 'Just Now') . '</td>
                  </tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<div class="no-data">ℹ️ No betting logs found in the `bets` table yet. Go to the game and place a bet!</div>';
    }
} else {
    echo '<p class="error">❌ Could not read data from `bets` table. Error: ' . $conn->error . '</p>';
    
    // সেফটি চেক: টেবিলটি আসলে ডাটাবেজে আছে কি না তা নিশ্চিত করা
    $check_table = $conn->query("SHOW TABLES LIKE 'bets'");
    if ($check_table && $check_table->num_rows == 0) {
        echo '<p class="error" style="background:#7f1d1d; border-color:#b91c1c;">⚠️ CRITICAL: The table named `bets` does not exist in your database!</p>';
    }
}

echo '</div>
</body>
</html>';
?>
