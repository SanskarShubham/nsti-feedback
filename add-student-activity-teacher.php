<?php 
// Ensure you have your database connection file included.
include('header.php'); 

// --- GET TEACHER'S ASSIGNED TRADES FROM DATABASE ---
$teacher_id = $_SESSION['admin_data']['teacher_id'];
$assigned_trades = [];

// This query joins the pivot table with the trades table to get the names of trades assigned to the logged-in teacher.
$sql_trades = "SELECT DISTINCT t.trade_name 
               FROM teacher_subject_trade tst
               JOIN trade t ON tst.trade_id = t.trade_id
               WHERE tst.teacher_id = ?";

$stmt_trades = $conn->prepare($sql_trades);
$stmt_trades->bind_param("i", $teacher_id);
$stmt_trades->execute();
$result_trades = $stmt_trades->get_result();
while ($row_trade = $result_trades->fetch_assoc()) {
    $assigned_trades[] = $row_trade['trade_name'];
}
$stmt_trades->close();

// If the teacher has no trades assigned, show an error and stop.
if (empty($assigned_trades)) {
    echo "<div class='container-fluid'><div class='alert alert-danger'><strong>Action Required:</strong> You have no trades assigned to your profile. Please contact an administrator.</div></div>";
    include('footer.php');
    exit();
}
?>

<!-- CSS for the Sticky Table Header -->
<style>
    .table-scrollable {
        max-height: 65vh; 
        overflow-y: auto;
    }
    .table-scrollable thead th {
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        z-index: 2;
        background-color: #f8f9fa; 
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
    }
    input:disabled {
        background-color: #e9ecef;
        cursor: not-allowed;
    }
</style>

<?php
// --- PHP LOGIC SECTION ---

// Initialize variables
$students = [];
$selected_program = '';
$selected_trade = '';
$message = '';
$is_readonly = false; 

// --- Logic to Handle Form Submissions ---

// 1. Check if the 'Find Students' button was clicked
if (isset($_POST['find_students'])) {
    $selected_program = $_POST['program'];
    $selected_trade = $_POST['trade_id']; // This is the trade name selected from the dropdown

    // Security check: ensure the selected trade is one the teacher is actually assigned to
    if (in_array($selected_trade, $assigned_trades)) {
        if (!empty($selected_program) && !empty($selected_trade)) {
            $sql = "SELECT s.id, s.name, sa.total_lesson, sa.total_demo, sa.total_practical, sa.total_test, sa.total_tmp, sa.remarks
                    FROM students s
                    LEFT JOIN student_activity sa ON s.id = sa.student_id
                    WHERE s.program = ? AND s.trade = ? 
                    ORDER BY s.name ASC";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $selected_program, $selected_trade);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
                if ($row['total_lesson'] !== null) {
                    $is_readonly = true;
                }
            }
            $stmt->close();

            if (empty($students)) {
                $message = "<div class='alert alert-warning'>⚠️ No students found for '" . htmlspecialchars($selected_trade) . "' in the selected program.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>❌ Please select both Program and Trade.</div>";
        }
    } else {
        // This prevents a user from trying to submit a trade they are not assigned to
        $message = "<div class='alert alert-danger'>❌ Invalid trade selected.</div>";
    }
}

// 2. Check if the 'save_activities' button was clicked
if (isset($_POST['save_activities'])) {
    // This logic remains the same as it correctly handles the data submission
    $student_ids = $_POST['student_id'];
    $total_lessons = $_POST['total_lesson'];
    $total_demos = $_POST['total_demo'];
    $total_practicals = $_POST['total_practical'];
    $total_tests = $_POST['total_test'];
    $total_tmps = $_POST['total_tmp'];
    $remarks = $_POST['remarks'];
    $status = 1;
    $created_by = $_SESSION['admin_data']['teacher_id'];
    $updated_by = $_SESSION['admin_data']['teacher_id'];
    $teacher_id= $_SESSION['admin_data']['teacher_id'];
    $sql = "INSERT INTO student_activity (student_id, total_lesson, total_demo, total_practical, total_test, total_tmp, remarks, status, created_by, updated_by,teacher_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?,?, ?, ?,?)
            ON DUPLICATE KEY UPDATE
            total_lesson = VALUES(total_lesson), total_demo = VALUES(total_demo), total_practical = VALUES(total_practical), 
            total_test = VALUES(total_test), total_tmp = VALUES(total_tmp), remarks = VALUES(remarks), updated_by = VALUES(updated_by), updated_at = NOW()";
    
    $stmt = $conn->prepare($sql);
    $conn->begin_transaction();
    $all_saved = true;

    foreach ($student_ids as $key => $student_id) {
        $stmt->bind_param(
            "iiiiiisiiii", 
            $student_id, $total_lessons[$key], $total_demos[$key], $total_practicals[$key], 
            $total_tests[$key], $total_tmps[$key], $remarks[$key], $status,
            $created_by, $updated_by,$teacher_id
        );

        if (!$stmt->execute()) {
            $all_saved = false;
            $message = "<div class='alert alert-danger'>❌ Error saving data: " . $stmt->error . "</div>";
            break;
        }
    }

    if ($all_saved) {
        $conn->commit();
        $message = "<div class='alert alert-success'>✅ Student activities have been saved successfully! The data is now locked.</div>";
        $is_readonly = true;
    } else {
        $conn->rollback();
    }
    $stmt->close();
}
?>

<!-- Page content starts here -->
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
                        <!-- MODIFICATION: This dropdown now only shows trades assigned to the teacher -->
                        <select class="form-control" id="trade_id" name="trade_id" required>
                            <option value="">-- Select Your Trade --</option>
                            <?php
                            foreach ($assigned_trades as $trade_name) {
                                $selected = ($trade_name == $selected_trade) ? 'selected' : '';
                                echo "<option value='" . htmlspecialchars($trade_name) . "' {$selected}>" . htmlspecialchars($trade_name) . "</option>";
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

                <?php if ($is_readonly): ?>
                    <div class="alert alert-info text-center">
                        <strong>Data Locked:</strong> Activities for this group have already been submitted and cannot be edited.
                    </div>
                <?php endif; ?>

                <div class="table-responsive table-scrollable">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Total Lesson</th>
                                <th>Total Demo</th>
                                <th>Total Practical</th>
                                <th>Total Test</th>
                                <th>Total TMP</th>
                                <th>Remarks</th>
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
                                    <input type="number" class="form-control" name="total_lesson[]" value="<?php echo $student['total_lesson'] ?? 0; ?>" min="0" required <?php if ($is_readonly) echo 'disabled'; ?>>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="total_demo[]" value="<?php echo $student['total_demo'] ?? 0; ?>" min="0" required <?php if ($is_readonly) echo 'disabled'; ?>>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="total_practical[]" value="<?php echo $student['total_practical'] ?? 0; ?>" min="0" required <?php if ($is_readonly) echo 'disabled'; ?>>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="total_test[]" value="<?php echo $student['total_test'] ?? 0; ?>" min="0" required <?php if ($is_readonly) echo 'disabled'; ?>>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="total_tmp[]" value="<?php echo $student['total_tmp'] ?? 0; ?>" min="0" required <?php if ($is_readonly) echo 'disabled'; ?>>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="remarks[]" value="<?php echo htmlspecialchars($student['remarks'] ?? ''); ?>" <?php if ($is_readonly) echo 'disabled'; ?>>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-lg-12 text-center">
                        <?php if (!$is_readonly): ?>
                            <button type="submit" name="save_activities" class="btn btn-primary"><i class="fa fa-plus"></i> Submit Activities</button>
                        <?php endif; ?>
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
