<?php
session_start();
require_once '../connection.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    // Get POST values
    $id = $_POST["id"];
    $attendance_id = trim($_POST["attendance_id"]);
    $name = trim($_POST["username"]);
    $trade = trim($_POST["trade"]);
    $program = $_POST["program"];

    // Update query
    $updateSql = "UPDATE students SET attendance_id = ?, name = ?, trade = ?, program = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ssssi", $attendance_id, $name, $trade, $program, $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "✅ Student profile updated successfully.";
        header("Location: ../list-students.php");
        exit;
    } else {
        echo "<div class='alert alert-danger mt-3'>❌ Failed to update student: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
?>
