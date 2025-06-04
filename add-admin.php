<?php include('header.php'); ?>

<!-- content -->
<div class="container-fluid">   
    <div class="card">
        <div class="card-body">
            <div class="form-validation">
                <form class="form-valide" action="" method="post" enctype="multipart/form-data" autocomplete="off">
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="val-username">Username <span class="text-danger">*</span></label>
                        <input type="hidden" name="id" value="">
                        <div class="col-lg-6">
                            <input type="text" class="form-control" value="" id="val-username" name="username" placeholder="Enter a username.." required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="val-email">Email <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="email" class="form-control" id="val-email" name="email" value="" placeholder="Your valid email.." required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="val-phone">Mobile No. <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="number" maxlength="10" value="" class="form-control" id="val-phone" name="phone" placeholder="Your phone number.." required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="val-password">Password <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="password" value="" class="form-control" id="val-password" name="password" placeholder="Enter password.." required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="val-cnf-password">Confirm Password <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="password" value="" class="form-control" id="val-cnf-password" name="cnf_password" placeholder="Confirm password.." required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Status <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <label class="mr-2"><input type="radio" value="0" name="status"> Inactive</label>
                            <label><input type="radio" value="1" name="status" checked> Active</label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Profile Picture <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="file" name="image" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-8 ml-auto">
                            <button type="submit" name="submit" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                </form>

                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $name = $_POST["username"];
                    $email = $_POST["email"];
                    $phone = $_POST["phone"];
                    $password = $_POST["password"];
                    $cnf_password = $_POST["cnf_password"];
                    $status = $_POST["status"];
                    $imagePath = "";

                    if ($password !== $cnf_password) {
                        echo "<div class='text-danger mt-3'>❌ Passwords do not match.</div>";
                    } else {
                        $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
                        $uploadDir = "dp_uploads/";

                        // Create folder if not exists
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }

                        if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
                            $fileName = basename($_FILES["image"]["name"]);
                            $targetPath = $uploadDir . $fileName;

                            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
                                $imagePath = $targetPath;

                                // Prepare SQL Insert
                                $sql = "INSERT INTO admin (name, password, mobile, email, status, dp_file_path) VALUES (?, ?, ?, ?, ?, ?)";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("ssssis", $name, $encrypted_password, $phone, $email, $status, $imagePath);

                                if ($stmt->execute()) {
                                  
                                    echo "<div class='text-success mt-3'>✅ User added successfully!</div>";
                                } else {
                                    echo "<div class='text-danger mt-3'>❌ Error: " . $stmt->error . "</div>";
                                }

                                $stmt->close();
                            } else {
                                echo "<div class='text-danger mt-3'>❌ Failed to upload image.</div>";
                            }
                        } else {
                            echo "<div class='text-danger mt-3'>❌ No image uploaded or upload error occurred.</div>";
                        }
                    }

                    $conn->close();
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- end content -->

<?php include('footer.php'); ?>
