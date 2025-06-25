<?php include('header.php'); ?>

<!-- content -->
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="form-validation">
                <form class="form-valide" action="" method="post" enctype="multipart/form-data" autocomplete="off">
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="val-username">Name <span class="text-danger">*</span></label>
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
                        <label class="col-lg-4 col-form-label">Designation <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <label class="mr-2"><input type="radio" value="Admin" name="designation" required> Admin</label>
                            <label><input type="radio" value="Other" name="designation" required> Other</label>
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


                    <div id="subject-container">
                        <div class="form-group row subject-row">
                            <label class="col-lg-4 col-form-label">Program / Trade / Subject <span class="text-danger">*</span></label>
                            <div class="col-lg-2">
                                <select class="form-control" name="program[]">
                                    <option value="">Select Program</option>
                                    <option value="CTS">CTS</option>
                                    <option value="CITS">CITS</option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <select class="form-control" name="trade[]">
                                    <option value="">Select Trade</option>
                                    <?php
                                    $sql = "SELECT trade_id, trade_name FROM trade ORDER BY trade_name ASC";
                                    $result = mysqli_query($conn, $sql);
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<option value="' . $row['trade_id'] . '">' . $row['trade_name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>


                            <div class="col-lg-2">
                                <select class="form-control" name="subject[]">
                                    <option value="">Select Subject</option>
                                    <?php
                                    $sql = "SELECT subject_id, name FROM subject ORDER BY name ASC";
                                    $result = mysqli_query($conn, $sql);
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<option value="' . $row['subject_id'] . '">' . $row['name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <button type="button" class="btn btn-success" onclick="addSubjectRow(this)"><i class="fa fa-plus"> </i> ADD</button>
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-lg-8 ml-auto">
                            <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-check"> </i> SUBMIT</button>
                        </div>
                    </div>
                </form>

                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $name = $_POST["username"];
                    $email = $_POST["email"];
                    $phone = $_POST["phone"];
                    $status = $_POST["status"];
                    $designation = $_POST["designation"];
                    $password = $_POST["password"];
                    $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
                    // Prepare SQL Insert
                    $sql = "INSERT INTO teachers (name, mobile_no, email, status, designation , password) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssiss", $name, $phone, $email, $status, $designation, $encrypted_password);

                    if ($stmt->execute()) {
                        $lastId = $stmt->insert_id; // Get last inserted ID

                        // Use last inserted ID and array data to insert into teachers_trade_subject
                        $programs = $_POST['program'];
                        $trades = $_POST['trade'];
                        $subjects = $_POST['subject'];

                        foreach ($programs as $index => $program) {
                            $trade = $trades[$index];
                            $subject = $subjects[$index];

                            $sql = "INSERT INTO teacher_subject_trade (teacher_id, program, trade_id, subject_id) VALUES (?, ?, ?, ?)";
                            $stmt2 = $conn->prepare($sql);
                            $stmt2->bind_param("isii", $lastId, $program, $trade, $subject);

                            if (!$stmt2->execute()) {
                                echo "<div class='text-danger mt-3'>❌ Error: " . $stmt2->error . "</div>";
                            }

                            $stmt2->close();
                        }

                        echo "<div class='text-success mt-3'>✅ User added successfully!</div>";
                    } else {
                        echo "<div class='text-danger mt-3'>❌ Error: " . $stmt->error . "</div>";
                    }

                    $stmt->close();
                    $conn->close();
                }
                ?>

            </div>
        </div>
    </div>
</div>
<!-- end content -->




<script>
    function addSubjectRow(button) {
        let container = document.getElementById("subject-container");

        // Clone the first subject row
        let originalRow = button.closest(".subject-row");
        let newRow = originalRow.cloneNode(true);

        // Clear values in the cloned dropdowns
        newRow.querySelectorAll("select").forEach(select => select.value = "");

        // Replace Add button with Remove button
        let actionBtn = newRow.querySelector("button");
        actionBtn.innerText = "Remove";
        actionBtn.className = "btn btn-danger";
        actionBtn.setAttribute("onclick", "removeSubjectRow(this)");

        // Append the new row
        container.appendChild(newRow);
    }

    function removeSubjectRow(button) {
        let row = button.closest(".subject-row");
        row.remove();
    }
</script>




<?php include('footer.php'); ?>