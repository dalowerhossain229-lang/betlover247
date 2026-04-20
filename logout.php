<?php
session_start(); // সেশন শুরু করা যাতে এটি বন্ধ করা যায়
session_unset(); // সব সেশন ভেরিয়েবল মুছে ফেলা
session_destroy(); // সেশনটি পুরোপুরি ধ্বংস করা

// লগআউট হওয়ার পর ইউজারকে মেইন পেজে পাঠিয়ে দেওয়া
header("Location: index.php");
exit();
?>

