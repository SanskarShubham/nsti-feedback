<?php
session_start();
require_once '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = intval($_POST['id']);
        $name = trim($_POST['username']);
        $email = trim($_POST['email']);
        $mobile = trim($_POST['mobile']);
        $status = isset($_POST['status']) ? intval($_POST['status']) : 0;
        $designation = trim($_POST['designation'] ?? 'other');
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($name) || empty($email) || empty($mobile) || empty($designation)) {
            throw new Exception("All required fields must be filled");
        }

        if (!in_array($designation, ['admin', 'other'])) {
            throw new Exception("Invalid designation selected");
        }

        if (!empty($new_password)) {
            if ($new_password !== $confirm_password) {
                throw new Exception("Passwords do not match");
            }
            if (strlen($new_password) < 4) {
                throw new Exception("Password must be at least 4 characters");
            }
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        }

        $conn->begin_transaction();

        // Update teacher info
        if (!empty($new_password)) {
            $stmt = $conn->prepare("UPDATE teachers SET name=?, email=?, mobile_no=?, status=?, designation=?, password=? WHERE teacher_id=?");
            $stmt->bind_param("sssissi", $name, $email, $mobile, $status, $designation, $hashed_password, $id);
        } else {
            $stmt = $conn->prepare("UPDATE teachers SET name=?, email=?, mobile_no=?, status=?, designation=? WHERE teacher_id=?");
            $stmt->bind_param("sssisi", $name, $email, $mobile, $status, $designation, $id);
        }
        if (!$stmt->execute()) throw new Exception("Teacher update failed: " . $stmt->error);

        // Handle subject/trade/programs
        $programs = $_POST['program'] ?? [];
        $trades = $_POST['trade'] ?? [];
        $subjects = $_POST['subject'] ?? [];
        $tst_ids = $_POST['teacher_subject_trade_id'] ?? [];

        $existing_ids = [];

        for ($i = 0; $i < count($programs); $i++) {
            $program = trim($programs[$i]);
            $trade_id = intval($trades[$i]);
            $subject_id = intval($subjects[$i]);

            if (empty($program) || $trade_id <= 0 || $subject_id <= 0) continue;

            $tst_id = $tst_ids[$i] ?? '';

            if (!empty($tst_id)) {
                $tst_id = intval($tst_id);
                $stmt = $conn->prepare("UPDATE teacher_subject_trade SET program=?, trade_id=?, subject_id=? WHERE id=? AND teacher_id=?");
                $stmt->bind_param("siiii", $program, $trade_id, $subject_id, $tst_id, $id);
                $stmt->execute();
                $existing_ids[] = $tst_id;
            } else {
                $stmt = $conn->prepare("INSERT INTO teacher_subject_trade (teacher_id, program, trade_id, subject_id) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isii", $id, $program, $trade_id, $subject_id);
                $stmt->execute();
                $existing_ids[] = $conn->insert_id;
            }
        }

        // Delete removed subject rows
        $existing_ids_str = implode(',', array_map('intval', $existing_ids));
        if (!empty($existing_ids_str)) {
            $conn->query("DELETE FROM teacher_subject_trade WHERE teacher_id = $id AND id NOT IN ($existing_ids_str)");
        } else {
            $conn->query("DELETE FROM teacher_subject_trade WHERE teacher_id = $id");
        }

        $conn->commit();
        $_SESSION['success_message'] = "✅ Teacher updated successfully.";
        header("Location: ../list-teachers.php");
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error_message'] = "❌ " . $e->getMessage();
        header("Location: ../edit-teacher.php?id=$id");
        exit;
    }
} else {
    $_SESSION['error_message'] = "❌ Invalid request method.";
    header("Location: ../list-teachers.php");
    exit;
}
