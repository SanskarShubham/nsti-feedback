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
    $password = $_POST['password']; // plain password from form
    

    $stmt = $conn->prepare("SELECT * FROM teachers WHERE email = ? and status = 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check hashed password
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_data'] = $user;
            if ($user['designation'] == 'admin') {
                header("Location: dashboard.php");
                exit();
            }else{
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
<html class="h-100" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>NSTI - Admin Login</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="h-100">

    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>

    <div class="login-form-bg h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-xl-6">
                    <div class="form-input-content">
                        <div class="card login-form mb-0">
                            <div class="card-body pt-5">
                                <a class="text-center" href="dashboard.php">
                                    <h4>NSTI Feedback Admin Panel</h4>
                                </a>

                                <form class="mt-5 mb-5 login-input" action="admin_login.php" method="post">
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                                    </div>
                                    <button class="btn login-form__btn submit w-100">Sign In</button>
                                </form>
                                <a href="index.php" style="margin-top:-20px;"><button class="btn login-form__btn submit w-100 ">Home </button></a>

                                <?php if (!empty($error)): ?>
                                    <div class="alert alert-danger text-center"><?= $error ?></div>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="plugins/common/common.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/gleek.js"></script>
    <script src="js/styleSwitcher.js"></script>
</body>
</html>

