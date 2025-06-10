<?php
session_start();
require_once '../connection.php'; // Your DB connection

?>



<?php
// Handle update when form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $name = $_POST["tradename"];
    $program = $_POST["program"];

    
    $id = $_POST["id"]; // Get the trade ID from the form

    
    // Update DB
    $updateSql = "UPDATE trade SET trade_name = ?, program = ? WHERE id = ?";
    
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ssi", $name, $program, $id);  // "ssssi" for string, string, string, string, int

    if ($updateStmt->execute()) {
        echo "<div class='alert alert-success mt-3'>✅ trade updated successfully.</div>";
        header("Location: ../list-trade.php");
    } else {
        echo "<div class='alert alert-danger'>❌ Failed to update trade.</div>";
    }
}
?>
