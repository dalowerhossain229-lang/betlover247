<?php
include 'db.php';

// ১৫টি পয়েন্টের জন্য প্রয়োজনীয় সব টেবিল তৈরির মাস্টার কুয়েরি
$sql = "
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    balance DECIMAL(10,2) DEFAULT 0.00,
    bonus_balance DECIMAL(10,2) DEFAULT 0.00,
    turnover_done DECIMAL(10,2) DEFAULT 0.00,
    turnover_target DECIMAL(10,2) DEFAULT 1000.00,
    status ENUM('active', 'banned') DEFAULT 'active',
    last_ip VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE deposits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    amount DECIMAL(10,2),
    trx_id VARCHAR(100) UNIQUE,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE withdraws (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    amount DECIMAL(10,2),
    number VARCHAR(20),
    method VARCHAR(20),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'sub_admin', 'staff', 'agent', 'affiliate') NOT NULL,
    status ENUM('active', 'banned') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE game_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game_name VARCHAR(100),
    api_key TEXT,
    api_url TEXT,
    rtp_percent INT DEFAULT 85
);

CREATE TABLE site_configs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    config_key VARCHAR(50) UNIQUE,
    config_value TEXT
);
";

// কুয়েরি রান করা
if ($conn->multi_query($sql)) {
    echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>";
    echo "<h1 style='color:green;'>✅ অভিনন্দন! সব টেবিল সফলভাবে তৈরি হয়েছে।</h1>";
    echo "<p>আপনার ডাটাবেস এখন এডমিন প্যানেলের জন্য ১০০% রেডি।</p>";
    echo "</div>";
} else {
    echo "<h1 style='color:red;'>❌ এরর: </h1>" . $conn->error;
}
?>
