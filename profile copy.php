<?php include 'header.php'; ?>

<?php
// Assuming $conn is your mysqli connection object
$userData = $_SESSION['admin_data'];
$query = "SELECT teacher_id, name, email, mobile_no, dp_file_path FROM teachers WHERE teacher_id = " . $userData['teacher_id'];
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}

$id = 0;
$name = '';
$email = '';
$mobile = '';
$dp = '';

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id = $row['teacher_id'];
    $name = $row['name'];
    $email = $row['email'];
    $mobile = $row['mobile_no'];
    $dp = $row['dp_file_path'];
}
?>

<div class="row page-titles mx-0">
    <div class="col p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Profile</a></li>
        </ol>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4 col-xl-3">
            <div class="card">
                <div class="card-body">

                    <div class="media align-items-center mb-4">
                        <img class="mr-3" src="<?php echo $dp ?>" width="80" height="80" alt="Profile Picture">
                        <div class="media-body">
                            <h3 class="mb-0"><?php echo htmlspecialchars($name); ?></h3>
                        </div>
                    </div>
                    <ul class="card-profile__info">
                        <li class="mb-1"><strong class="text-dark mr-4">Mobile</strong> <span><?php echo htmlspecialchars($mobile); ?></span></li>
                        <li><strong class="text-dark mr-4">Email</strong> <span><?php echo htmlspecialchars($email); ?></span></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-xl-9">
            <div class="card">
                <div class="card-body">
                    <div class="form-validation">
                        <form class="form-valide" action="backend/update-profile.php" method="post" enctype="multipart/form-data">
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Username <span class="text-danger">*</span></label>
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($name); ?>" name="username" placeholder="Enter a username..">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Email <span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Your valid email..">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Mobile No. <span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($mobile); ?>" placeholder="Your phone number..">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Profile Picture</label>
                                <div class="col-lg-6">
                                    <input type="file" name="image" class="custom-file-input" accept=".jpg, .jpeg, .png">
                                    <label class="custom-file-label">Choose file</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-8 ml-auto">
                                    <button type="submit" name="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>