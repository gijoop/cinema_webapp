<?php
$servername = "localhost";
$username = "cinema_app";
$password = "ultra_secure_app_password";
$dbname = "cinema";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->query("SET CHARSET utf8");
    $conn->query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>