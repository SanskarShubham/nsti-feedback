<?php
session_start();
include('../connection.php'); 

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // $sql = "DELETE FROM teachers WHERE teacher_id = ?";
    $sql = "UPDATE teachers set status = 0 WHERE teacher_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: ../list-admin.php");
    exit;
}

 include('footer.php'); 
 
 ?>