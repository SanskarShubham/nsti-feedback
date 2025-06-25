<?php
session_start();
require_once '../connection.php'; // Your DB connection


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // print_r($_POST);exit;
    $id = intval($_POST['id']);
    $name = trim($_POST['username']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $status = isset($_POST['status']) ? intval($_POST['status']) : 0;

    // Basic validation
    if (empty($name) || empty($email) || empty($mobile)) {
        die("❌ All fields are required.");
    }

    // Step 1: Update teacher's main info
    $stmt = $conn->prepare("UPDATE teachers SET name = ?, email = ?, mobile_no = ?, status = ? WHERE teacher_id = ?");
    $stmt->bind_param("sssii", $name, $email, $mobile, $status, $id);
    $stmt->execute();

    // Step 2: Process subject/trade/program rows
    $programs = $_POST['program'];
    $trades = $_POST['trade'];
    $subjects = $_POST['subject'];
    $tst_ids = isset($_POST['teacher_subject_trade_id']) ? $_POST['teacher_subject_trade_id'] : [];

    for ($i = 0; $i < count($programs); $i++) {



        $program = mysqli_real_escape_string($conn, $programs[$i]);
        $trade_id = intval($trades[$i]);
        $subject_id = intval($subjects[$i]);

        if (!empty($program) && $trade_id > 0 && $subject_id > 0) {

            if (!empty($tst_ids[$i])) {
                // UPDATE existing row
                $tst_id = intval($tst_ids[$i]);
                $stmt = $conn->prepare("UPDATE teacher_subject_trade SET program = ?, trade_id = ?, subject_id = ? WHERE id = ? AND teacher_id = ?");
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


    // Step 3: Remove deleted rows
    // Get all teacher_subject_trade_ids submitted in the form
    $submitted_ids = array_filter(array_map('intval', $tst_ids)); // ensure it's clean and non-empty

    if (!empty($submitted_ids)) {
        // Build a comma-separated string of valid IDs
        $ids_str = implode(',', $submitted_ids);

        // Delete old rows NOT present in form submission
        $delete_sql = "DELETE FROM teacher_subject_trade WHERE teacher_id = $id AND id NOT IN ($ids_str)";
        $conn->query($delete_sql);
    } else {
        // If no IDs were submitted, delete all mappings for this teacher
        $conn->query("DELETE FROM teacher_subject_trade WHERE teacher_id = $id");
    }
    // ✅ Optional: redirect or show success
    echo "<div class='alert alert-success mt-3'>✅ Profile updated successfully.</div>";
    header("Location: ../list-teachers.php");
    exit();
} else {
    echo "<div class='alert alert-danger'>❌ Failed to update profile.</div>";
}
