<?php
// ===================================================================================
// 🎰 BETLOVER777 / DKWIN ডাটাবেজ ওল্ড এভিয়েটর লগ ক্লিনআপ অ্যান্ড রুট বুস্টার বর্ম
// ===================================================================================
ob_start();
session_start();
header("Content-Type: application/json");

// ডাটাবেজ কানেকশন সিঙ্ক ভাই ভাই
include 'db.php';

try {
    // 🔒 [লোহার বর্মে সুরক্ষামূলক ট্রাঙ্কেট মেকানিজম]
    // এটি bets টেবিলের পুরনো সব ভুল ডাটা ক্লিয়ার করে টেবিল এক্কেবারে নতুন করে দেবে ওস্তাদ!
    $clean_action = $conn->query("TRUNCATE TABLE bets");

    if ($clean_action) {
        echo json_encode([
            "success" => true,
            "status" => "CLEANUP_SUCCESSFUL_LOCKED_7400",
            "message" => "🏆 অভিনন্দন ওস্তাদ! ডাটাবেজের পুরনো সব ভুল 'Aviator' লগ ওয়ান-শটে চিরতরে সাফ হয়ে গেছে ভাই ভাই। এখন লবি থেকে যেকোনো গেমের নতুন বাজি খেললে একদম ওরিজিনাল নাম লাইভ শো করবে।"
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "❌ ডাটাবেজ ক্লিয়ারিং হুক ডিনাইড বা ব্যাকএন্ড কানেকশন এরর ভাই ভাই!"
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "⚠️ ক্র্যাশ এরর ট্র্যাপ: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

ob_end_flush();
?>
