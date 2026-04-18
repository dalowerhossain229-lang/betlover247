<?php
session_start();
session_unset();
session_destroy();
// ব্রাউজারের ক্যাশ মেমোরি ক্লিয়ার করার জন্য
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Location: index.php");
exit();
?>
