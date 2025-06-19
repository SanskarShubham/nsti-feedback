<?php
include 'connection.php';

// Check if feedback already submitted for given attendance_id
function feedbackExists($conn, $attendance_id) {
    $attendance_id_esc = mysqli_real_escape_string($conn, $attendance_id);
    $check_query = "SELECT COUNT(*) as cnt FROM feedback WHERE attendance_id = '$attendance_id_esc' AND status = 1";
    $res = mysqli_query($conn, $check_query);
    if ($res) {
        $row = mysqli_fetch_assoc($res);
        return ($row['cnt'] > 0);
    }
    return false;
}

// Handle AJAX fetch student info + teacher data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['attendance_id']) && !isset($_POST['submit_feedback'])) {
  $attendance_id = mysqli_real_escape_string($conn, $_POST['attendance_id']);

  // Check if feedback already submitted
  if (feedbackExists($conn, $attendance_id)) {
    echo json_encode(['status' => 'exists', 'message' => 'Feedback already submitted for this student.']);
    exit;
  }

  $query = "SELECT * FROM students WHERE attendance_id = '$attendance_id'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) === 1) {
    $student = mysqli_fetch_assoc($result);

    $trade_name = $student['trade'];
    $trade_query = "SELECT trade_id FROM trade WHERE trade_name = '$trade_name' LIMIT 1";
    $trade_result = mysqli_query($conn, $trade_query);
    $trade_row = mysqli_fetch_assoc($trade_result);
    $trade_id = $trade_row['trade_id'];

    $teacher_sub_trade_query = "
      SELECT tst.id, tst.teacher_id, tst.trade_id, tst.subject_id, tst.program, t.name AS teacher_name, tr.trade_name, s.name AS subject_name
      FROM teacher_subject_trade tst
      LEFT JOIN teachers t ON tst.teacher_id = t.teacher_id
      LEFT JOIN trade tr ON tst.trade_id = tr.trade_id
      LEFT JOIN subject s ON tst.subject_id = s.subject_id
      WHERE tst.trade_id = $trade_id";
    $teacher_sub_trade_result = mysqli_query($conn, $teacher_sub_trade_query);
    $teacher_sub_trade_rows = mysqli_fetch_all($teacher_sub_trade_result, MYSQLI_ASSOC);

    echo json_encode([
      'status' => 'success',
      'data' => [
        'name' => $student['name'],
        'program' => $student['program'],
        'trade' => $student['trade'],
        'student_id' => $student['id'],
        'attendance_id' => $student['attendance_id'],
        'teacher_sub_trade_rows' => $teacher_sub_trade_rows
      ]
    ]);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Student details not found.']);
  }
  exit;
}

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_feedback'])) {
  $student_id = intval($_POST['student_id']);
  $attendance_id = mysqli_real_escape_string($conn, $_POST['attendance_id']);

  // Check if feedback already submitted - server side validation
  if (feedbackExists($conn, $attendance_id)) {
    echo "<script>
            showStatus('Feedback already submitted for this student.', 'error');
          </script>";
    exit;
  }

  $teacher_ids = $_POST['teacher_id'];
  $trade_ids = $_POST['trade_id'];
  $subject_ids = $_POST['subject_id'];
  $programs = $_POST['program'];
  $ratings = $_POST['rating'] ?? [];
  $remarks_arr = $_POST['remarks'] ?? [];

  // Validation: all fields must be filled
  $errors = [];

  for ($i = 0; $i < count($teacher_ids); $i++) {
    $rating = $ratings[$i] ?? null;
    $remarks = trim($remarks_arr[$i] ?? '');

    if (empty($rating)) {
      $errors[] = "Rating missing for teacher index $i.";
    }
    if ($remarks === '') {
      $errors[] = "Remarks missing for teacher index $i.";
    }
  }

  if (!empty($errors)) {
    $error_str = implode("\n", $errors);
    echo "<script>
            showStatus('Please fill all mandatory fields:\\n$error_str', 'error');
          </script>";
    exit;
  }

  // Insert feedback
  $created_by = NULL; // Adjust if needed

  $insert_errors = [];
  for ($i = 0; $i < count($teacher_ids); $i++) {
    $teacher_id = intval($teacher_ids[$i]);
    $trade_id = intval($trade_ids[$i]);
    $subject_id = intval($subject_ids[$i]);
    $program = mysqli_real_escape_string($conn, $programs[$i]);
    $rating = intval($ratings[$i]);
    $remarks = mysqli_real_escape_string($conn, $remarks_arr[$i]);

    $insert = "INSERT INTO feedback (teacher_id, trade_id, subject_id, program, attendance_id, rating, remarks, created_at, created_by, status)
               VALUES ('$teacher_id', '$trade_id', '$subject_id', '$program', '$attendance_id', '$rating', '$remarks', NOW(), ".($created_by ?? "NULL").", 1)";
    if (!mysqli_query($conn, $insert)) {
      $insert_errors[] = mysqli_error($conn);
    }
  }

  if (empty($insert_errors)) {
    echo "<script>
            showSuccessModal();
          </script>";
  } else {
    $error_str = implode(", ", $insert_errors);
    echo "<script>
            showStatus('Error submitting feedback: $error_str', 'error');
          </script>";
  }
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Feedback Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary: #7e57c2;
            --primary-dark: #5e35b1;
            --primary-light: #b085f5;
            --accent: #ff4081;
            --light-bg: #f5f3ff;
            --card-bg: #ffffff;
            --text: #333333;
            --text-light: #757575;
            --border: #e0e0e0;
            --success: #4caf50;
            --warning: #ff9800;
            --error: #f44336;
            --shadow: 0 4px 12px rgba(126, 87, 194, 0.1);
            --shadow-hover: 0 6px 16px rgba(126, 87, 194, 0.2);
            --transition: all 0.3s ease;
        }

        body {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow-x: hidden;
            transition: filter 0.5s ease;
        }

        body.blurred {
            filter: blur(5px);
        }

        .container {
            max-width: 800px;
            width: 100%;
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin: 20px;
            transition: var(--transition);
            position: relative;
            z-index: 10;
        }

        .container:hover {
            box-shadow: var(--shadow-hover);
        }

        .header {
            background: linear-gradient(to right, var(--primary-dark), var(--primary));
            color: white;
            padding: 30px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            transform: rotate(30deg);
        }

        .header h1 {
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .form-container {
            padding: 30px;
        }

        .section {
            margin-bottom: 30px;
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            padding: 25px;
            transition: var(--transition);
        }

        .section:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .section-title {
            font-size: 1.4rem;
            color: var(--primary-dark);
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--primary-light);
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 12px;
            background: var(--primary-light);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--primary-dark);
            font-size: 0.95rem;
        }

        .required::after {
            content: " *";
            color: var(--accent);
        }

        .input-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-size: 1rem;
            transition: var(--transition);
            background: #fafafa;
        }

        .input-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(126, 87, 194, 0.2);
            background: white;
        }

        .input-control:read-only {
            background-color: #f0f0f0;
            color: var(--text-light);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 28px;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 10px;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 6px rgba(126, 87, 194, 0.2);
        }

        .btn i {
            margin-right: 12px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            box-shadow: 0 6px 10px rgba(126, 87, 194, 0.3);
            transform: translateY(-2px);
        }

        .btn-block {
            width: 100%;
        }

        .hidden {
            display: none;
        }

        .teacher-cards {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .teacher-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            transition: var(--transition);
            border-left: 4px solid var(--primary);
        }

        .teacher-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .teacher-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 5px;
        }

        .subject-name {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .stars {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 5px;
            margin: 10px 0;
        }

        .stars input {
            display: none;
        }

        .stars label {
            font-size: 28px;
            color: #ddd;
            cursor: pointer;
            transition: var(--transition);
        }

        .stars label:hover,
        .stars label:hover ~ label,
        .stars input:checked ~ label {
            color: #ffc107;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: var(--text-light);
            font-size: 0.9rem;
            border-top: 1px solid var(--border);
            margin-top: 30px;
        }

        .heart {
            color: #f44336;
            margin: 0 5px;
        }

        .status-message {
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            text-align: center;
            font-weight: 500;
            transition: all 0.5s ease;
            opacity: 0;
            height: 0;
            overflow: hidden;
        }

        .status-message.show {
            opacity: 1;
            height: auto;
            padding: 15px;
            margin: 15px 0;
        }

        .success-msg {
            background: rgba(76, 175, 80, 0.15);
            color: var(--success);
            border: 1px solid var(--success);
        }

        .error-msg {
            background: rgba(244, 67, 54, 0.15);
            color: var(--error);
            border: 1px solid var(--error);
        }

        .info-msg {
            background: rgba(33, 150, 243, 0.15);
            color: #2196f3;
            border: 1px solid #2196f3;
        }

        .warning-msg {
            background: rgba(255, 152, 0, 0.15);
            color: var(--warning);
            border: 1px solid var(--warning);
        }

        .no-teachers {
            background: #f0f0f0;
            border: 1px dashed #bdbdbd;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            color: var(--text-light);
        }

        .no-teachers i {
            font-size: 3rem;
            color: #bdbdbd;
            margin-bottom: 15px;
        }

        /* Success Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .success-modal {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            transform: translateY(50px);
            opacity: 0;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        
        .modal-overlay.active .success-modal {
            transform: translateY(0);
            opacity: 1;
        }
        
        .modal-icon {
            font-size: 80px;
            color: var(--success);
            margin-bottom: 20px;
            animation: bounce 1s ease;
        }
        
        .modal-title {
            font-size: 2rem;
            color: var(--primary-dark);
            margin-bottom: 15px;
        }
        
        .modal-content {
            font-size: 1.1rem;
            color: var(--text);
            margin-bottom: 25px;
            line-height: 1.6;
        }
        
        .countdown {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--primary);
            margin-top: 15px;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-30px);}
            60% {transform: translateY(-15px);}
        }
        
        @keyframes confettiFall {
            0% { 
                transform: translateY(0) rotate(0deg); 
                opacity: 1;
            }
            100% { 
                transform: translateY(100vh) rotate(720deg); 
                opacity: 0;
            }
        }
        
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #ff4081;
            border-radius: 50%;
            opacity: 0;
            z-index: -1;
        }

        @media (max-width: 768px) {
            .container {
                margin: 15px;
            }
            
            .header {
                padding: 25px 15px;
            }
            
            .header h1 {
                font-size: 1.8rem;
            }
            
            .form-container {
                padding: 20px;
            }
            
            .section {
                padding: 20px;
            }
            
            .section-title {
                font-size: 1.3rem;
            }
            
            .modal-icon {
                font-size: 60px;
            }
            
            .modal-title {
                font-size: 1.7rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .header h1 {
                font-size: 1.5rem;
            }
            
            .header p {
                font-size: 0.95rem;
            }
            
            .section-title {
                font-size: 1.2rem;
            }
            
            .section-title i {
                width: 30px;
                height: 30px;
                font-size: 0.9rem;
            }
            
            .teacher-card {
                padding: 15px;
            }
            
            .stars label {
                font-size: 24px;
            }
            
            .btn i {
                margin-right: 8px;
            }
            
            .btn {
                padding: 12px 20px;
                font-size: 0.9rem;
            }
            
            .input-control {
                padding: 12px 14px;
            }
            
            .modal-title {
                font-size: 1.5rem;
            }
            
            .modal-content {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Success Modal -->
    <div class="modal-overlay" id="successModal">
        <div class="success-modal">
            <div class="modal-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 class="modal-title">Feedback Submitted!</h2>
            <p class="modal-content">Thank you for your valuable feedback. Your input helps us improve the learning experience.</p>
            <div class="countdown" id="countdown">Redirecting in 3 seconds...</div>
        </div>
    </div>

    <div class="container">
        <div class="header">
            <h1><i class="fas fa-comment-alt"></i> Student Feedback Portal</h1>
            <p>Share your valuable feedback to help us improve the teaching experience</p>
        </div>

        <div class="form-container">
            <div class="section">
                <h2 class="section-title"><i class="fas fa-user-graduate"></i> Student Information</h2>
                
                <div class="form-group">
                    <label for="attendance_id" class="required">Attendance ID</label>
                    <input type="number" id="attendance_id" class="input-control" placeholder="Enter your attendance ID" />
                </div>
                
                <button id="fetchInfoBtn" class="btn btn-primary btn-block">
                    <i class="fas fa-search"></i> Fetch Student Information
                </button>
                
                <div id="statusMessage" class="status-message"></div>
            </div>

            <form method="POST" id="feedbackForm" class="hidden">
                <div class="section">
                    <h2 class="section-title"><i class="fas fa-info-circle"></i> Student Details</h2>
                    
                    <input type="hidden" id="student_id" name="student_id" />
                    <input type="hidden" id="attendance_id_hidden" name="attendance_id" />
                    
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" id="name" class="input-control" readonly />
                    </div>
                    
                    <div class="form-group">
                        <label>Program</label>
                        <input type="text" id="program" class="input-control" readonly />
                    </div>
                    
                    <div class="form-group">
                        <label>Trade</label>
                        <input type="text" id="trade" class="input-control" readonly />
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
            <p>Made with <span class="heart">❤️</span> CSA 2024-25</p>
        </div>
    </div>

    <script>
        // Function to show status message below fetch button
        function showStatus(message, type) {
            const statusEl = document.getElementById('statusMessage');
            statusEl.textContent = message;
            statusEl.className = `status-message ${type} show`;
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                statusEl.classList.remove('show');
            }, 5000);
        }
        
        // Function to show success modal
        function showSuccessModal() {
            const modal = document.getElementById('successModal');
            document.body.classList.add('blurred');
            modal.classList.add('active');
            
            // Create confetti effect
            createConfetti();
            
            // Start countdown
            let seconds = 3;
            const countdownEl = document.getElementById('countdown');
            
            const countdown = setInterval(() => {
                seconds--;
                countdownEl.textContent = `Redirecting in ${seconds} second${seconds !== 1 ? 's' : ''}...`;
                
                if (seconds <= 0) {
                    clearInterval(countdown);
                    resetForm();
                }
            }, 1000);
        }
        
        // Function to reset form and return to main page
        function resetForm() {
            const modal = document.getElementById('successModal');
            document.body.classList.remove('blurred');
            modal.classList.remove('active');
            
            // Reset form and show initial state
            document.getElementById('feedbackForm').classList.add('hidden');
            document.getElementById('feedbackForm').reset();
            document.getElementById('attendance_id').value = '';
            document.getElementById('teacherCardsContainer').innerHTML = '';
            
            // Reset countdown text
            document.getElementById('countdown').textContent = 'Redirecting in 3 seconds...';
        }
        
        // Create confetti effect
        function createConfetti() {
            const colors = ['#ff4081', '#7e57c2', '#4caf50', '#ff9800', '#03a9f4'];
            const container = document.body;
            
            for (let i = 0; i < 150; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.width = Math.random() * 10 + 5 + 'px';
                confetti.style.height = confetti.style.width;
                confetti.style.animation = `confettiFall ${Math.random() * 3 + 2}s linear forwards`;
                container.appendChild(confetti);
                
                // Remove confetti after animation completes
                setTimeout(() => {
                    if (confetti.parentNode) {
                        confetti.remove();
                    }
                }, 5000);
            }
        }

        // Fetch student information
        document.getElementById('fetchInfoBtn').addEventListener('click', function() {
            const attendanceId = document.getElementById('attendance_id').value.trim();
            
            if (!attendanceId) {
                showStatus('Please enter Attendance ID', 'error-msg');
                return;
            }
            
            // Show loading state
            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Fetching...';
            btn.disabled = true;
            
            // Create form data
            const formData = new FormData();
            formData.append('attendance_id', attendanceId);
            
            // Send request to server
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const student = data.data;
                    
                    // Fill student info
                    document.getElementById('student_id').value = student.student_id;
                    document.getElementById('attendance_id_hidden').value = student.attendance_id;
                    document.getElementById('name').value = student.name;
                    document.getElementById('program').value = student.program;
                    document.getElementById('trade').value = student.trade;
                    
                    const container = document.getElementById('teacherCardsContainer');
                    container.innerHTML = '';
                    
                    if (student.teacher_sub_trade_rows.length === 0) {
                        container.innerHTML = `
                            <div class="no-teachers">
                                <i class="fas fa-user-graduate"></i>
                                <h3>No Teachers Found</h3>
                                <p>There are currently no teachers assigned to this trade.</p>
                            </div>
                        `;
                    } else {
                        student.teacher_sub_trade_rows.forEach((teacher, index) => {
                            const card = document.createElement('div');
                            card.className = 'teacher-card';
                            
                            card.innerHTML = `
                                <input type="hidden" name="teacher_id[]" value="${teacher.teacher_id}">
                                <input type="hidden" name="trade_id[]" value="${teacher.trade_id}">
                                <input type="hidden" name="subject_id[]" value="${teacher.subject_id}">
                                <input type="hidden" name="program[]" value="${teacher.program}">
                                
                                <div class="teacher-name">${teacher.teacher_name}</div>
                                <div class="subject-name">Subject: ${teacher.subject_name}</div>
                                
                                <label>Rating <span style="color:var(--accent)">*</span></label>
                                <div class="stars" data-index="${index}">
                                    ${[5, 4, 3, 2, 1].map(star => `
                                        <input type="radio" name="rating[${index}]" value="${star}" id="star${star}_${index}" required>
                                        <label for="star${star}_${index}">&#9733;</label>
                                    `).join('')}
                                </div>
                                
                                <div class="form-group">
                                    <label>Remarks <span style="color:var(--accent)">*</span></label>
                                    <textarea name="remarks[]" class="input-control" rows="3" placeholder="Write your feedback about this teacher..." required></textarea>
                                </div>
                            `;
                            
                            container.appendChild(card);
                        });
                    }
                    
                    document.getElementById('feedbackForm').classList.remove('hidden');
                    showStatus('Student information loaded successfully', 'success-msg');
                } else if (data.status === 'exists') {
                    showStatus(data.message, 'info-msg');
                } else if (data.status === 'error') {
                    showStatus(data.message, 'error-msg');
                } else {
                    showStatus('An unexpected error occurred', 'error-msg');
                }
            })
            .catch(() => {
                showStatus('Something went wrong! Please try again.', 'error-msg');
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });

        // Handle form submission
        document.getElementById('feedbackForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Check if there are teachers to submit feedback for
            const teacherCards = document.querySelectorAll('.teacher-card');
            if (teacherCards.length === 0) {
                showStatus('No teachers available to submit feedback', 'warning-msg');
                return;
            }
            
            // Check if all ratings and remarks are filled
            let allFilled = true;
            document.querySelectorAll('.stars').forEach((stars, index) => {
                const ratingSelected = stars.querySelector('input:checked');
                const remarks = document.querySelectorAll('textarea[name="remarks[]"]')[index];
                
                if (!ratingSelected || !remarks.value.trim()) {
                    allFilled = false;
                }
            });
            
            if (!allFilled) {
                showStatus('Please fill all ratings and remarks for teachers', 'error-msg');
                return;
            }
            
            // Show submitting status
            showStatus('Submitting feedback...', 'info-msg');
            
            // Submit the form
            const formData = new FormData(this);
            formData.append('submit_feedback', 'true');
            
            // Send request to server
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // The response will be handled by the PHP script which will output JavaScript
                // We need to execute that JavaScript
                const script = document.createElement('script');
                script.textContent = data;
                document.body.appendChild(script);
            })
            .catch(() => {
                showStatus('Error submitting feedback', 'error-msg');
            });
        });

        // Initialize star ratings
        document.addEventListener('click', function(e) {
            if (e.target.matches('.stars label')) {
                const index = e.target.closest('.stars').dataset.index;
                const starValue = e.target.htmlFor.split('_')[0].replace('star', '');
                
                // Highlight selected stars
                const stars = e.target.closest('.stars');
                const labels = stars.querySelectorAll('label');
                
                labels.forEach((label, i) => {
                    if (i >= 5 - starValue) {
                        label.style.color = '#ffc107';
                    } else {
                        label.style.color = '#ddd';
                    }
                });
            }
        });
    </script>
</body>
</html>