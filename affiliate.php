<?php 
session_start();
include 'db.php'; 

// ১. লগইন চেক
if (!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit(); 
}

$u = $_SESSION['user_id'];

// ২. ডাটাবেস থেকে ইউজারের সব তথ্য নিয়ে আসা
$u_res = $conn->query("SELECT * FROM users WHERE username = '$u'");
$u_data = $u_res->fetch_assoc();

// ৩. অ্যাডমিন পারমিশন চেক (is_affiliate ১ না হলে বের করে দিবে)
if ($u_data['is_affiliate'] != 1) {
    include 'header.php';
    echo "<div style='color:white; text-align:center; margin-top:100px; font-family:sans-serif;'>
            <h2 style='color:#ff4d4d;'>❌ ACCESS DENIED</h2>
            <p>এই ফিচারটি শুধুমাত্র অনুমোদিত পার্টনারদের জন্য।</p>
            <a href='profile.php' style='color:#00ff88; text-decoration:none; font-weight:bold;'>প্রোফাইলে ফিরে যান</a>
          </div>";
    exit();
}

// ৪. রেফারেল সংখ্যা গণনা করা
$ref_code = $u_data['ref_code'];
$ref_count_res = $conn->query("SELECT COUNT(*) as total FROM users WHERE ref_by = '$ref_code'");
$ref_count_data = $ref_count_res->fetch_assoc();
$ref_count = $ref_count_data['total'];

include 'header.php'; 
?>

<div style="padding: 20px; color: white; font-family: sans-serif; min-height: 100vh; background: #0a0f0d;">
    <h2 style="color:#00ff88; text-align: center; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 25px;">🤝 AFFILIATE PROGRAM</h2>
    
    <!-- মার্কেটিং লিঙ্ক সেকশন -->
    <div style="background:#111; padding:20px; border-radius:15px; border:1px dashed #00ff88; text-align:center; box-shadow: 0 0 15px rgba(0,255,136,0.1);">
        <p style="color:#888; font-size:12px; margin-bottom:10px;">আপনার মার্কেটিং লিঙ্ক (কপি করুন):</p>
        <input type="text" readonly value="https://onrender.com<?php echo $u_data['ref_code']; ?>" 
               style="width:100%; background:#000; border:1px solid #333; color:#00ff88; padding:15px; border-radius:10px; font-size:11px; text-align:center; outline:none; box-sizing: border-box;">
        <p style="font-size:11px; color:#666; margin-top:15px; line-height:1.6;">প্রতিটি ডিপোজিটে ৫% ইনস্ট্যান্ট কমিশন এবং মাসিক ৪৭% প্রফিট শেয়ার!</p>
    </div>

    <!-- ইনকাম এবং রেফারেল কার্ডস -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 25px;">
        <div style="background:#111; padding:18px; border-radius:15px; border-left:4px solid #00ff88;">
            <small style="color:#888; font-size: 10px; text-transform: uppercase;">Instant (5%)</small>
            <h3 style="color:#00ff88; margin:8px 0; font-size: 20px;">৳ <?php echo number_format($u_data['aff_instant_earned'] ?? 0, 2); ?></h3>
        </div>
        <div style="background:#111; padding:18px; border-radius:15px; border-left:4px solid #ffdf1b;">
            <small style="color:#888; font-size: 10px; text-transform: uppercase;">Total Referrals</small>
            <h3 style="color:#ffdf1b; margin:8px 0; font-size: 20px;"><?php echo $ref_count; ?></h3>
        </div>
    </div>

    <!-- মাসিক প্রফিট শেয়ার (৪৭%) -->
    <div style="background:linear-gradient(135deg, #1a1a1a, #000); padding:25px; border-radius:15px; border:1px solid #ffdf1b; margin-top:20px; text-align:center; box-shadow: 0 4px 20px rgba(255,223,27,0.15);">
        <small style="color:#888; text-transform: uppercase; font-size: 10px; letter-spacing: 1px;">Monthly Profit Share (47%)</small>
        <h2 style="color:#ffdf1b; margin:12px 0; font-size: 32px; text-shadow: 0 0 10px rgba(255,223,27,0.3);">৳ <?php echo number_format($u_data['aff_monthly_ngr'] ?? 0, 2); ?></h2>
        <small style="color:#555; font-size:10px; font-style: italic;">*মাস শেষে এই ব্যালেন্সটি মেইন একাউন্টে যোগ করা হবে।</small>
    </div>
</div>

<?php include 'footer.php'; ?>
