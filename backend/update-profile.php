<?php
session_start();
require_once '../connection.php'; // Ensure $conn is your database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"];
    $name = $_POST["username"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    $uploadDir = "../dp_uploads/";
    $imagePath = ""; // new image path to be saved in DB

    // STEP 1: Get old image path from DB
    $getOldImageQuery = "SELECT dp_file_path FROM admin WHERE id = ?";
    $stmtOld = $conn->prepare($getOldImageQuery);
    $stmtOld->bind_param("i", $id);
    $stmtOld->execute();
    $stmtOld->bind_result($oldImagePath);
    $stmtOld->fetch();
    $stmtOld->close();

    // STEP 2: If new image is uploaded
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
        $fileName = basename($_FILES["image"]["name"]);
        $targetPath = $uploadDir . $fileName;

        // Create directory if not exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // DELETE OLD IMAGE from local storage
        if (!empty($oldImagePath)) {
            $fullOldPath = "../" . $oldImagePath; // relative to script location
            if (file_exists($fullOldPath)) {
                unlink($fullOldPath); // delete file
            }
        }

        // MOVE new file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
            $imagePath = "dp_uploads/" . $fileName; // relative path to save in DB
        } else {
            echo "❌ Failed to upload new image.";
            exit;
        }
    }

    // STEP 3: Prepare SQL for updating
    if ($imagePath !== "") {
        $sql = "UPDATE admin SET name = ?, email = ?, mobile = ?, dp_file_path = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $email, $phone, $imagePath, $id);
    } else {
        $sql = "UPDATE admin SET name = ?, email = ?, mobile = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $email, $phone, $id);
    }

    // STEP 4: Execute and update session
    if ($stmt->execute()) {
        $_SESSION['admin_data']['name'] = $name;
        $_SESSION['admin_data']['email'] = $email;
        $_SESSION['admin_data']['mobile'] = $phone;
        if ($imagePath !== "") {
            $_SESSION['admin_data']['dp_file_path'] = $imagePath;
        }

        header("Location: ../profile.php?status=success");
        exit;
    } else {
        echo "❌ Error updating profile: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
