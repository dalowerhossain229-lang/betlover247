<?php 
session_start();
include 'db.php'; 

if (!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit(); 
}

$u = $_SESSION['user_id'];

// ইউজারের সব তথ্য আনা
$u_res = $conn->query("SELECT * FROM users WHERE username = '$u'");
$u_data = $u_res->fetch_assoc();

// রেফারেল সংখ্যা গণনা করা
$ref_count_res = $conn->query("SELECT COUNT(*) as total FROM users WHERE ref_by = '{$u_data['ref_code']}'");
$ref_count_data = $ref_count_res->fetch_assoc();
$ref_count = $ref_count_data['total'];

include 'header.php'; 
?>

<div style="padding: 20px; color: white; font-family: sans-serif; min-height: 100vh;">
    <h2 style="color:#00ff88; text-align: center; text-transform: uppercase; letter-spacing: 1px;">🤝 AFFILIATE PROGRAM</h2>
    
    <!-- ১. রেফারেল লিঙ্ক সেকশন -->
    <div style="background:#111; padding:20px; border-radius:15px; border:1px dashed #00ff88; margin-top:20px; text-align:center;">
        <p style="color:#888; font-size:12px; margin-bottom:10px;">আপনার মার্কেটিং লিঙ্ক (কপি করুন):</p>
        <input type="text" readonly value="https://onrender.com<?php echo $u_data['ref_code']; ?>" 
               style="width:100%; background:#000; border:1px solid #333; color:#00ff88; padding:12px; border-radius:8px; font-size:11px; text-align:center; outline:none;">
        <p style="font-size:10px; color:#666; margin-top:12px; line-height:1.5;">প্রতিটি ডিপোজিটে ৫% ইনস্ট্যান্ট কমিশন এবং মাসিক ৪৭% প্রফিট শেয়ার!</p>
    </div>

    <!-- ২. ইনকাম এবং রেফারেল কার্ডস -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 20px;">
        <div style="background:#111; padding:15px; border-radius:12px; border-left:4px solid #00ff88;">
            <small style="color:#888;">Instant (5%)</small>
            <h3 style="color:#00ff88; margin:5px 0;">৳ <?php echo number_format($u_data['aff_instant_earned'] ?? 0, 2); ?></h3>
        </div>
        <div style="background:#111; padding:15px; border-radius:12px; border-left:4px solid #ffdf1b;">
            <small style="color:#888;">Referrals</small>
            <h3 style="color:#ffdf1b; margin:5px 0;"><?php echo $ref_count; ?></h3>
        </div>
    </div>

    <!-- ৩. মাসিক প্রফিট শেয়ার (৪৭%) -->
    <div style="background:linear-gradient(45deg, #1a1a1a, #000); padding:25px; border-radius:15px; border:1px solid #ffdf1b; margin-top:20px; text-align:center; box-shadow: 0 4px 15px rgba(255,223,27,0.1);">
        <small style="color:#888; text-transform: uppercase; font-size: 10px;">Monthly Profit Share (47%)</small>
        <h2 style="color:#ffdf1b; margin:10px 0; font-size: 28px;">৳ <?php echo number_format($u_data['aff_monthly_ngr'] ?? 0, 2); ?></h2>
        <small style="color:#555; font-size:10px;">*মাস শেষে এই ব্যালেন্সটি মেইন একাউন্টে যোগ করা হবে।</small>
    </div>
</div>

<?php include 'footer.php'; ?>
