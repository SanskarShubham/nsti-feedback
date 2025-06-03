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

    // $imagePath = $_POST['dp_file_path']; // default to old
    // $uploadDir = "../dp_uploads/";
    // echo "<PRE>";
    // print_r($_POST);
    // print_r($_FILES);exit;
    // If new image is uploaded
    // if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    //     $fileName = basename($_FILES['image']['name']);
    //     $targetPath = $uploadDir . $fileName;

    //     if (!is_dir($uploadDir)) {
    //         mkdir($uploadDir, 0755, true);
    //     }

    //     // delete old file
    //     if (!empty($imagePath) && file_exists("../" . $imagePath)) {
    //         unlink("../" . $imagePath);
    //     }

    //     if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
    //         $imagePath = "dp_uploads/" . $fileName;
    //     }
    // }

    // Update DB
    $updateSql = "UPDATE admin SET name = ?, email = ?, mobile = ? /*, dp_file_path = ?*/ WHERE id = ?";
    
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("sssi", $name, $email, $mobile, /* $imagePath,*/ $id);  // "ssssi" for string, string, string, string, int

    if ($updateStmt->execute()) {
        echo "<div class='alert alert-success mt-3'>✅ Profile updated successfully.</div>";
        header("Location: ../list-admin.php");
    } else {
        echo "<div class='alert alert-danger'>❌ Failed to update profile.</div>";
    }
}
?>
