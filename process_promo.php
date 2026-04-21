<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_SESSION['user_id'] ?? 'Guest';
    $type = $_POST['type'] ?? 'Unknown';
    $details = mysqli_real_escape_string($conn, $_POST['details'] ?? '');
    
    // ডাটাবেসে সেভ করার জন্য ইউনিক কি (Key) তৈরি
    $config_key = "promo_" . $type . "_" . time();
    $config_value = "ইউজার: $user | তথ্য: $details";

    $sql = "INSERT INTO site_configs (config_key, config_value) VALUES ('$config_key', '$config_value')";
    
    if ($conn->query($sql)) {
        echo json_encode(["status" => "success", "message" => "আবেদন সফলভাবে জমা হয়েছে!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "সার্ভার এরর!"]);
    }
}
?>
``` [৩]

### ২. `promo.php` এর জাভাস্ক্রিপ্ট আপডেট করুন
আপনার `promo.php` ফাইলের নিচের দিকে থাকা **`submitPromoForm`** ফাংশনটি বদলে এই কোডটুকু দিন যাতে এটি সরাসরি ডাটাবেসে ডাটা পাঠায়: [৪]

```javascript
function submitPromoForm(type) {
    // এখানে সব ইনপুট থেকে ডাটা সংগ্রহ করার কোড (সংক্ষেপে)
    let details = "আবেদন টাইপ: " + type; 
    
    let fd = new FormData();
    fd.append('type', type);
    fd.append('details', details);

    fetch('process_promo.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.status === 'success') closePromoForm();
    })
    .catch(() => alert("কানেকশন এরর!"));
}
