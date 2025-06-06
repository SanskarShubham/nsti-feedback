<?php
session_start();
require_once '../connection.php'; // Your DB connection

?>



<?php
// Handle update when form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $name = $_POST["username"];
    $email = $_POST["email"];
    $mobile = $_POST["mobile"];
    $status = $_POST["status"];

    
    $id = $_POST["id"]; // Get the admin ID from the form

    
    // Update DB
    $updateSql = "UPDATE admin SET name = ?, email = ?, mobile = ?, status = ? WHERE id = ?";
    
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ssssi", $name, $email, $mobile, $status, $id);  // "ssssi" for string, string, string, string, int

    if ($updateStmt->execute()) {
        echo "<div class='alert alert-success mt-3'>✅ Profile updated successfully.</div>";
        header("Location: ../list-admin.php");
    } else {
        echo "<div class='alert alert-danger'>❌ Failed to update profile.</div>";
    }
}
?>
