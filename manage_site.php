<?php
session_start();
include 'db.php';

// ১. সেটিংস আপডেট করার লজিক (নোটিশ ও পেমেন্ট নম্বর)
if (isset($_POST['update_site'])) {
    foreach ($_POST['config'] as $key => $value) {
        $key = mysqli_real_escape_string($conn, $key);
        $value = mysqli_real_escape_string($conn, $value);
        $conn->query("INSERT INTO site_configs (config_key, config_value) VALUES ('$key', '$value') ON DUPLICATE KEY UPDATE config_value='$value'");
    }
    echo "<script>alert('সেটিংস সফলভাবে আপডেট হয়েছে!'); location.href='manage_site.php';</script>";
}

// ২. স্লাইডার আপডেট করার লজিক (১০টি স্লাইডার)
if (isset($_POST['update_sliders'])) {
    foreach ($_POST['slide_url'] as $id => $url) {
        $id = intval($id);
        $url = mysqli_real_escape_string($conn, $url);
        $conn->query("UPDATE sliders SET image_url = '$url' WHERE id = $id");
    }
    echo "<script>alert('স্লাইডার আপডেট সফল!'); location.href='manage_site.php';</script>";
}

// বর্তমান ডাটাবেস তথ্য নিয়ে আসা
$configs = $conn->query("SELECT * FROM site_configs");
$site_data = [];
while($row = $configs->fetch_assoc()) { $site_data[$row['config_key']] = $row['config_value']; }

$sliders = $conn->query("SELECT * FROM sliders LIMIT 10");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Site Control | BetLover777</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background: #0a0f0d; color: white; font-family: sans-serif; padding: 15px; }
        .card { background: #073128; border: 1.5px solid #00ff88; padding: 20px; border-radius: 12px; margin-bottom: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.4); }
        h3 { color: #00ff88; border-bottom: 1px solid #1a2a22; padding-bottom: 10px; margin-bottom: 15px; font-size: 16px; }
        label { font-size: 12px; color: #aaa; display: block; margin-bottom: 5px; }
        input, textarea { width: 100%; padding: 12px; margin-bottom: 15px; border-radius: 8px; border: 1px solid #1a2a22; background: #000; color: #fff; box-sizing: border-box; }
        .btn { width: 100%; padding: 15px; background: #00ff88; color: #000; border: none; font-weight: 900; border-radius: 8px; cursor: pointer; text-transform: uppercase; }
        .back-link { color: #00ff88; text-decoration: none; font-size: 14px; margin-bottom: 20px; display: inline-block; }
    </style>
</head>
<body>
    <a href="admin_panel.php" class="back-link">← ড্যাশবোর্ড</a>

    <!-- নোটিশ বোর্ড কন্ট্রোল -->
    <form method="POST" class="card">
        <h3>📢 নোটিশ বোর্ড নিয়ন্ত্রণ</h3>
        <label>আপনার সাইটের স্ক্রলিং নোটিশ লিখুন:</label>
        <textarea name="config[notice_text]" rows="3"><?php echo $site_data['notice_text'] ?? ''; ?></textarea>
        <button type="submit" name="update_site" class="btn">SAVE NOTICE</button>
    </form>

    <!-- স্লাইডার কন্ট্রোল (১০টি স্লাইডার) -->
    <form method="POST" class="card">
        <h3>🖼️ স্লাইডার নিয়ন্ত্রণ (১০টি ব্যানার)</h3>
        <?php if($sliders->num_rows > 0): while($s = $sliders->fetch_assoc()): ?>
            <label>স্লাইডার নম্বর <?php echo $s['id']; ?> (Image File Name):</label>
            <input type="text" name="slide_url[<?php echo $s['id']; ?>]" value="<?php echo $s['image_url']; ?>">
        <?php endwhile; else: ?>
            <p style="font-size: 12px; color: #ff4d4d;">স্লাইডার টেবিল তৈরি করা নেই। আগে SQL রান করুন।</p>
        <?php endif; ?>
        <button type="submit" name="update_sliders" class="btn" style="background: #ffdf1b;">UPDATE ALL SLIDERS</button>
    </form>

</body>
</html>
