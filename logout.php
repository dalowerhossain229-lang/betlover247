<?php
session_start();
session_unset();
session_destroy();
header("Location: index.php"); // লগআউট হওয়ার পর হোম পেজে নিয়ে যাবে
exit();
?>
