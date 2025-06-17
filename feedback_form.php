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
    echo json_encode(['status' => 'error', 'message' => 'Student not found.']);
  }
  exit;
}

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_feedback'])) {
  $student_id = intval($_POST['student_id']);
  $attendance_id = mysqli_real_escape_string($conn, $_POST['attendance_id']);

  // Check if feedback already submitted - server side validation
  if (feedbackExists($conn, $attendance_id)) {
    echo "<script>alert('Feedback already submitted for this student.'); window.location.href='';</script>";
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
    echo "<script>alert('Please fill all mandatory fields:\\n$error_str'); window.history.back();</script>";
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
    echo "<script>alert('Feedback submitted successfully!'); window.location.href='';</script>";
  } else {
    $error_str = implode(", ", $insert_errors);
    echo "<script>alert('Error submitting feedback: $error_str'); window.location.href='';</script>";
  }
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Student Feedback</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    /* Same CSS as before, no change */
    * {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #f5f0ff;
      margin: 0;
      padding: 20px;
    }

    .container {
      max-width: 600px;
      background: #fff;
      margin: auto;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(106, 13, 173, 0.2);
    }

    h2 {
      color: #6a0dad;
      text-align: center;
    }

    label {
      display: block;
      margin-top: 12px;
      color: #6a0dad;
      font-weight: bold;
    }

    input,
    select,
    textarea,
    button {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      background-color: #6a0dad;
      color: white;
      font-weight: bold;
      border: none;
      cursor: pointer;
      margin-top: 20px;
    }

    button:hover {
      background-color: #5a01a7;
    }

    .card {
      background-color: #f0e6ff;
      border-radius: 8px;
      padding: 15px;
      margin-top: 25px;
      box-shadow: 0 0 10px rgba(128, 0, 128, 0.1);
    }

    .stars {
      direction: rtl;
      display: flex;
      justify-content: flex-start;
      gap: 5px;
      margin-top: 8px;
    }

    .stars input {
      display: none;
    }

    .stars label {
      font-size: 24px;
      color: #ccc;
      cursor: pointer;
    }

    .stars input:checked~label,
    .stars label:hover,
    .stars label:hover~label {
      color: #ffc107;
    }

    .hidden {
      display: none;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Student Feedback Form</h2>

    <label for="attendance_id">Attendance ID:</label>
    <input type="number" id="attendance_id" placeholder="Enter Attendance ID" />
    <button id="fetchInfoBtn">Fetch Info</button>

    <form method="POST" id="feedbackForm" class="hidden" onsubmit="return validateForm()">
      <input type="hidden" id="student_id" name="student_id" />
      <input type="hidden" id="attendance_id_hidden" name="attendance_id" />

      <label>Name:</label>
      <input type="text" id="name" readonly />

      <label>Program:</label>
      <input type="text" id="program" readonly />

      <label>Trade:</label>
      <input type="text" id="trade" readonly />

      <div id="teacherCardsContainer"></div>

      <button type="submit" name="submit_feedback">Submit Feedback</button>
    </form>
  </div>

  <script>
    document.getElementById('fetchInfoBtn').addEventListener('click', function () {
      const attendanceId = parseInt(document.getElementById('attendance_id').value.trim());
      if (!attendanceId) {
        alert('Please enter Attendance ID');
        return;
      }

      fetch('', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'attendance_id=' + encodeURIComponent(attendanceId),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === 'success') {
            const student = data.data;
            const teacherList = student.teacher_sub_trade_rows;

            // Fill student info
            document.getElementById('student_id').value = student.student_id;
            document.getElementById('attendance_id_hidden').value = student.attendance_id;
            document.getElementById('name').value = student.name;
            document.getElementById('program').value = student.program;
            document.getElementById('trade').value = student.trade;

            const container = document.getElementById('teacherCardsContainer');
            container.innerHTML = '';

            teacherList.forEach((teacher, index) => {
              const card = document.createElement('div');
              card.className = 'card';

              card.innerHTML = `
                <input type="hidden" name="teacher_id[]" value="${teacher.teacher_id}">
                <input type="hidden" name="trade_id[]" value="${teacher.trade_id}">
                <input type="hidden" name="subject_id[]" value="${teacher.subject_id}">
                <input type="hidden" name="program[]" value="${teacher.program}">

                <label>Teacher Name (${teacher.subject_name}):</label>
                <input type="text" name="teacher_name[]" value="${teacher.teacher_name}" readonly>

                <label>Rating: <span style="color:red">*</span></label>
                <div class="stars" data-index="${index}">
                  ${[5, 4, 3, 2, 1]
                    .map(
                      (star) => `
                    <input type="radio" name="rating[${index}]" value="${star}" id="star${star}_${index}">
                    <label for="star${star}_${index}">&#9733;</label>
                  `
                    )
                    .join('')}
                </div>

                <label>Remarks: <span style="color:red">*</span></label>
                <textarea name="remarks[]" rows="3" placeholder="Write your remarks..." required></textarea>
              `;

              container.appendChild(card);
            });

            document.getElementById('feedbackForm').classList.remove('hidden');
          } else if (data.status === 'exists') {
            alert(data.message);
            document.getElementById('feedbackForm').classList.add('hidden');
          } else {
            alert(data.message || 'Student not found.');
            document.getElementById('feedbackForm').classList.add('hidden');
          }
        })
        .catch(() => {
          alert('Something went wrong!');
          document.getElementById('feedbackForm').classList.add('hidden');
        });
    });

    // Client-side form validation before submit
    function validateForm() {
      const cards = document.querySelectorAll('#teacherCardsContainer .card');
      for (let i = 0; i < cards.length; i++) {
        // Check rating radio group
        const ratingInputs = cards[i].querySelectorAll(`input[name="rating[${i}]"]`);
        let ratingChecked = false;
        for (const r of ratingInputs) {
          if (r.checked) {
            ratingChecked = true;
            break;
          }
        }
        if (!ratingChecked) {
          alert(`Please select rating for teacher #${i + 1}`);
          return false;
        }

        // Check remarks textarea
        const remarks = cards[i].querySelector('textarea[name="remarks[]"]');
        if (!remarks.value.trim()) {
          alert(`Please enter remarks for teacher #${i + 1}`);
          return false;
        }
      }
      return true;
    }
  </script>
  
  <script>
  </script>
</body>

</html>
