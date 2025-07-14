<?php
// Include your database connection file - make sure the path is correct
require_once('connection.php'); // or whatever your connection file is named

// Check if connection was successful
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Set headers for download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=feedback_export_' . date('Y-m-d') . '.csv');

// Create output stream
$output = fopen('php://output', 'w');

// Write CSV headers with proper column names
fputcsv($output, [
    'ID', 
    'Student ID', 
    'Teacher', 
    'Subject', 
    'Trade', 
    'Rating', 
    'Remarks', 
    'Submitted At'
], ',', '"');

// Filters from GET parameters
$teacher = $_GET['teacher'] ?? '';
$subject = $_GET['subject'] ?? '';
$trade = $_GET['trade'] ?? '';
$rating = $_GET['rating'] ?? '';
$date = $_GET['date'] ?? '';

// Build WHERE clause
$where = "WHERE 1";
if ($teacher !== '') $where .= " AND t.teacher_id = '" . mysqli_real_escape_string($conn, $teacher) . "'";
if ($subject !== '') $where .= " AND s.subject_id = '" . mysqli_real_escape_string($conn, $subject) . "'";
if ($trade !== '') $where .= " AND tr.trade_name = '" . mysqli_real_escape_string($conn, $trade) . "'";
if ($rating !== '') $where .= " AND f.rating = '" . mysqli_real_escape_string($conn, $rating) . "'";
if ($date !== '') $where .= " AND DATE(f.created_at) = '" . mysqli_real_escape_string($conn, $date) . "'";

// Fetch all data (without pagination)
$sql = "SELECT f.*, t.name AS teacher_name, s.name AS subject_name, tr.trade_name 
        FROM feedback f
        JOIN teachers t ON t.teacher_id = f.teacher_id
        JOIN trade tr ON tr.trade_id = f.trade_id
        JOIN subject s ON s.subject_id = f.subject_id
        $where
        ORDER BY f.created_at DESC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Write data rows
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['id'],
        $row['attendance_id'],
        $row['teacher_name'],
        $row['subject_name'],
        $row['trade_name'],
        $row['rating'],
        str_replace(["\r", "\n"], ' ', $row['remarks']), // Remove line breaks for cleaner CSV
        (new DateTime($row['created_at']))->format('d/m/Y h:i A')
    ], ',', '"');
}

// Close connection and exit
mysqli_close($conn);
exit;