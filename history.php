<?php 
session_start();
include 'header.php'; 
include 'db.php'; 

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
$u = $_SESSION['user_id'];

// ডিপোজিট হিস্ট্রি নিয়ে আসা
$dep_res = $conn->query("SELECT amount, method, status, trx_id as info, date FROM deposits WHERE username = '$u' ORDER BY id DESC LIMIT 20");
// উইথড্র হিস্ট্রি নিয়ে আসা
$wd_res = $conn->query("SELECT amount, method, status, method as info, date FROM withdraws WHERE username = '$u' ORDER BY id DESC LIMIT 20");
?>

<div style="padding: 20px; color: white; font-family: sans-serif;">
    <h2 style="color:#00ff88; text-align: center; margin-bottom: 25px;">TRANSACTION HISTORY</h2>

    <div style="display: flex; gap: 10px; margin-bottom: 20px;">
        <button onclick="showTab('dep')" id="btn-dep" style="flex:1; padding:12px; background:#00ff88; border:none; border-radius:8px; font-weight:bold; cursor:pointer;">Deposits</button>
        <button onclick="showTab('wd')" id="btn-wd" style="flex:1; padding:12px; background:#111; color:#888; border:1px solid #333; border-radius:8px; font-weight:bold; cursor:pointer;">Withdrawals</button>
    </div>

    <!-- ডিপোজিট লিস্ট -->
    <div id="dep-list">
        <?php if($dep_res->num_rows > 0): while($row = $dep_res->fetch_assoc()): ?>
            <div style="background:#111; padding:15px; border-radius:12px; border:1px solid #222; margin-bottom:12px;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span style="color:#ffdf1b; font-weight:bold;">৳ <?php echo number_format($row['amount'], 2); ?></span>
                    <span style="font-size:10px; padding:2px 8px; border-radius:4px; text-transform:uppercase; background:<?php echo ($row['status']=='approved') ? '#00ff88' : (($row['status']=='pending') ? '#ffdf1b' : '#ff4d4d'); ?>; color:#000;">
                        <?php echo $row['status']; ?>
                    </span>
                </div>
                <p style="font-size:11px; color:#666; margin:8px 0 0 0;">ID: <?php echo $row['info']; ?> | <?php echo $row['date']; ?></p>
            </div>
        <?php endwhile; else: echo "<p style='text-align:center; color:#555;'>কোনো ডিপোজিট রেকর্ড নেই।</p>"; endif; ?>
    </div>

    <!-- উইথড্র লিস্ট (শুরুতে লুকানো থাকবে) -->
    <div id="wd-list" style="display:none;">
        <?php if($wd_res->num_rows > 0): while($row = $wd_res->fetch_assoc()): ?>
            <div style="background:#111; padding:15px; border-radius:12px; border:1px solid #222; margin-bottom:12px;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span style="color:#ff4d4d; font-weight:bold;">- ৳ <?php echo number_format($row['amount'], 2); ?></span>
                    <span style="font-size:10px; padding:2px 8px; border-radius:4px; text-transform:uppercase; background:<?php echo ($row['status']=='approved') ? '#00ff88' : (($row['status']=='pending') ? '#ffdf1b' : '#ff4d4d'); ?>; color:#000;">
                        <?php echo $row['status']; ?>
                    </span>
                </div>
                <p style="font-size:11px; color:#666; margin:8px 0 0 0;"><?php echo $row['info']; ?> | <?php echo $row['date']; ?></p>
            </div>
        <?php endwhile; else: echo "<p style='text-align:center; color:#555;'>কোনো উইথড্র রেকর্ড নেই।</p>"; endif; ?>
    </div>
</div>

<script>
function showTab(type) {
    const depList = document.getElementById('dep-list');
    const wdList = document.getElementById('wd-list');
    const btnDep = document.getElementById('btn-dep');
    const btnWd = document.getElementById('btn-wd');

    if(type === 'dep') {
        depList.style.display = 'block';
        wdList.style.display = 'none';
        btnDep.style.background = '#00ff88'; btnDep.style.color = '#000';
        btnWd.style.background = '#111'; btnWd.style.color = '#888'; btnWd.style.border = '1px solid #333';
    } else {
        depList.style.display = 'none';
        wdList.style.display = 'block';
        btnWd.style.background = '#00ff88'; btnWd.style.color = '#000';
        btnDep.style.background = '#111'; btnDep.style.color = '#888'; btnDep.style.border = '1px solid #333';
    }
}
</script>
<?php include 'footer.php'; ?>
