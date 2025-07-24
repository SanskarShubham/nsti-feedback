<?php include('header.php'); ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header text-right">
            <a href="list-students.php" class="btn btn-primary"><i class="fa fa-eye"></i> View Students</a>
            <a href="sample_csv_format/student_data_sample.csv" class="btn btn-success" download><i class="fa fa-download"></i> Download Sample CSV File</a>
        </div>
        <div class="card-body">
            <div class="form-validation">

                <!-- Mode Selector -->
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Select Entry Mode <span class="text-danger">*</span></label>
                    <div class="col-lg-6">
                        <label><input type="radio" name="entry_mode" value="manual" onclick="toggleEntry('manual')"> Manual Entry</label>
                        <label class="ml-3"><input type="radio" name="entry_mode" value="csv" onclick="toggleEntry('csv')"> CSV Upload</label>
                    </div>
                </div>

                <!-- Manual Entry Form -->
                <form method="post" id="manualForm" style="display:none;">
                    <input type="hidden" name="entry_type" value="manual">
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">attendance ID <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="text" name="attendance_Id" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Student Name <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="text" name="username" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Trade <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <select class="form-control" name="trade" required>
                                <option value="">Select Trade</option>
                                <?php
                                include('connection.php');
                                $sql = "SELECT trade_name FROM trade ORDER BY trade_name ASC";
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="'.$row['trade_name'].'">'.$row['trade_name'].'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Program <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <label><input type="radio" name="program" value="CTS" required> CTS</label>
                            <label class="ml-3"><input type="radio" name="program" value="CITS"> CITS</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-8 ml-auto">
                            <button type="submit" name="submit_manual" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
                        </div>
                    </div>
                </form>

                <!-- CSV Upload Form -->
                <form method="post" enctype="multipart/form-data" id="csvForm" style="display:none;">
                    <input type="hidden" name="entry_type" value="csv">
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Upload CSV File <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-8 ml-auto">
                            <button type="submit" name="submit_csv" class="btn btn-primary"><i class="fa fa-upload"></i> Upload</button>
                        </div>
                    </div>
                </form>

                <?php
                include('connection.php');

                // Manual Entry Processing
                if (isset($_POST['submit_manual'])) {
                    $attid = $_POST['attendance_Id'];
                    $name = $_POST['username'];
                    $trade = $_POST['trade']; // Now holds trade name
                    $program = $_POST['program'];

                    $check = $conn->prepare("SELECT * FROM students WHERE attendance_id = ?");
                    $check->bind_param("s", $attid);
                    $check->execute();
                    $result = $check->get_result();

                    if ($result->num_rows > 0) {
                        echo "<div class='text-danger'>❌ Duplicate Entry: $attid already exists.</div>";
                    } else {
                        $stmt = $conn->prepare("INSERT INTO students (attendance_id, name, trade, program) VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("ssss", $attid, $name, $trade, $program);
                        if ($stmt->execute()) {
                            echo "<div class='text-success'>✅ Student added successfully!</div>";
                        } else {
                            echo "<div class='text-danger'>❌ Error: " . $stmt->error . "</div>";
                        }
                        $stmt->close();
                    }
                    $conn->close();
                }

                // CSV Upload Processing
                if (isset($_POST['submit_csv'])) {
                    if ($_FILES['csv_file']['error'] == 0) {
                        $file = fopen($_FILES['csv_file']['tmp_name'], 'r');
                        $header = fgetcsv($file); // Skip header

                        $inserted = 0;
                        $duplicate = 0;

                        while (($data = fgetcsv($file)) !== FALSE) {
                            $attid = trim($data[0]);
                            $name = trim($data[1]);
                            $trade = trim($data[2]); // trade name
                            $program = trim($data[3]);

                            if ($attid == '' || $name == '' || $trade == '' || $program == '') continue;

                            $check = $conn->prepare("SELECT * FROM students WHERE attendance_id = ?");
                            $check->bind_param("s", $attid);
                            $check->execute();
                            $result = $check->get_result();

                            if ($result->num_rows == 0) {
                                $stmt = $conn->prepare("INSERT INTO students (attendance_id, name, trade, program) VALUES (?, ?, ?, ?)");
                                $stmt->bind_param("ssss", $attid, $name, $trade, $program);
                                if ($stmt->execute()) $inserted++;
                                $stmt->close();
                            } else {
                                $duplicate++;
                            }
                            $check->close();
                        }
                        fclose($file);
                        echo "<div class='text-success'>✅ $inserted entries added. 🚫 $duplicate duplicates skipped.</div>";
                        $conn->close();
                    } else {
                        echo "<div class='text-danger'>❌ Error uploading file.</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
function toggleEntry(mode) {
    document.getElementById('manualForm').style.display = mode === 'manual' ? 'block' : 'none';
    document.getElementById('csvForm').style.display = mode === 'csv' ? 'block' : 'none';
}
</script>
<?php include('footer.php'); ?>
