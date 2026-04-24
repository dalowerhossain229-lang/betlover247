<?php
include 'db.php';

echo "<div style='font-family:sans-serif; background:#000; color:#fff; padding:20px; min-height:100vh;'>";
echo "<h2 style='color:#00ff88;'>🔍 স্লাইডার ডিবাগ রিপোর্ট</h2><hr>";

$get_slides = $conn->query("SELECT * FROM slider_images LIMIT 10");

if ($get_slides && $get_slides->num_rows > 0) {
    echo "<b>মোট স্লাইডার পাওয়া গেছে: " . $get_slides->num_rows . " টি</b><br><br>";
    
    while ($s = $get_slides->fetch_assoc()) {
        $path = $s['image_path'];
        echo "<div style='background:#111; padding:10px; border:1px solid #333; margin-bottom:10px;'>";
        echo "🔹 ডাটাবেসে ইমেজের নাম: <span style='color:#ffdf1b;'>$path</span><br>";
        
        // ফাইলটি সার্ভারে আছে কি না চেক করা
        if (file_exists($path)) {
            echo "✅ স্ট্যাটাস: <span style='color:green;'>ফাইলটি সার্ভারে আছে।</span><br>";
            echo "🖼️ প্রিভিউ: <br><img src='$path' width='150' style='margin-top:10px; border-radius:5px;'>";
        } else {
            echo "❌ স্ট্যাটাস: <span style='color:red;'>ফাইলটি সার্ভারে নেই! (এটিই সমস্যা)</span><br>";
            echo "💡 পরামর্শ: গিটহাবে এই নামের ছবিটি আপলোড করুন অথবা রেন্ডারের ডিস্ক চেক করুন।";
        }
        echo "</div>";
    }
} else {
    echo "<h3 style='color:red;'>❌ ডাটাবেসে কোনো স্লাইডার ইমেজ খুঁজে পাওয়া যায়নি!</h3>";
    echo "অ্যাডমিন প্যানেল থেকে আগে ১টি ছবি আপলোড করুন।";
}

echo "</div>";
?>
