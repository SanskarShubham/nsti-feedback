<?php
session_start();
include('../connection.php'); 

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM students WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: ../list-students.php");
    exit;
}

 include('footer.php'); 
 
 ?>