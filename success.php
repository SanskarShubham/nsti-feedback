<?php
// Start the session to access session variables
session_start();

// Set headers to prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Get student name from session or use default
$studentName = $_SESSION['student_name'] ?? 'Student';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Submitted Successfully</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="feedback_form.css">
    <style>
        /* Success Page - Fixed and Enhanced */
        body {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
            color: var(--text);
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .success-card {
            width: 100%;
            max-width: 500px;
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
            text-align: center;
            transition: var(--transition);
        }

        .success-card:hover {
            box-shadow: var(--shadow-hover);
        }

        .success-header {
            background: linear-gradient(to right, var(--primary-dark), var(--primary));
            color: white;
            padding: 25px;
            position: relative;
            overflow: hidden;
        }

        .success-header::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            transform: rotate(30deg);
        }

        .success-header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            margin: 0;
            position: relative;
            z-index: 2;
        }

        .success-body {
            padding: 40px 30px;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .success-icon {
            font-size: 72px;
            color: var(--success);
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .success-title {
            font-size: 1.5rem;
            color: var(--primary-dark);
            margin-bottom: 15px;
            font-weight: 600;
        }

        .success-message {
            color: var(--text-light);
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 8px;
            background: var(--primary);
            color: white;
            border: none;
            text-decoration: none;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 8px rgba(126, 87, 194, 0.2);
            margin: 10px 0;
        }

        .btn-home i {
            margin-right: 10px;
            font-size: 1rem;
        }

        .btn-home:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(126, 87, 194, 0.3);
        }

        .redirect-notice {
            margin-top: 25px;
            font-size: 0.95rem;
            color: var(--text-light);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .redirect-notice i {
            color: var(--primary);
            animation: spin 1.5s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Mobile Responsiveness */
        @media (max-width: 600px) {
            body {
                padding: 15px;
            }
            
            .success-card {
                max-width: 100%;
                border-radius: 12px;
            }
            
            .success-header {
                padding: 20px;
            }
            
            .success-header h1 {
                font-size: 1.5rem;
            }
            
            .success-body {
                padding: 30px 20px;
            }
            
            .success-icon {
                font-size: 60px;
            }
            
            .success-title {
                font-size: 1.3rem;
            }
            
            .success-message {
                font-size: 1rem;
            }
            
            .btn-home {
                padding: 10px 20px;
                font-size: 0.95rem;
            }
            
            .redirect-notice {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 400px) {
            .success-header h1 {
                font-size: 1.3rem;
            }
            
            .success-title {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="success-card">
        <div class="success-header">
            <h1>Feedback Received</h1>
        </div>
        
        <div class="success-body">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 class="success-title">Thank You, <?php echo htmlspecialchars($studentName); ?>!</h2>
            <p class="success-message">Your feedback has been successfully submitted. We appreciate you helping us improve our teaching quality.</p>
            
            <a href="index.php" class="btn-home">
                <i class="fas fa-home"></i> Return to Home
            </a>
            
            <div class="redirect-notice">
                <i class="fas fa-spinner"></i>
                <span>Redirecting in <span id="countdown">5</span> seconds...</span>
            </div>
        </div>
    </div>

    <script>
        // Countdown and redirect
        let seconds = 10;
        const countdownElement = document.getElementById('countdown');
        
        const countdown = setInterval(function() {
            seconds--;
            countdownElement.textContent = seconds;
            
            if (seconds <= 0) {
                clearInterval(countdown);
                window.location.href = "index.php";
            }
        }, 1000);
    </script>
</body>
</html>