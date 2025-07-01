<?php
session_start();
require_once '../connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    try {
        // Validate and sanitize inputs
        $id = intval($_POST["id"]);
        $name = trim($_POST["username"]);
        $email = trim($_POST["email"]);
        $mobile = trim($_POST["mobile"]);
        $status = intval($_POST["status"]);
        $new_password = $_POST["new_password"] ?? '';
        $confirm_password = $_POST["confirm_password"] ?? '';

        // Basic validation
        if (empty($name) || empty($email) || empty($mobile)) {
            throw new Exception("All fields are required");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Initialize query and parameters
        $query = "UPDATE teachers SET name = ?, email = ?, mobile_no = ?, status = ?";
        $types = "sssi";
        $params = [$name, $email, $mobile, $status];

        // Handle password update if provided
        if (!empty($new_password)) {
            if ($new_password !== $confirm_password) {
                throw new Exception("Passwords do not match");
            }
            if (strlen($new_password) < 4) {
                throw new Exception("Password must be at least 8 characters");
            }

            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $query .= ", password = ?";
            $types .= "s";
            $params[] = $hashed_password;
        }

        // Complete the query
        $query .= " WHERE teacher_id = ?";
        $types .= "i";
        $params[] = $id;

        // Prepare and execute
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param($types, ...$params);

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        // Check if any rows were affected
        if ($stmt->affected_rows === 0) {
            throw new Exception("No changes made - record may not exist");
        }

        $_SESSION['success_message'] = "✅ Profile updated successfully";
        header("Location: ../list-admin.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['error_message'] = "❌ Error: " . $e->getMessage();
        header("Location: ../edit-admin.php?id=" . $id);
        exit();
    }
} else {
    $_SESSION['error_message'] = "Invalid request";
    header("Location: ../list-admin.php");
    exit();
}
?>