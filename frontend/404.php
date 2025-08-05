<?php
// Optional: Start session if needed
// session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 - Page Not Found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom Green Theme -->
    <style>
        :root {
            --primary-color: #28a745; /* Bootstrap success green */
            --primary-hover: #218838;
            --bg-color: #f4fef6;
        }

        body {
            background-color: var(--bg-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .error-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .error-box {
            text-align: center;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .error-box img {
            max-width: 300px;
            margin-bottom: 20px;
        }

        .error-code {
            font-size: 90px;
            font-weight: 800;
            color: var(--primary-color);
        }

        .btn-green {
            background-color: var(--primary-color);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 16px;
            color: white;
            transition: background-color 0.3s;
        }

        .btn-green:hover {
            background-color: var(--primary-hover);
        }

        .text-muted {
            color: #6c757d !important;
        }
    </style>
</head>
<body>

<div class="container error-container">
    <div class="error-box">
        <!-- Cute vector image -->
        <img src="https://undraw.co/api/illustrations/59c1f9b1-5e8a-4d55-a03e-6f573b70e0ff" alt="404 vector">

        <!-- 404 Message -->
        <div class="error-code">404</div>
        <h2 class="mb-3">Oops! Page Not Found</h2>
        <p class="text-muted mb-4">
            The page you're looking for doesn't exist or might have been moved.<br>
            Let’s get you back to the homepage!
        </p>

        <!-- Green Home Button -->
        <a href="index.php" class="btn btn-green">
            <i class="bi bi-house-door-fill me-2"></i>Go to Home
        </a>
    </div>
</div>

<!-- Bootstrap JS (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
