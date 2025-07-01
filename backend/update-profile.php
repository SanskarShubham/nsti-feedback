<?php
session_start();
require_once '../connection.php'; // Your DB connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST["id"]);
    $name = $_POST["username"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $newPassword = $_POST["new_password"] ?? null;
    $confirmPassword = $_POST["confirm_password"] ?? null;
    $uploadDir = "../dp_uploads/";
    $imagePath = ""; // New image path to save in DB

    // STEP 1: Get old image path and current password hash from DB
    $getOldDataQuery = "SELECT dp_file_path, password FROM teachers WHERE teacher_id = ?";
    $stmtOld = $conn->prepare($getOldDataQuery);
    $stmtOld->bind_param("i", $id);
    $stmtOld->execute();
    $stmtOld->bind_result($oldImagePath, $currentPasswordHash);
    $stmtOld->fetch();
    $stmtOld->close();

    // STEP 2: Handle password update if provided
    $passwordUpdate = false;
    if (!empty($newPassword)) {
        if ($newPassword !== $confirmPassword) {
            header("Location: ../profile.php?status=error&message=Passwords+do+not+match");
            exit;
        }
        
        // Validate password strength if needed
        if (strlen($newPassword) < 4) {
            header("Location: ../profile.php?status=error&message=Password+must+be+at+least+8+characters");
            exit;
        }
        
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $passwordUpdate = true;
    }

    // STEP 3: Handle new image upload if any
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
        // Validate image
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $fileType = $_FILES["image"]["type"];
        $fileSize = $_FILES["image"]["size"]; // in bytes
        
        if (!in_array($fileType, $allowedTypes)) {
            header("Location: ../profile.php?status=error&message=Invalid+file+type.+Only+JPEG,+JPG+and+PNG+are+allowed");
            exit;
        }
        
        if ($fileSize > 2097152) { // 2MB in bytes
            header("Location: ../profile.php?status=error&message=File+size+exceeds+2MB+limit");
            exit;
        }

        $fileName = basename($_FILES["image"]["name"]);
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

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
            header("Location: ../profile.php?status=error&message=Failed+to+upload+new+image");
            exit;
        }
    }

    // STEP 4: Prepare SQL update query based on what needs to be updated
    if ($imagePath !== "" && $passwordUpdate) {
        // Update all fields including password and image
        $sql = "UPDATE teachers SET name = ?, email = ?, mobile_no = ?, dp_file_path = ?, password = ? WHERE teacher_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $name, $email, $phone, $imagePath, $passwordHash, $id);
    } elseif ($imagePath !== "") {
        // Update all fields except password
        $sql = "UPDATE teachers SET name = ?, email = ?, mobile_no = ?, dp_file_path = ? WHERE teacher_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $email, $phone, $imagePath, $id);
    } elseif ($passwordUpdate) {
        // Update all fields except image
        $sql = "UPDATE teachers SET name = ?, email = ?, mobile_no = ?, password = ? WHERE teacher_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $email, $phone, $passwordHash, $id);
    } else {
        // Update only basic info
        $sql = "UPDATE teachers SET name = ?, email = ?, mobile_no = ? WHERE teacher_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $email, $phone, $id);
    }

    // STEP 5: Execute and update session
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
        header("Location: ../profile.php?status=error&message=Error+updating+profile");
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../profile.php?status=error&message=Invalid+request");
    exit;
}