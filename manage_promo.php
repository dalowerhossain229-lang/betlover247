<?php
session_start();
include 'db.php';

// ১. ইউজারের প্রোমো আবেদনগুলো নিয়ে আসা
$promo_res = $conn->query("SELECT * FROM site_configs WHERE config_key LIKE 'promo_%' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Promotion Requests | BetLover777</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background: #0a0f0d; color: white; font-family: sans-serif; padding: 15px; }
        .promo-card { background: #073128; border: 1px solid #ffdf1b; padding: 15px; border-radius: 12px; margin-bottom: 15px; }
        .tag { background: #ffdf1b; color: #000; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; }
        .btn-view { display: block; width: 100%; padding: 10px; background: #00ff88; color: #000; text-align: center; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>
    <a href="admin_panel.php" style="color: #00ff88; text-decoration: none;">← ড্যাশবোর্ড</a>
    <h2 style="text-align: center;">PROMOTION REQUESTS</h2>

    <?php if($promo_res && $promo_res->num_rows > 0): while($row = $promo_res->fetch_assoc()): ?>
        <div class="promo-card">
            <span class="tag"><?php echo str_replace('promo_', '', $row['config_key']); ?></span>
            <p style="margin: 10px 0; font-size: 14px;"><?php echo $row['config_value']; ?></p>
            <a href="#" class="btn-view">VIEW DOCUMENTS</a>
        </div>
    <?php endwhile; else: ?>
        <p style="text-align: center; color: #555; margin-top: 50px;">বর্তমানে কোনো প্রোমো আবেদন নেই।</p>
    <?php endif; ?>
</body>
</html>
