<?php
session_start();
include 'db.php';

// ১. টার্নওভার আপডেট লজিক
if (isset($_POST['update_turnover'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $m_done = intval($_POST['m_done']);
    $b_done = intval($_POST['b_done']);
    $p_done = intval($_POST['p_done']);

    $sql = "UPDATE users SET 
            turnover_completed = $m_done, 
            bonus_t_done = $b_done, 
            pb_t_done = $p_done 
            WHERE username = '$username'";

    if ($conn->query($sql)) {
        echo "<script>alert('টার্নওভার সফলভাবে আপডেট হয়েছে!');</script>";
    }
}

// ২. সব ইউজারদের লিস্ট নিয়ে আসা
$users = $conn->query("SELECT username, turnover_target, turnover_completed, bonus_t_target, bonus_t_done, pb_t_target, pb_t_done FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body { background: #0a0f0d; color: white; font-family: sans-serif; padding: 15px; }
        .t-card { background: #111; border: 1px solid #333; padding: 15px; border-radius: 12px; margin-bottom: 20px; }
        .t-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 10px; }
        input { width: 100%; padding: 10px; background: #000; border: 1px solid #444; color: #fff; border-radius: 5px; box-sizing: border-box; }
        label { font-size: 11px; color: #888; }
        .btn-up { background: #00ff88; color: #000; border: none; padding: 12px; width: 100%; border-radius: 8px; font-weight: bold; cursor: pointer; margin-top: 15px; }
    </style>
</head>
<body>
    <h2 style="color: #00ff88; text-align: center;">MANAGE TURNOVER</h2>
    <p style="text-align:center;"><a href="admin_panel.php" style="color:#888; text-decoration:none;">← ব্যাক টু ড্যাশবোর্ড</a></p>

    <?php while($u = $users->fetch_assoc()): ?>
        <div class="t-card">
            <h4 style="margin:0; color:#ffdf1b;">User: <?php echo $u['username']; ?></h4>
            <form method="POST">
                <input type="hidden" name="username" value="<?php echo $u['username']; ?>">
                
                <div class="t-grid">
                    <div>
                        <label>Main Done (Target: <?php echo $u['turnover_target']; ?>)</label>
                        <input type="number" name="m_done" value="<?php echo $u['turnover_completed']; ?>">
                    </div>
                    <div>
                        <label>Bonus Done (Target: <?php echo $u['bonus_t_target']; ?>)</label>
                        <input type="number" name="b_done" value="<?php echo $u['bonus_t_done']; ?>">
                    </div>
                    <div style="grid-column: span 2;">
                        <label>PB Done (Target: <?php echo $u['pb_t_target']; ?>)</label>
                        <input type="number" name="p_done" value="<?php echo $u['pb_t_done']; ?>">
                    </div>
                </div>
                
                <button type="submit" name="update_turnover" class="btn-up">UPDATE PROGRESS</button>
            </form>
        </div>
    <?php endwhile; ?>
</body>
</html>
