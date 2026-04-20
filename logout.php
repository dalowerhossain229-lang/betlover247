<?php
// ১. সেশন শুরু করা
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ২. সব সেশন ডাটা মুছে ফেলা
$_SESSION = array();

// ৩. ব্রাউজারের সেশন কুকি ডিলিট করা
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// ৪. সেশন পুরোপুরি ধ্বংস করা
session_destroy();

// ৫. ক্যাশ ক্লিয়ার করে মেইন পেজে পাঠানো
header("Cache-Control: no-cache, must-revalidate");
header("Location: index.php");
exit();
?>
