<?php
require_once('connection.php');

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=students_export_' . date('Y-m-d') . '.csv');

$output = fopen('php://output', 'w');

fputcsv($output, [
    'ID', 
    'Attendance ID', 
    'Name', 
    'Trade', 
    'Program'
], ',', '"');

// Filters from GET parameters
$name_filter = $_GET['name'] ?? '';
$trade_filter = $_GET['trade'] ?? '';
$program_filter = $_GET['program'] ?? '';

// Build WHERE clause
$where = [];
if (!empty($name_filter)) $where[] = "name LIKE '%" . mysqli_real_escape_string($conn, $name_filter) . "%'";
if (!empty($trade_filter)) {
    $trades = explode(',', $trade_filter);
    $escaped_trades = array_map(function ($t) use ($conn) {
        return "'" . mysqli_real_escape_string($conn, $t) . "'";
    }, $trades);
    $where[] = "trade IN (" . implode(',', $escaped_trades) . ")";
}
if (!empty($program_filter)) $where[] = "program = '" . mysqli_real_escape_string($conn, $program_filter) . "'";
$where_sql = !empty($where) ? "WHERE " . implode(" AND ", $where) : '';

// Fetch all data (without pagination)
$sql = "SELECT * FROM students $where_sql ORDER BY trade ASC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['id'],
        $row['attendance_id'],
        $row['name'],
        $row['trade'],
        $row['program']
    ], ',', '"');
}

mysqli_close($conn);
exit;