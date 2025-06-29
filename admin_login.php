<?php
session_start();

$error = "";

// Redirect if already logged in
if (isset($_SESSION['admin_data'])) {
    header("Location: dashboard.php");
    exit();
}

// Login check
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "nsti_feedback_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM teachers WHERE email = ? AND status = 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_data'] = $user;
            if ($user['designation'] == 'admin') {
                header("Location: dashboard.php");
                exit();
            } else {
                header("Location: teachers_dashboard.php");
                exit();
            }
        } else {
            $error = "❌ Invalid email or password.";
        }
    } else {
        $error = "❌ Invalid email or password.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NSTI Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Modern CSS UI */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #e0aaff, #c77dff);
            overflow: hidden;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.98);
            padding: 2rem;
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px rgba(155, 89, 182, 0.1);
            width: 90%;
            max-width: 500px;
            transform: translateY(20px);
            opacity: 0;
            animation: formEntrance 0.6s cubic-bezier(0.23, 1, 0.32, 1) forwards;
            border: 1px solid rgba(199, 125, 255, 0.3);
        }

        @keyframes formEntrance {
            to { opacity: 1; transform: translateY(0); }
        }

        h1 {
            color: #7b2cbf;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            text-align: center;
            font-weight: 600;
            position: relative;
        }

        h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #7b2cbf, #9d4edd);
            border-radius: 2px;
        }

        .form-group {
            margin-bottom: 1.2rem;
            position: relative;
            opacity: 0;
            transform: translateY(15px);
            animation: fieldEntrance 0.4s ease-out forwards;
        }

        @keyframes fieldEntrance {
            to { opacity: 1; transform: translateY(0); }
        }

        label {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #b39ddb;
            font-size: 0.9rem;
            pointer-events: none;
            transition: all 0.3s ease;
            background: white;
            padding: 0 4px;
        }

        input {
            width: 100%;
            padding: 0.9rem;
            border: 2px solid #e0aaff;
            border-radius: 0.75rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: transparent;
            color: #5e35b1;
        }

        input:focus {
            outline: none;
            border-color: #7b2cbf;
            box-shadow: 0 4px 6px -1px rgba(155, 89, 182, 0.1);
        }

        input:hover {
            border-color: #b388ff;
        }

        input:focus ~ label,
        input:valid ~ label {
            top: 0;
            font-size: 0.75rem;
            color: #7b2cbf;
        }

        .submit-btn {
            background: linear-gradient(135deg, #9d4edd, #7b2cbf);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 0.75rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 12px -3px rgba(123, 44, 191, 0.2);
        }

        .submit-btn::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.15), transparent);
            transform: rotate(45deg);
            animation: btnShine 4s infinite;
        }

        @keyframes btnShine {
            0% { transform: translateX(-100%) rotate(45deg); }
            100% { transform: translateX(100%) rotate(45deg); }
        }

        .alert-danger {
            background: rgba(255, 0, 0, 0.1);
            color: #d32f2f;
            border-left: 4px solid #f44336;
            border-radius: 8px;
            padding: 15px;
            margin-top: 1rem;
            text-align: center;
        }

        .form-group:nth-child(1) { animation-delay: 0.2s; }
        .form-group:nth-child(2) { animation-delay: 0.3s; }

        a.btn {
            display: inline-block;
            margin-top: 1rem;
            text-align: center;
            padding: 0.75rem;
            border-radius: 0.75rem;
            font-weight: 500;
            font-size: 1rem;
            background: white;
            color: #7b2cbf;
            border: 1px solid #e0aaff;
            text-decoration: none;
            width: 100%;
            transition: 0.3s;
        }

        a.btn:hover {
            background: #f3e5f5;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

<div class="form-container">
    <h1>Admin Login</h1>
    <form action="admin_login.php" method="post">
        <div class="form-group">
            <input type="email" name="email" required>
            <label>Email</label>
        </div>
        <div class="form-group">
            <input type="password" name="password" required>
            <label>Password</label>
        </div>
        <button type="submit" class="submit-btn">Sign In</button>
        <a href="index.php" class="btn">Back to Home</a>

        <?php if (!empty($error)): ?>
            <div class="alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
    </form>
</div>

</body>
</html>
