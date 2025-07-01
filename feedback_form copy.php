<?php
session_start();
include 'connection.php';

function feedbackExists($conn, $attendance_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM feedback WHERE attendance_id = ? AND status = 1");
    $stmt->bind_param("s", $attendance_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return ($row['cnt'] > 0);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['attendance_id']) && !isset($_POST['submit_feedback'])) {
        $attendance_id = $_POST['attendance_id'];
        
        if (feedbackExists($conn, $attendance_id)) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'exists', 'message' => 'Feedback already submitted']);
            exit;
        }

        $stmt = $conn->prepare("SELECT * FROM students WHERE attendance_id = ?");
        $stmt->bind_param("s", $attendance_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $student = $result->fetch_assoc();
            $_SESSION['student_name'] = $student['name'];
            $trade_name = $student['trade'];
            $program = $student['program'];
            
            $stmt = $conn->prepare("SELECT trade_id FROM trade WHERE trade_name = ? LIMIT 1");
            $stmt->bind_param("s", $trade_name);
            $stmt->execute();
            $trade_result = $stmt->get_result();
            $trade_row = $trade_result->fetch_assoc();
            $trade_id = $trade_row['trade_id'];

            // Modified query to include program filter
            $stmt = $conn->prepare("
                SELECT tst.id, tst.teacher_id, tst.trade_id, tst.subject_id, tst.program, 
                       t.name AS teacher_name, tr.trade_name, s.name AS subject_name
                FROM teacher_subject_trade tst
                LEFT JOIN teachers t ON tst.teacher_id = t.teacher_id
                LEFT JOIN trade tr ON tst.trade_id = tr.trade_id
                LEFT JOIN subject s ON tst.subject_id = s.subject_id
                WHERE tst.trade_id = ? AND tst.program = ?
            ");
            $stmt->bind_param("is", $trade_id, $program);
            $stmt->execute();
            $teacher_result = $stmt->get_result();
            
            $teachers = [];
            while ($row = $teacher_result->fetch_assoc()) {
                $teachers[] = $row;
            }

            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'data' => [
                    'name' => $student['name'],
                    'program' => $program,
                    'trade' => $trade_name,
                    'student_id' => $student['id'],
                    'attendance_id' => $student['attendance_id'],
                    'teacher_sub_trade_rows' => $teachers
                ]
            ]);
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Student not found']);
            exit;
        }
    }
    elseif (isset($_POST['submit_feedback'])) {
        $attendance_id = $_POST['attendance_id'];
        
        if (feedbackExists($conn, $attendance_id)) {
            die("<script>showStatus('Feedback already submitted', 'error');</script>");
        }

        $conn->begin_transaction();
        try {
            // First get student's trade and program for validation
            $stmt = $conn->prepare("SELECT trade, program FROM students WHERE attendance_id = ?");
            $stmt->bind_param("s", $attendance_id);
            $stmt->execute();
            $student = $stmt->get_result()->fetch_assoc();
            
            if (!$student) {
                throw new Exception("Student not found");
            }

            // Get trade_id for validation
            $stmt = $conn->prepare("SELECT trade_id FROM trade WHERE trade_name = ? LIMIT 1");
            $stmt->bind_param("s", $student['trade']);
            $stmt->execute();
            $trade_row = $stmt->get_result()->fetch_assoc();
            $trade_id = $trade_row['trade_id'];

            foreach ($_POST['teacher_id'] as $index => $teacher_id) {
                // Validate each teacher-subject-trade combination
                $stmt = $conn->prepare("
                    SELECT COUNT(*) as valid FROM teacher_subject_trade 
                    WHERE teacher_id = ? 
                    AND trade_id = ? 
                    AND subject_id = ? 
                    AND program = ?
                ");
                $stmt->bind_param(
                    "iiis",
                    $teacher_id,
                    $_POST['trade_id'][$index],
                    $_POST['subject_id'][$index],
                    $student['program']
                );
                $stmt->execute();
                $valid = $stmt->get_result()->fetch_assoc()['valid'];

                if (!$valid) {
                    throw new Exception("Invalid teacher-subject combination for this student");
                }

                // Only insert if validation passed
                $rating = $_POST['rating'][$index];
                $remarks = $_POST['remarks'][$index];
                
                $stmt = $conn->prepare("
                    INSERT INTO feedback 
                    (teacher_id, trade_id, subject_id, program, attendance_id, rating, remarks, created_at, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 1)
                ");
                $stmt->bind_param(
                    "iiissss",
                    $teacher_id,
                    $_POST['trade_id'][$index],
                    $_POST['subject_id'][$index],
                    $student['program'],
                    $attendance_id,
                    $rating,
                    $remarks
                );
                $stmt->execute();
            }
            $conn->commit();
            header("Location: success.php");
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            die("<script>showStatus('Error: ".addslashes($e->getMessage())."', 'error');</script>");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="feedback_form.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-comment-alt"></i> Feedback Portal</h1>
            <p>Share your valuable feedback</p>
        </div>

        <div class="form-container">
            <div class="section">
                <h2 class="section-title"><i class="fas fa-user-graduate"></i> Student Info</h2>
                <div class="form-group">
                    <label for="attendance_id" class="required">Attendance ID</label>
                    <input type="number" id="attendance_id" class="input-control" placeholder="Enter attendance ID" required>
                </div>
                <button id="fetchInfoBtn" class="btn btn-primary btn-block">
                    <i class="fas fa-search"></i> Fetch Info
                </button>
                <div id="statusMessage" class="status-message"></div>
            </div>

            <form method="POST" id="feedbackForm" class="hidden">
                <input type="hidden" id="student_id" name="student_id">
                <input type="hidden" id="attendance_id_hidden" name="attendance_id">
                
                <div class="section">
                    <h2 class="section-title"><i class="fas fa-info-circle"></i> Student Details</h2>
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" id="name" class="input-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Program</label>
                        <input type="text" id="program" class="input-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Trade</label>
                        <input type="text" id="trade" class="input-control" readonly>
                    </div>
                </div>

                <div class="section">
                    <h2 class="section-title"><i class="fas fa-chalkboard-teacher"></i> Teacher Feedback</h2>
                    <div id="teacherCardsContainer" class="teacher-cards"></div>
                </div>

                <button type="submit" name="submit_feedback" class="btn btn-primary btn-block">
                    <i class="fas fa-paper-plane"></i> Submit Feedback
                </button>
            </form>
        </div>

        <div class="footer">
            <p>Made with <span class="heart">❤️</span> CSA</p>
        </div>
    </div>

    <script>
        function showStatus(message, type) {
            const el = document.getElementById('statusMessage');
            el.textContent = message;
            el.className = `status-message ${type}-msg show`;
            setTimeout(() => el.classList.remove('show'), 5000);
        }

        document.getElementById('fetchInfoBtn').addEventListener('click', async function() {
            const attendanceId = document.getElementById('attendance_id').value.trim();
            if (!attendanceId) {
                showStatus('Please enter Attendance ID', 'error');
                return;
            }

            const btn = this;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Fetching...';
            btn.disabled = true;

            try {
                const response = await fetch('', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `attendance_id=${attendanceId}`
                });
                const data = await response.json();
                
                if (data.status === 'success') {
                    displayStudentData(data.data);
                    showStatus('Data loaded', 'success');
                } else {
                    showStatus(data.message || 'Error', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showStatus('Network error', 'error');
            } finally {
                btn.innerHTML = '<i class="fas fa-search"></i> Fetch Info';
                btn.disabled = false;
            }
        });

        function displayStudentData(data) {
            document.getElementById('student_id').value = data.student_id;
            document.getElementById('attendance_id_hidden').value = data.attendance_id;
            document.getElementById('name').value = data.name;
            document.getElementById('program').value = data.program;
            document.getElementById('trade').value = data.trade;

            const container = document.getElementById('teacherCardsContainer');
            container.innerHTML = '';

            data.teacher_sub_trade_rows.forEach((teacher, index) => {
                const card = document.createElement('div');
                card.className = 'teacher-card';
                card.innerHTML = `
                    <input type="hidden" name="teacher_id[]" value="${teacher.teacher_id}">
                    <input type="hidden" name="trade_id[]" value="${teacher.trade_id}">
                    <input type="hidden" name="subject_id[]" value="${teacher.subject_id}">
                    <input type="hidden" name="program[]" value="${teacher.program}">
                    
                    <div class="teacher-name">${teacher.teacher_name}</div>
                    <div class="subject-name">${teacher.subject_name}</div>
                    
                    <div class="form-group">
                        <label>Rating <span class="required">*</span></label>
                        <div class="stars">
                            ${[5,4,3,2,1].map(star => `
                                <input type="radio" name="rating[${index}]" id="star${star}_${index}" value="${star}" required>
                                <label for="star${star}_${index}">★</label>
                            `).join('')}
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Remarks <span class="required">*</span></label>
                        <textarea name="remarks[]" class="input-control" required></textarea>
                    </div>
                `;
                container.appendChild(card);
            });

            document.getElementById('feedbackForm').classList.remove('hidden');
        }

        document.getElementById('feedbackForm').addEventListener('submit', function(e) {
            const invalidCards = [];
            document.querySelectorAll('.teacher-card').forEach((card, index) => {
                const rating = card.querySelector('input[type="radio"]:checked');
                const remarks = card.querySelector('textarea').value.trim();
                if (!rating || !remarks) {
                    invalidCards.push(index + 1);
                    card.style.border = '1px solid var(--error)';
                }
            });
            
            if (invalidCards.length > 0) {
                e.preventDefault();
                showStatus(`Please complete feedback for teacher(s): ${invalidCards.join(', ')}`, 'error');
            }
        });
    </script>
</body>
</html>