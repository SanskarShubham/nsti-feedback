<?php
session_start();
require_once '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get form data
        $id = intval($_POST['id']);
        $name = trim($_POST['username']);
        $email = trim($_POST['email']);
        $mobile = trim($_POST['mobile']);
        $status = isset($_POST['status']) ? intval($_POST['status']) : 0;
        $designation = trim($_POST['designation'] ?? 'other'); // Default to 'other' if not set
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Basic validation
        if (empty($name) || empty($email) || empty($mobile) || empty($designation)) {
            throw new Exception("All required fields must be filled");
        }

        // Validate designation
        $allowed_designations = ['admin', 'other'];
        if (!in_array($designation, $allowed_designations)) {
            throw new Exception("Invalid designation selected");
        }

        // Password validation if provided
        if (!empty($new_password)) {
            if ($new_password !== $confirm_password) {
                throw new Exception("Password and confirmation do not match");
            }
            if (strlen($new_password) < 4) {
                throw new Exception("Password must be at least 4 characters");
            }
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        }

        // Start transaction
        $conn->begin_transaction();

        try {
            // Update teacher info (with or without password)
            if (!empty($new_password)) {
                $stmt = $conn->prepare("UPDATE teachers SET name=?, email=?, mobile_no=?, status=?, designation=?, password=? WHERE teacher_id=?");
                $stmt->bind_param("sssissi", $name, $email, $mobile, $status, $designation, $hashed_password, $id);
            } else {
                $stmt = $conn->prepare("UPDATE teachers SET name=?, email=?, mobile_no=?, status=?, designation=? WHERE teacher_id=?");
                $stmt->bind_param("sssisi", $name, $email, $mobile, $status, $designation, $id);
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to update teacher: " . $stmt->error);
            }

            // Process subject/trade/program rows
            $programs = $_POST['program'] ?? [];
            $trades = $_POST['trade'] ?? [];
            $subjects = $_POST['subject'] ?? [];
            $tst_ids = $_POST['teacher_subject_trade_id'] ?? [];

            for ($i = 0; $i < count($programs); $i++) {
                $program = mysqli_real_escape_string($conn, $programs[$i]);
                $trade_id = intval($trades[$i]);
                $subject_id = intval($subjects[$i]);

                if (!empty($program) && $trade_id > 0 && $subject_id > 0) {
                    if (!empty($tst_ids[$i])) {
                        // UPDATE existing row
                        $tst_id = intval($tst_ids[$i]);
                        $stmt = $conn->prepare("UPDATE teacher_subject_trade SET program=?, trade_id=?, subject_id=? WHERE id=? AND teacher_id=?");
                        $stmt->bind_param("siiii", $program, $trade_id, $subject_id, $tst_id, $id);
                        $stmt->execute();
                    } else {
                        // INSERT new row
                        $stmt = $conn->prepare("INSERT INTO teacher_subject_trade (teacher_id, program, trade_id, subject_id) VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("isii", $id, $program, $trade_id, $subject_id);
                        $stmt->execute();
                    }
                }
            }

            // Remove deleted rows
            $submitted_ids = array_filter(array_map('intval', $tst_ids));
            if (!empty($submitted_ids)) {
                $ids_str = implode(',', $submitted_ids);
                $delete_sql = "DELETE FROM teacher_subject_trade WHERE teacher_id = $id AND id NOT IN ($ids_str)";
                $conn->query($delete_sql);
            } else {
                $conn->query("DELETE FROM teacher_subject_trade WHERE teacher_id = $id");
            }

            // Commit transaction
            $conn->commit();

            $_SESSION['success_message'] = "Teacher updated successfully!";
            header("Location: ../list-teachers.php");
            exit();

        } catch (Exception $e) {
            $conn->rollback();
            throw $e;
        }

    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        header("Location: ../edit-teacher.php?id=".$id);
        exit();
    }
} else {
    $_SESSION['error_message'] = "Invalid request method";
    header("Location: ../list-teachers.php");
    exit();
}
?>