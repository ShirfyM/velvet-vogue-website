<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'velvet_vogue_db';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    throw new Exception("Database connection error.");

}
?>