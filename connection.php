<?php


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nsti_feedback_db";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Enable exceptions for mysqli

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8mb4"); // Set charset (recommended)
} catch (mysqli_sql_exception $e) {
    // Handle error gracefully
    error_log("Connection error: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}
?>
