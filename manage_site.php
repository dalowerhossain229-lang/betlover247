   <?php
session_start();
include 'db.php';

// ১. ইমেজ আপলোড লজিক (শুধুমাত্র PNG)
if(isset($_POST['upload_slider'])) {
    $count_res = $conn->query("SELECT COUNT(*) as total FROM slider_images");
    $total = $count_res->fetch_assoc()['total'];
    $file_type = strtolower(pathinfo($_FILES["slider_file"]["name"], PATHINFO_EXTENSION));

    if($total >= 10) {
        echo "<script>alert('সর্বোচ্চ ১০টি স্লাইডার যোগ করা যাবে!');</script>";
    } elseif($file_type != "png") {
        echo "<script>alert('শুধুমাত্র PNG ফরম্যাট আপলোড করুন!');</script>";
    } else {
        $target_dir = "images/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $file_name = time() . ".png";
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["slider_file"]["tmp_name"], $target_file)) {
            $conn->query("INSERT INTO slider_images (image_path) VALUES ('$target_file')");
            echo "<script>alert('সফলভাবে যোগ হয়েছে!'); location.href='admin_slider.php';</script>";
        }
    }
}

// ২. ইমেজ ডিলিট লজিক
if(isset($_GET['del'])) {
    $id = $_GET['del'];
    $res = $conn->query("SELECT image_path FROM slider_images WHERE id = $id");
    $img = $res->fetch_assoc();
    if($img && file_exists($img['image_path'])) unlink($img['image_path']);
    $conn->query("DELETE FROM slider_images WHERE id = $id");
    header("Location: admin_slider.php");
}

$sliders = $conn->query("SELECT * FROM slider_images ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body { background: #0a0f0d; color: white; font-family: sans-serif; padding: 20px; }
        .card { background: #111; padding: 15px; border-radius: 12px; border: 1px solid #333; margin-bottom: 15px; }
        .btn { background: #00ff88; color: #000; padding: 10px 20px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; }
        .img-box { display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #222; padding: 10px 0; }
    </style>
</head>
<body>
    <h2>🖼️ SLIDER MANAGER (Max 10)</h2>
    <div class="card">
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="slider_file" accept="image/png" required><br><br>
            <button type="submit" name="upload_slider" class="btn">UPLOAD PNG SLIDE</button>
        </form>
    </div>
    <div class="card">
        <h3>Live Slides:</h3>
        <?php while($row = $sliders->fetch_assoc()): ?>
            <div class="img-box">
                <img src="<?php echo $row['image_path']; ?>" width="100" style="border-radius:5px;">
                <a href="?del=<?php echo $row['id']; ?>" style="color:#ff4d4d; text-decoration:none; font-weight:bold;">Delete</a>
            </div>
        <?php endwhile; ?>
    </div>
 <div class="card" style="margin-top: 20px; border-top: 2px solid #00ff88; padding-top: 20px;">
        <h3>📢 Update Notice Board</h3>
        <form method="POST">
            <textarea name="new_notice" style="width: 100%; height: 80px; background: #000; color: #00ff88; border: 1px solid #333; padding: 10px; border-radius: 8px; outline:none;" placeholder="এখানে নতুন নোটিশ লিখুন..."></textarea><br><br>
            <button type="submit" name="update_notice" class="btn">UPDATE NOTICE</button>
        </form>
    </div>
    <?php
    if(isset($_POST['update_notice'])) {
        $notice = mysqli_real_escape_string($conn, $_POST['new_notice']);
        // ডাটাবেসে নোটিশ আপডেট করা (settings টেবিল অনুযায়ী)
        $conn->query("UPDATE settings SET notice = '$notice' WHERE id = 1");
        echo "<script>alert('নোটিশ সফলভাবে আপডেট হয়েছে!'); location.href='manage_site.php';</script>";
    }?>  
</body>
</html>
         
