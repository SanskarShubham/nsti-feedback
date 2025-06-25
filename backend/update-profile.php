<?php
session_start();
require_once '../connection.php'; // Your DB connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST["id"]);
    $name = $_POST["username"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $uploadDir = "../dp_uploads/";
    $imagePath = ""; // New image path to save in DB

    // STEP 1: Get old image path from DB
    $getOldImageQuery = "SELECT dp_file_path FROM teachers WHERE teacher_id = ?";
    $stmtOld = $conn->prepare($getOldImageQuery);
    $stmtOld->bind_param("i", $id);
    $stmtOld->execute();
    $stmtOld->bind_result($oldImagePath);
    $stmtOld->fetch();
    $stmtOld->close();

    // STEP 2: Handle new image upload if any
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
        $fileName = basename($_FILES["image"]["name"]);
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);

        // Create unique filename to avoid overwriting
        $newFileName = uniqid("profile_", true) . '.' . $ext;

        // Create directory if not exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $targetPath = $uploadDir . $newFileName;

        // Move uploaded file first
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
            // Delete old image after successful upload
            if (!empty($oldImagePath)) {
                $fullOldPath = "../" . $oldImagePath;
                if (file_exists($fullOldPath)) {
                    unlink($fullOldPath);
                }
            }

            $imagePath = "dp_uploads/" . $newFileName; // Relative path to store in DB
        } else {
            echo "❌ Failed to upload new image.";
            exit;
        }
    }


    // STEP 3: Prepare SQL update query
    if ($imagePath !== "") {
        $sql = "UPDATE teachers SET name = ?, email = ?, mobile_no = ?, dp_file_path = ? WHERE teacher_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $email, $phone, $imagePath, $id);
      
    }

    // STEP 4: Execute and update session
    if ($stmt->execute()) {
        $_SESSION['admin_data']['name'] = $name;
        $_SESSION['admin_data']['email'] = $email;
        $_SESSION['admin_data']['mobile_no'] = $phone;

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
