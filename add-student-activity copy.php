<?php 
// Ensure you have your database connection file included.
// This file should create the $conn object.
include('header.php'); 

// --- PHP LOGIC SECTION ---

// Initialize variables
$students = [];
$selected_program = '';
$selected_trade_id = '';
$message = '';

// --- Logic to Handle Form Submissions ---

// 1. Check if the 'Find Students' button was clicked
if (isset($_POST['find_students'])) {
    $selected_program = $_POST['program'];
    $selected_trade_id = $_POST['trade_id'];

    if (!empty($selected_program) && !empty($selected_trade_id)) {
        // Prepare SQL to fetch students based on program and trade
        // Note: Assumes you have a 'students' table with columns like 'id', 'student_name', 'program', and 'trade_id'
        $stmt = $conn->prepare("SELECT id, name FROM students WHERE program = ? AND trade = ? ORDER BY name ASC");
        $stmt->bind_param("ss", $selected_program, $selected_trade_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
        $stmt->close();

        if (empty($students)) {
            $message = "<div class='alert alert-warning'>⚠️ No students found for the selected criteria.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>❌ Please select both Program and Trade.</div>";
    }
}

// 2. Check if the 'Add Activities' button was clicked
if (isset($_POST['save_activities'])) {
    // Get the arrays of data from the form
    $student_ids = $_POST['student_id'];
    $total_lessons = $_POST['total_lesson'];
    $total_demos = $_POST['total_demo'];
    $total_practicals = $_POST['total_practical'];
    $total_tests = $_POST['total_test'];
    $total_tmps = $_POST['total_tmp'];
    
    // Assume created_by and updated_by are taken from session (e.g., logged-in user ID)
    $created_by = 1; // Example user ID
    $updated_by = 1; // Example user ID

    // Prepare the SQL statement for insertion
    // Using INSERT ... ON DUPLICATE KEY UPDATE is robust. It will UPDATE if a record for the student_id already exists.
    // For this to work, you MUST have a UNIQUE index on the `student_id` column in your `student_activity` table.
    $sql = "INSERT INTO student_activity (student_id, total_lesson, total_demo, total_practical, total_test, total_tmp, status, created_by, updated_by) 
            VALUES (?, ?, ?, ?, ?, ?, 'active', ?, ?)
            ON DUPLICATE KEY UPDATE
            total_lesson = VALUES(total_lesson), total_demo = VALUES(total_demo), total_practical = VALUES(total_practical), 
            total_test = VALUES(total_test), total_tmp = VALUES(total_tmp), updated_by = VALUES(updated_by), updated_at = NOW()";
    
    $stmt = $conn->prepare($sql);
    
    $conn->begin_transaction();
    $all_saved = true;

    // Loop through each student's data and execute the insert/update
    foreach ($student_ids as $key => $student_id) {
        $stmt->bind_param(
            "iiiiiiii",
            $student_id,
            $total_lessons[$key],
            $total_demos[$key],
            $total_practicals[$key],
            $total_tests[$key],
            $total_tmps[$key],
            $created_by,
            $updated_by
        );

        if (!$stmt->execute()) {
            $all_saved = false;
            $message = "<div class='alert alert-danger'>❌ Error saving data: " . $stmt->error . "</div>";
            break; // Exit loop on first error
        }
    }

    if ($all_saved) {
        $conn->commit();
        $message = "<div class='alert alert-success'>✅ Student activities have been saved successfully!</div>";
    } else {
        $conn->rollback();
    }

    $stmt->close();
}

?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Add Student Activity</h4>
        </div>
        <div class="card-body">
            
            <?php if(!empty($message)) echo $message; ?>

            <form class="form-valide" action="" method="post" autocomplete="off">
                
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="program">Program <span class="text-danger">*</span></label>
                        <select class="form-control" id="program" name="program" required>
                            <option value="">-- Select Program --</option>
                            <option value="CTS" <?php if ($selected_program == 'CTS') echo 'selected'; ?>>CTS</option>
                            <option value="CITS" <?php if ($selected_program == 'CITS') echo 'selected'; ?>>CITS</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="trade_id">Trade <span class="text-danger">*</span></label>
                        <select class="form-control" id="trade_id" name="trade_id" required>
                            <option value="">-- Select Trade --</option>
                            <?php
                            // Fetch trades from the 'trade' table to populate the dropdown
                            $trade_result = $conn->query("SELECT trade_id, trade_name FROM trade ORDER BY trade_name ASC");
                            while ($trade = $trade_result->fetch_assoc()) {
                                $selected = ($trade['trade_name'] == $selected_trade_id) ? 'selected' : '';
                                echo "<option value='{$trade['trade_name']}' {$selected}>{$trade['trade_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-md-4" style="margin-top: 28px;">
                        <button type="submit" name="find_students" class="btn btn-info"><i class="fa fa-search"></i> Find Students</button>
                    </div>
                </div>
                <hr>

                <?php if (!empty($students)): ?>
                <div class="table-responsive table-scrollable"  >
                    <table class="table table-striped table-bordered">
                        <thead class="sticky-header">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Total Lesson</th>
                                <th>Total Demo</th>
                                <th>Total Practical</th>
                                <th>Total Test</th>
                                <th>Total TMP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $index => $student): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td>
                                    <?php echo htmlspecialchars($student['name']); ?>
                                    <input type="hidden" name="student_id[]" value="<?php echo $student['id']; ?>">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="total_lesson[]" value="0" min="0" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="total_demo[]" value="0" min="0" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="total_practical[]" value="0" min="0" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="total_test[]" value="0" min="0" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="total_tmp[]" value="0" min="0" required>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-lg-12 text-center">
                        <button type="submit" name="save_activities" class="btn btn-primary"><i class="fa fa-plus"></i> Add Activities for All Students</button>
                    </div>
                </div>
                <?php endif; ?>

            </form>
        </div>
    </div>
</div>
<?php 
$conn->close();
include('footer.php'); 
?>