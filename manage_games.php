<?php
session_start();
include 'db.php';

// ১. নতুন গেম এপিআই সেভ করা
if (isset($_POST['add_game'])) {
    $name = mysqli_real_escape_string($conn, $_POST['game_name']);
    $key = mysqli_real_escape_string($conn, $_POST['api_key']);
    $rtp = intval($_POST['rtp']);
    
    $conn->query("INSERT INTO game_settings (game_name, api_key, rtp_percent) VALUES ('$name', '$key', '$rtp')");
    echo "<script>alert('গেম এপিআই সফলভাবে সেভ হয়েছে!'); location.href='manage_games.php';</script>";
}

// ২. গেম লিস্ট নিয়ে আসা
$games = $conn->query("SELECT * FROM game_settings ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Game API Control</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background: #0a0f0d; color: white; font-family: sans-serif; padding: 15px; }
        .form-card { background: #073128; border: 1px solid #00ff88; padding: 20px; border-radius: 12px; margin-bottom: 25px; }
        .game-card { background: #111; border: 1px solid #333; padding: 15px; border-radius: 10px; margin-bottom: 10px; }
        input { width: 100%; padding: 12px; margin-bottom: 10px; border-radius: 5px; border: 1px solid #444; background: #000; color: #fff; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background: #00ff88; border: none; font-weight: bold; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <a href="admin_panel.php" style="color: #00ff88; text-decoration: none;">← ড্যাশবোর্ড</a>
    <h2 style="text-align: center;">GAME API CONNECTION</h2>

    <!-- নতুন গেম যোগ করার ফর্ম -->
    <div class="form-card">
        <form method="POST">
            <input type="text" name="game_name" placeholder="গেমের নাম (যেমন: Super Ace)" required>
            <input type="text" name="api_key" placeholder="এপিআই কি (API KEY)" required>
            <input type="number" name="rtp" placeholder="RTP % (যেমন: 85)" required>
            <button type="submit" name="add_game" class="btn">SAVE GAME API</button>
        </form>
    </div>

    <!-- সেভ করা গেমের লিস্ট -->
    <h3>ACTIVE GAMES</h3>
    <?php while($row = $games->fetch_assoc()): ?>
        <div class="game-card">
            <p><strong>গেম:</strong> <?php echo $row['game_name']; ?></p>
            <p><strong>RTP:</strong> <?php echo $row['rtp_percent']; ?>%</p>
            <p style="font-size: 10px; color: #555;">API: <?php echo $row['api_key']; ?></p>
        </div>
    <?php endwhile; ?>
</body>
</html>
