<?php
session_start();
require_once '../connection.php'; // Your DB connection

?>


<?php
// Handle update when form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $name = $_POST["subjectname"];

    
    $id = $_POST["id"]; // Get the subject ID from the form

    
    // Update DB
    $updateSql = "UPDATE subject SET name = ? WHERE subject_id = ?";
    
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("si", $name, $id);  // "ssssi" for string, string, string, string, int

    if ($updateStmt->execute()) {
        echo "<div class='alert alert-success mt-3'>✅ subject updated successfully.</div>";
        header("Location: ../list-subject.php");
    } else {
        echo "<div class='alert alert-danger'>❌ Failed to update subject.</div>";
    }
}
?>
