<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

echo '<!DOCTYPE html>
<html>
<head>
    <title>👁️ Live Database Advanced Inspector</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background: #090d16; color: #e5e7eb; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; padding: 20px; margin: 0; }
        .wrapper { max-width: 1100px; margin: 30px auto; background: #111827; padding: 30px; border-radius: 16px; border: 1px solid #1f2937; box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
        .header-area { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #1f2937; padding-bottom: 15px; margin-bottom: 25px; }
        h2 { color: #10b981; margin: 0; font-size: 24px; display: flex; align-items: center; gap: 10px; }
        h3 { color: #3b82f6; margin-top: 30px; margin-bottom: 15px; border-left: 4px solid #3b82f6; padding-left: 10px; font-size: 18px; }
        .refresh-btn { background: #10b981; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-size: 14px; cursor: pointer; text-decoration: none; font-weight: bold; transition: 0.2s; }
        .refresh-btn:hover { background: #059669; }
        .table-responsive { width: 100%; overflow-x: auto; margin-top: 10px; border-radius: 8px; border: 1px solid #1f2937; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; background: #161e2e; text-align: left; }
        th, td { padding: 12px 15px; border-bottom: 1px solid #1f2937; border-right: 1px solid #1f2937; }
        th { background: #1f2937; color: #10b981; font-weight: 600; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; }
        tr:last-child td { border-bottom: none; }
        tr:hover { background: #1f2937; }
        .badge { font-weight: bold; padding: 3px 8px; border-radius: 4px; font-size: 11px; display: inline-block; }
        .badge-win { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.4); }
        .badge-loss { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.4); }
        .badge-bet { background: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.4); }
        .alert-box { color: #ef4444; font-weight: bold; background: #2d1515; padding: 15px; border-radius: 8px; border: 1px solid #7f1d1d; margin-bottom: 20px; font-family: monospace; }
        .no-data { text-align: center; color: #6b7280; padding: 40px; font-style: italic; font-size: 14px; background: #161e2e; }
        .wallet-highlight { font-weight: bold; color: #f59e0b; background: rgba(245, 158, 11, 0.1); padding: 2px 6px; border-radius: 4px; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header-area">
        <h2>👁️ Live Database Advanced Inspector</h2>
        <a href="" class="refresh-btn">🔄 Refresh Dashboard</a>
    </div>';

// ১. ডাটাবেজ কানেকশন চেক
if (!isset($conn) || $conn->connect_error) {
    echo '<div class="alert-box">❌ CRITICAL ERROR: Database connection failed! Please check your db.php file.<br>';
    if (isset($conn)) echo 'Error Details: ' . htmlspecialchars($conn->connect_error);
    echo '</div></div></body></html>';
    exit;
}

// ২. USERS টেবিলের লাইভ ডাটা ও একটিভ ওয়ালেট মনিটর (আমার চাওয়া স্ক্রিনশট ১ এর বিকল্প)
echo '<h3>👥 Section 1: Live Players Wallet Status (`users` table data)</h3>';
$users_query = $conn->query("SELECT id, username, balance, pb_balance, bonus_balance, active_wallet FROM users ORDER BY id DESC LIMIT 5");

if ($users_query) {
    echo '<div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Main Balance</th>
                        <th>PB (Promo Balance)</th>
                        <th>Bonus Balance</th>
                        <th>Active Wallet</th>
                    </tr>
                </thead>
                <tbody>';
    if ($users_query->num_rows > 0) {
        while ($u_row = $users_query->fetch_assoc()) {
            echo '<tr>
                    <td>' . htmlspecialchars($u_row['id']) . '</td>
                    <td style="font-weight:bold; color:#f3f4f6;">' . htmlspecialchars($u_row['username']) . '</td>
                    <td style="color:#34d399; font-weight:bold;">৳' . number_format($u_row['balance'], 2) . '</td>
                    <td style="color:#60a5fa; font-weight:bold;">৳' . number_format($u_row['pb_balance'], 2) . '</td>
                    <td style="color:#a78bfa; font-weight:bold;">৳' . number_format($u_row['bonus_balance'], 2) . '</td>
                    <td><span class="wallet-highlight">' . strtoupper(htmlspecialchars($u_row['active_wallet'] ?? 'MAIN')) . '</span></td>
                  </tr>';
        }
    } else {
        echo '<tr><td colspan="6" class="no-data">No registered users found in the database.</td></tr>';
    }
    echo '</tbody></table></div>';
} else {
    echo '<div class="alert-box">❌ Failed to query `users` table. Error: ' . htmlspecialchars($conn->error) . '</div>';
}

// ৩. BETS টেবিলের আসল কলাম স্ট্রাকচার (আমার চাওয়া স্ক্রিনশট ২ এর বিকল্প)
echo '<h3>📊 Section 2: Structure & Columns of `bets` Table</h3>';
$bets_structure = $conn->query("DESCRIBE bets");

if ($bets_structure) {
    echo '<div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Column Name (Field)</th>
                        <th>Data Type</th>
                        <th>Null Allowed</th>
                        <th>Key</th>
                        <th>Default Value</th>
                    </tr>
                </thead>
                <tbody>';
    while ($b_struct = $bets_structure->fetch_assoc()) {
        echo '<tr>
                <td style="font-weight:bold; color:#10b981;">' . htmlspecialchars($b_struct['Field']) . '</td>
                <td>' . htmlspecialchars($b_struct['Type']) . '</td>
                <td>' . htmlspecialchars($b_struct['Null']) . '</td>
                <td style="color:#60a5fa; font-weight:bold;">' . htmlspecialchars($b_struct['Key']) . '</td>
                <td>' . htmlspecialchars($b_struct['Default'] ?? 'NULL') . '</td>
              </tr>';
    }
    echo '</tbody></table></div>';
} else {
    echo '<div class="alert-box">❌ CRITICAL: Could not describe table `bets`. Table might be missing or named differently. Error: ' . htmlspecialchars($conn->error) . '</div>';
}

// ৪. BETS টেবিলের সর্বশেষ লাইভ বাজি রেকর্ড মনিটর (আমার চাওয়া স্ক্রিনশট ৩ এর বিকল্প)
echo '<h3>🎰 Section 3: Last 10 Live Aviator Betting Records (`bets` table rows)</h3>';
$bets_data = $conn->query("SELECT * FROM bets ORDER BY id DESC LIMIT 10");

if ($bets_data) {
    echo '<div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Bet ID</th>
                        <th>Player Username</th>
                        <th>Game ID (Name)</th>
                        <th>Amount (Stake/Win)</th>
                        <th>Status</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>';
    if ($bets_data->num_rows > 0) {
        while ($b_row = $bets_data->fetch_assoc()) {
            $status = strtolower($b_row['status'] ?? 'bet');
            if ($status == 'win') {
                $badge = '<span class="badge badge-win">WIN ✅</span>';
                $amt_color = '#34d399';
            } elseif ($status == 'loss') {
                $badge = '<span class="badge badge-loss">LOSS ❌</span>';
                $amt_color = '#f87171';
            } else {
                $badge = '<span class="badge badge-bet">BET ⏳</span>';
                $amt_color = '#60a5fa';
            }

            echo '<tr>
                    <td>' . htmlspecialchars($b_row['id']) . '</td>
                    <td style="font-weight:bold; color:#e5e7eb;">' . htmlspecialchars($b_row['username']) . '</td>
                    <td>' . htmlspecialchars($b_row['game_id'] ?? 'Aviator') . '</td>
                    <td style="font-weight:bold; color:' . $amt_color . ';">৳' . number_format($b_row['amount'], 2) . '</td>
                    <td>' . $badge . '</td>
                    <td style="color:#9ca3af; font-size:12px;">' . htmlspecialchars($b_row['created_at'] ?? 'Just Now') . '</td>
                  </tr>';
        }
    } else {
        echo '<tr><td colspan="6" class="no-data">ℹ️ No records found in the `bets` table. Place a bet in Aviator and refresh!</td></tr>';
    }
    echo '</tbody></table></div>';
} else {
    echo '<div class="alert-box">❌ Failed to query `bets` table rows. Error: ' . htmlspecialchars($conn->error) . '</div>';
}

echo '</div>
</body>
</html>';
?>
