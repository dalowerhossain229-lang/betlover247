<?php
include 'db.php';

// ১. নম্বর আপডেট করার লজিক
if(isset($_POST['update_wa'])){
    $new_no = $_POST['wa_no'];
    $conn->query("UPDATE settings SET whatsapp_no = '$new_no' WHERE id = 1");
    echo "<script>alert('হোয়াটসঅ্যাপ নম্বর সফলভাবে আপডেট হয়েছে!'); window.location.href='manage_whatsapp.php';</script>";
}

// ২. বর্তমান নম্বরটি ডাটাবেস থেকে আনা
$res = $conn->query("SELECT whatsapp_no FROM settings WHERE id = 1");
$data = $res->fetch_assoc();

echo "<body style='background:#000; color:#fff; font-family:sans-serif; text-align:center; padding:50px;'>";
echo "<h2 style='color:#25D366;'>📱 WhatsApp Number Control</h2>";
echo "<div style='background:#111; padding:30px; border-radius:15px; border:1px solid #333; display:inline-block;'>";
echo "<form method='POST'>";
echo "<p style='color:#888;'>বর্তমান নম্বর: <b style='color:#fff;'>".$data['whatsapp_no']."</b></p>";
echo "<input type='text' name='wa_no' value='".$data['whatsapp_no']."' placeholder='যেমন: 8801700000000' style='width:250px; padding:10px; border-radius:5px; border:1px solid #444; background:#222; color:#fff; text-align:center;'>";
echo "<br><br><button type='submit' name='update_wa' style='background:#25D366; color:#000; padding:10px 25px; border:none; border-radius:5px; font-weight:bold; cursor:pointer;'>UPDATE NOW</button>";
echo "</form></div>";
echo "<br><br><a href='admin_panel.php' style='color:#888; text-decoration:none;'>← ব্যাকে যান</a>";
echo "</body>";
?>
