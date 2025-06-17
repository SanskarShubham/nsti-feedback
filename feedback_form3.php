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
        }
    </style>
</head>
<body>
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
        // Function to show status message
        function showStatus(message, type) {
            const statusEl = document.getElementById('statusMessage');
            statusEl.textContent = message;
            statusEl.className = `status-message ${type} show`;
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                statusEl.classList.remove('show');
            }, 5000);
        }

        // Simulate fetching student info
        document.getElementById('fetchInfoBtn').addEventListener('click', function() {
            const attendanceId = document.getElementById('attendance_id').value;
            
            if (!attendanceId) {
                showStatus('Please enter Attendance ID', 'error-msg');
                return;
            }
            
            // Show loading state
            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Fetching...';
            btn.disabled = true;
            
            // Simulate API call delay
            setTimeout(() => {
                // Simulate different responses based on input
                if (attendanceId === '123') {
                    // Simulate "feedback already submitted"
                    showStatus('Feedback already submitted for this student.', 'info-msg');
                } else if (attendanceId === '456') {
                    // Simulate "student not found"
                    showStatus('Student details not found.', 'error-msg');
                } else if (attendanceId === '789') {
                    // Simulate "no teachers found"
                    document.getElementById('feedbackForm').classList.remove('hidden');
                    document.getElementById('name').value = 'Alex Johnson';
                    document.getElementById('program').value = 'Computer Science';
                    document.getElementById('trade').value = 'Software Development';
                    
                    const container = document.getElementById('teacherCardsContainer');
                    container.innerHTML = `
                        <div class="no-teachers">
                            <i class="fas fa-user-graduate"></i>
                            <h3>No Teachers Found</h3>
                            <p>There are currently no teachers assigned to this trade.</p>
                        </div>
                    `;
                    
                    showStatus('Student information loaded successfully', 'success-msg');
                } else {
                    // Simulate successful fetch with teachers
                    document.getElementById('feedbackForm').classList.remove('hidden');
                    document.getElementById('name').value = 'Sarah Williams';
                    document.getElementById('program').value = 'Information Technology';
                    document.getElementById('trade').value = 'Web Development';
                    
                    const container = document.getElementById('teacherCardsContainer');
                    container.innerHTML = '';
                    
                    // Add teacher cards
                    const teachers = [
                        { name: 'Dr. Robert Chen', subject: 'Advanced Algorithms' },
                        { name: 'Prof. Emily Davis', subject: 'Web Application Security' },
                        { name: 'Dr. James Wilson', subject: 'Cloud Computing' }
                    ];
                    
                    teachers.forEach((teacher, index) => {
                        const card = document.createElement('div');
                        card.className = 'teacher-card';
                        
                        card.innerHTML = `
                            <input type="hidden" name="teacher_id[]" value="${index + 1}">
                            <input type="hidden" name="trade_id[]" value="1">
                            <input type="hidden" name="subject_id[]" value="${index + 101}">
                            <input type="hidden" name="program[]" value="Information Technology">
                            
                            <div class="teacher-name">${teacher.name}</div>
                            <div class="subject-name">Subject: ${teacher.subject}</div>
                            
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
                    
                    showStatus('Student information loaded successfully', 'success-msg');
                }
                
                // Reset button
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 1500);
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
            
            // Simulate form submission
            showStatus('Submitting feedback...', 'info-msg');
            
            setTimeout(() => {
                // Show success notification
                showStatus('Feedback submitted successfully!', 'success-msg');
                
                // Reset form after success
                setTimeout(() => {
                    document.getElementById('feedbackForm').reset();
                    document.getElementById('feedbackForm').classList.add('hidden');
                    document.getElementById('attendance_id').value = '';
                }, 2000);
            }, 2000);
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