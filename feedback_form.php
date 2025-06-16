<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['attendance_id'])) {
  $attendance_id = mysqli_real_escape_string($conn, $_POST['attendance_id']);
  $query = "SELECT * FROM students WHERE attendance_id = '$attendance_id'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) === 1) {
    $student = mysqli_fetch_assoc($result);
    // echo "<pre>";

    // Get Trade ID
    $trade_name = $student['trade'];
    $trade_query = "SELECT trade_id FROM trade WHERE trade_name = '$trade_name' LIMIT 1";
    $trade_result = mysqli_query($conn, $trade_query);
    $trade_row = mysqli_fetch_assoc($trade_result);
    $trade_id = $trade_row['trade_id'];

    $teacher_sub_trade_query = "SELECT tst.id, tst.teacher_id, tst.trade_id, tst.subject_id, tst.program, tst.created_at, tst.created_by, tst.updated_at, tst.updated_by, tst.status,
                                    t.name AS teacher_name, tr.trade_name, s.name AS subject_name
                                FROM teacher_subject_trade tst
                                LEFT JOIN teachers t ON tst.teacher_id = t.teacher_id
                                LEFT JOIN trade tr ON tst.trade_id = tr.trade_id
                                LEFT JOIN subject s ON tst.subject_id = s.subject_id
                                WHERE tst.trade_id = $trade_id";
    $teacher_sub_trade_result = mysqli_query($conn, $teacher_sub_trade_query);
    $teacher_sub_trade_rows = mysqli_fetch_all($teacher_sub_trade_result, MYSQLI_ASSOC);
    // print_r($teacher_sub_trade_rows);
    // print_r($student);
    // print_r($trade_id);
    // exit;

    echo json_encode([
      'status' => 'success',
      'data' => [
        'name' => $student['name'],
        'program' => $student['program'],
        'trade' => $student['trade'],
        'student_id' => $student['id'],
        'teacher_sub_trade_rows' => $teacher_sub_trade_rows
      ]
    ]);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Student not found.']);
  }
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_feedback'])) {
  $student_id = $_POST['student_id'];
  $teacher_name = $_POST['teacher_name'];
  $rating = $_POST['rating'];
  $remarks = $_POST['remarks'];

  $teacher_id = 1; // Replace with real teacher_id
  $subject_id = 1; // Replace with real subject_id

  $insert = "INSERT INTO feedback (student_id, teacher_id, subject_id, rating, remarks)
               VALUES ('$student_id', '$teacher_id', '$subject_id', '$rating', '$remarks')";
  mysqli_query($conn, $insert);

  echo "<script>alert('Feedback submitted successfully!'); window.location.href='';</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Student Feedback</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
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
    <input type="text" id="attendance_id" placeholder="Enter Attendance ID">
    <button id="fetchInfoBtn" onclick="fetchStudent()">Fetch Info</button>

    <form method="POST" id="feedbackForm" class="hidden">
      <input type="hidden" id="student_id" name="student_id">

      <label>Name:</label>
      <input type="text" id="name" readonly>

      <label>Program:</label>
      <input type="text" id="program" readonly>

      <label>Trade:</label>
      <input type="text" id="trade" readonly>
      <div id="teacherCardsContainer"></div>
      <!-- <div id="teacherInfoCard" class="card">
        <label>Teacher Name (Trade Theory):</label>
        <input type="text" name="teacher_name" value="(Auto-filled Trade Theory Teacher)" readonly>

        <label>Rating:</label>
        <div class="stars">
          <input type="radio" name="rating" value="5" id="star5"><label for="star5">&#9733;</label>
          <input type="radio" name="rating" value="4" id="star4"><label for="star4">&#9733;</label>
          <input type="radio" name="rating" value="3" id="star3"><label for="star3">&#9733;</label>
          <input type="radio" name="rating" value="2" id="star2"><label for="star2">&#9733;</label>
          <input type="radio" name="rating" value="1" id="star1"><label for="star1">&#9733;</label>
        </div>

        <label>Remarks:</label>
        <textarea name="remarks" rows="3" placeholder="Write your remarks..."></textarea>
      </div> -->

      <button type="submit" name="submit_feedback">Submit Feedback</button>
    </form>
  </div>
  <script>
    document.getElementById('fetchInfoBtn').addEventListener('click', function() {
      const attendanceId = document.getElementById('attendance_id').value;

      fetch('', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'attendance_id=' + encodeURIComponent(attendanceId)
        })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success') {
            const student = data.data;
            const teacherList = data.data.teacher_sub_trade_rows;
            console.log('Fetched teacher list:', teacherList);

            // Fill student fields
            console.log('Student data:', student);
            document.getElementById('student_id').value = student.student_id;
            document.getElementById('name').value = student.name;
            document.getElementById('program').value = student.program;
            document.getElementById('trade').value = student.trade;

            // Empty previous cards
            const container = document.getElementById('teacherCardsContainer');
            container.innerHTML = '';

            // Generate a card for each teacher
            teacherList.forEach((teacher, index) => {
              console.log(`Generating card for teacher: ${teacher.teacher_name}, subject: ${teacher.subject_name}`);
              const card = document.createElement('div');
              card.className = 'card';
              card.innerHTML = `
          <label>Teacher Name (${teacher.subject_name}):</label>
          <input type="text" name="teacher_name[]" value="${teacher.teacher_name}" readonly>

          <label>Rating:</label>
          <div class="stars">
            ${[5,4,3,2,1].map(star => `
              <input type="radio" name="rating_${index}" value="${star}" id="star${star}_${index}">
              <label for="star${star}_${index}">&#9733;</label>
            `).join('')}
          </div>

          <label>Remarks:</label>
          <textarea name="remarks[]" rows="3" placeholder="Write your remarks..."></textarea>
        `;
              container.appendChild(card);
            });

            document.getElementById('feedbackForm').classList.remove('hidden');
          } else {
            console.warn('Error fetching student data:', data.message || 'Student not found.');
            alert(data.message || 'Student not found.');
          }
        })
        .catch(err => {
          console.error('Error:', err);
          alert('Something went wrong!');
        });
    });
  </script>



</body>

</html>