<?php require_once 'header.php'; ?>

<?php
// Assuming $conn is your mysqli connection object
$userData = $_SESSION['admin_data'];
// print_r($userData);
$query = "SELECT id,name, email, mobile FROM admin WHERE id = " . $userData['id'];
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}
$id = 0;
$name = '';
$email = '';
$mobile = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $name = $row['name'];
        $email = $row['email'];
        $mobile = $row['mobile'];
    }
} else {
    echo "No records found.";
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
<!-- row -->

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="media align-items-center mb-4">
                        <img class="mr-3" src="images/avatar/11.png" width="80" height="80" alt="">
                        <div class="media-body">
                            <h3 class="mb-0">Pikamy Cha</h3>
                            <p class="text-muted mb-0">Canada</p>
                        </div>
                    </div>


                    <h4>About Me</h4>
                    <p class="text-muted">Hi, I'm Pikamy, has been the industry standard dummy text ever since the 1500s.</p>
                    <ul class="card-profile__info">
                        <li class="mb-1"><strong class="text-dark mr-4">Mobile</strong> <span>01793931609</span></li>
                        <li><strong class="text-dark mr-4">Email</strong> <span>name@domain.com</span></li>
                    </ul>
                </div>
            </div>
        </div>


        <!-- form starts from here -->






        <div class="col-lg-8 col-xl-9">
            <div class="card">
                <div class="card-body">
                    <div class="form-validation">
                        <form class="form-valide" action="backend/update-profile.php" method="post">
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" for="val-username">Username <span class="text-danger">*</span>
                                </label>
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" value="<?php echo $name; ?>" id="val-username" name="username" placeholder="Enter a username..">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" for="val-email">Email <span class="text-danger">*</span>
                                </label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" id="val-email" name="email" value="<?php echo $email; ?>" placeholder="Your valid email..">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" for="phone">Mobile NO. <span class="text-danger">*</span>
                                </label>
                                <div class="col-lg-6">
                                    <input type="text" value="<?php echo htmlspecialchars($mobile ?? ''); ?>" class="form-control" id="val-phoneus" name="phone" placeholder="Your phone number..">

                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-8 ml-auto">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>



    </div>



</div>
</div>
<?php include 'footer.php'; ?>