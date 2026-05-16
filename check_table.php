<?php
session_start();
include 'db.php';

echo '<!DOCTYPE html>
<html>
<head>
    <title>⚙️ Database Structure Checker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background: #0b0f19; color: #f3f4f6; font-family: sans-serif; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: #111827; padding: 25px; border-radius: 12px; border: 1px solid #1f2937; box-shadow: 0 4px 6px rgba(0,0,0,0.3); }
        h2 { color: #10b981; border-bottom: 2px solid #1f2937; padding-bottom: 10px; margin-top: 0; }
        h3 { color: #3b82f6; margin-top: 25px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
        th, td { padding: 12px; text-align: left; border: 1px solid #1f2937; }
        th { background: #1f2937; color: #10b981; }
        tr:nth-child(even) { background: #161e2e; }
        .session-box { background: #1e1b4b; padding: 12px; border-radius: 6px; border: 1px solid #4338ca; color: #c7d2fe; font-family: monospace; }
        .error { color: #ef4444; font-weight: bold; background: #451a03; padding: 10px; border-radius: 6px; border: 1px solid #78350f; }
    </style>
</head>
<body>
<div class="container">
    <h2>⚙️ Database Table & Session Structure Checker</h2>';

// ১. ডাটাবেজ কানেকশন চেক
if (!isset($conn) || $conn->connect_error) {
    echo '<p class="error">❌ Database Connection Failed! Check db.php</p>';
    if (isset($conn)) echo '<p class="error">Error: ' . $conn->connect_error . '</p>';
    echo '</div></body></html>';
    exit;
}

// ২. বর্তমান ব্রাউজার সেশন চেক
echo '<h3>👤 Current Browser Session Data</h3>';
echo '<div class="session-box"><pre>';
if (!empty($_SESSION)) {
    print_r($_SESSION);
} else {
    echo 'No active session found! Please log in to the casino site first in this browser tab.';
}
echo '</pre></div>';

// ৩. users টেবিলের কলাম স্ট্রাকচার চেক
echo '<h3>📊 Columns in `users` Table</h3>';
$result = $conn->query("DESCRIBE users");

if ($result) {
    echo '<table>
            <thead>
                <tr>
                    <th>Column Name (Field)</th>
                    <th>Data Type</th>
                    <th>Null Allowed</th>
                    <th>Key</th>
                    <th>Default</th>
                </tr>
            </thead>
            <tbody>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <td style="font-weight:bold; color:#10b981;">' . htmlspecialchars($row['Field']) . '</td>
                <td>' . htmlspecialchars($row['Type']) . '</td>
                <td>' . htmlspecialchars($row['Null']) . '</td>
                <td style="color:#60a5fa;">' . htmlspecialchars($row['Key']) . '</td>
                <td>' . htmlspecialchars($row['Default'] ?? 'NULL') . '</td>
              </tr>';
    }
    echo '</tbody></table>';
} else {
    echo '<p class="error">❌ Could not describe table `users`. Error: ' . $conn->error . '</p>';
    
    // বিকল্প হিসেবে সব টেবিলের নাম দেখার চেষ্টা
    echo '<h3>📂 List of Available Tables in DB:</h3><ul>';
    $tables = $conn->query("SHOW TABLES");
    if($tables) {
        while($t_row = $tables->fetch_row()) {
            echo '<li>' . htmlspecialchars($t_row[0]) . '</li>';
        }
    }
    echo '</ul>';
}

echo '</div>
</body>
</html>';
?>
