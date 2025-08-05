<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('Asia/Kolkata');
if (!isset($_SESSION['admin_data'])) {
    header("Location: admin_login.php");
    exit();
}
// print_r($_SESSION['admin_data']);
require_once 'connection.php';
$conn->query("SET time_zone = '+05:30'");


// Set timeout duration in seconds (30 minutes = 1800 seconds)
$timeout_duration = 1800; 

// Check for last activity
if (isset($_SESSION['LAST_ACTIVITY']) && 
    (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    
    // Session expired
    session_unset();     // Unset all session variables
    session_destroy();   // Destroy the session

    // Optional: redirect to login or timeout page
    header("Location: admin_login.php");
    exit;
}

// Update last activity time
$_SESSION['LAST_ACTIVITY'] = time();
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <!-- theme meta -->
    <meta name="theme-name" content="quixlab" />

    <title>NSTI HOWRAH Admin Panel</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <!-- Pignose Calender -->
    <link href="./plugins/pg-calendar/css/pignose.calendar.min.css" rel="stylesheet">
    <!-- Chartist -->
    <link rel="stylesheet" href="./plugins/chartist/css/chartist.min.css">
    <link rel="stylesheet" href="./plugins/chartist-plugin-tooltips/css/chartist-plugin-tooltip.css">
    <!-- Custom Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <!-- <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div> -->
    <!--*******************
        Preloader end
    ********************-->


    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <div class="brand-logo">
                <a href="dashboard.php">
                    <b class="logo-abbr"><img src="images/logo.png" alt=""> </b>
                    <span class="logo-compact"><img src="./images/logo-compact.png" alt=""></span>
                    <span class="brand-title">
                        <!-- <img src="images/logo-text.png" alt=""> -->
                        <pre style="color: white; font-size: 22px; font-weight: bold; font-family: 'Times New Roman', Times, serif; letter-spacing: 1px;  "><b>NSTI HOWRAH</b></pre>
                        </p>
                    </span>
                </a>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content clearfix">

                <div class="nav-control">
                    <div class="hamburger">
                        <span class="toggle-icon"><i class="icon-menu"></i></span>
                    </div>

                </div>

                <div class="header-right">

                    <ul class="clearfix">



                        <?php
                        // print_r($_SESSION['admin_data']);exit;
                        $query = "select dp_file_path from teachers where teacher_id=" . $_SESSION['admin_data']['teacher_id'];
                        $result = $conn->query($query);
                        $dp = $result->fetch_assoc()['dp_file_path'];

                        ?>


                        <li class="icons dropdown">
                            <div class="user-img c-pointer position-relative" data-toggle="dropdown">
                                <span class="activity active"></span>
                                <img src="<?php echo $dp ?? "images/user/form-user.png"; ?>" height="40" width="40" alt="">
                            </div>
                            <div class="drop-down dropdown-profile animated fadeIn dropdown-menu">
                                <div class="dropdown-content-body">
                                    <ul>
                                        <li>
                                            <a href="profile.php"><i class="icon-user"></i> <span>Profile</span></a>
                                        </li>


                                        <hr class="my-2">

                                        <li><a href="logout.php"><i class="icon-key"></i> <span>Logout</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="nk-sidebar">
            <div class="nk-nav-scroll">
                <ul class="metismenu" id="menu">
                    <li>
                        <a href="./frontend/index.php" aria-expanded="false">
                            <i class="fa fa-home"></i> <span class="nav-text">Front Home Page</span>
                        </a>
                    </li>
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon-speedometer menu-icon"></i><span class="nav-text">Dashboard</span>
                        </a>
                        <ul aria-expanded="false">
                            <?php if ($_SESSION['admin_data']['designation'] == 'admin') { ?>
                                <li><a href="./dashboard.php"><i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard </a></li>
                            <?php } else { ?>

                                <li><a href="./teachers_dashboard.php"><i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard </a></li>
                            <?php } ?>
                            <li><a href="./profile.php"> <i class="fa fa-user"></i> Profile</a></li>

                            <!-- <li><a href="./index-2.html">Home 2</a></li> -->

                        </ul>
                    </li>
                    <?php if ($_SESSION['admin_data']['designation'] == 'admin') { ?>
                        <li>
                            <a href="./list-admin.php" aria-expanded="false">
                                <i class="fa fa-user-o"></i> <span class="nav-text">Admin</span>
                            </a>
                        </li>
                        <li>
                            <a href="./list-trade.php" aria-expanded="false">
                                <i class="fa fa-bolt"></i> <span class="nav-text">Trade</span>
                            </a>
                        </li>
                        <li>
                            <a href="./list-subject.php" aria-expanded="false">
                                <i class="fa fa-book"></i> <span class="nav-text">Subject</span>
                            </a>
                        </li>
                        <li>
                            <a href="./list-teachers.php" aria-expanded="false">
                                <i class="fa fa-users"></i> <span class="nav-text">Teachers</span>
                            </a>
                        </li>
                        <li>
                            <a href="./list-students.php" aria-expanded="false">
                                <i class="fa fa-users"></i> <span class="nav-text">Students</span>
                            </a>
                        </li>
                    <?php } ?>

                    <li>
                        <?php if ($_SESSION['admin_data']['designation'] == 'admin') { ?>
                            <a href="./list-feedback.php" aria-expanded="false">
                                <i class="fa fa-commenting"></i> <span class="nav-text">Feedback</span>
                            </a>
                    <li>
                        <a href="./list-feedback-specific.php" aria-expanded="false">
                            <i class="fa fa-commenting"></i> <span class="nav-text">Specific Feedback</span>
                        </a>
                    </li>
                    <li>

                        <a href="./feedback-cycle.php" aria-expanded="false">
                            <i class="fa fa-recycle"></i> <span class="nav-text">Feedback Cycle</span>
                        </a>

                    </li>
                    <li>

                        <a href="./add-student-activity.php" aria-expanded="false">                     <i class="fa fa-plus"></i> <span class="nav-text">Add Student Activity</span>
                        </a>

                    </li>
                    <li>

                        <a href="./list-students-activity.php" aria-expanded="false">
                            <i class="fa fa-eye"></i> <span class="nav-text">Show Student Activity</span>
                        </a>

                    </li>
                    <li>

                        <a href="./doc.php" aria-expanded="false">
                            <i class="fa fa-book"></i> <span class="nav-text">Project Documentation</span>
                        </a>

                    </li>

                <?php } else { ?>
                    <a href="./list-teacher-feedback.php" aria-expanded="false">
                        <i class="fa fa-commenting"></i> <span class="nav-text">Feedback</span>
                    </a>
                    <?php
                            $notshowactivity = [40, 39];
                            if (array_search($_SESSION['admin_data']['teacher_id'], $notshowactivity) === false) { ?>
                        <li>

                            <a href="./add-student-activity-teacher.php" aria-expanded="false">
                                <i class="fa fa-plus"></i> <span class="nav-text">Add Student Activity</span>
                            </a>

                        </li>
                        <li>

                            <a href="./list-students-activity-teacher.php" aria-expanded="false">
                                <i class="fa fa-eye"></i> <span class="nav-text">Show Student Activity</span>
                            </a>

                        </li>


                <?php
                            }
                        } ?>
                </li>




                </ul>
            </div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">