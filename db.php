<?php
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$db   = getenv('DB_NAME');
$port = getenv('DB_PORT') ?: 15764;

$conn = mysqli_init();
if (!$conn->real_connect($host, $user, $pass, $db, $port, NULL, MYSQLI_CLIENT_SSL)) {
    die("Database Connection Failed!");
}
$conn->set_charset("utf8mb4");
$check_t = $conn->query("SHOW COLUMNS FROM users LIKE 'turnover_completed'");
if ($check_t->num_rows == 0) {
    $conn->query("ALTER TABLE users ADD turnover_target INT DEFAULT 1000");
    $conn->query("ALTER TABLE users ADD turnover_completed INT DEFAULT 0");
    $conn->query("ALTER TABLE users ADD p_bkash VARCHAR(20) DEFAULT NULL");
    $conn->query("ALTER TABLE users ADD p_nagad VARCHAR(20) DEFAULT NULL");
}
?>
