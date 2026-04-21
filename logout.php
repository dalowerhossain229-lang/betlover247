<?php
session_start();
$_SESSION = array(); // সব সেশন ভ্যারিয়েবল খালি করা

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy(); // সেশন ধ্বংস করা
header("Location: index.php"); // মেইন পেজে ফেরত পাঠানো
exit();
?>
