<?php 
include('header.php'); 
date_default_timezone_set('Asia/Kolkata');
$conn->query("SET time_zone = '+05:30'");

// --- GET ACTIVE ACTIVITY CYCLE ---
$active_cycle = null;
$cycle_result = $conn->query("SELECT * FROM student_activity_cycle WHERE is_active = 1 AND NOW() BETWEEN start_date AND end_date LIMIT 1");
if ($cycle_result && $cycle_result->num_rows > 0) {
    $active_cycle = $cycle_result->fetch_assoc();
}

// --- ROLE-BASED PERMISSIONS SETUP ---
$user_designation = $_SESSION['admin_data']['designation'];
$teacher_id = $_SESSION['admin_data']['teacher_id'];
$assigned_trades = [];

// Admins can see and filter all trades.
if ($user_designation === 'admin') {
    $trade_result = $conn->query("SELECT trade_name FROM trade ORDER BY trade_name ASC");
    while ($trade_row = $trade_result->fetch_assoc()) {
        $assigned_trades[] = $trade_row['trade_name'];
    }
} 
// Teachers can only see their assigned trades.
else {
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
}

if (empty($assigned_trades) && $user_designation !== 'admin') {
    echo "<div class='container-fluid'><div class='alert alert-danger'><strong>Action Required:</strong> You have no trades assigned to your profile. Please contact an administrator.</div></div>";
    include('footer.php');
    exit();
}
?>

<!-- CSS for the Sticky Table Header -->
<style>
    .table-scrollable { max-height: 65vh; overflow-y: auto; }
    .table-scrollable thead th { position: sticky; top: 0; z-index: 2; background-color: #f8f9fa; box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1); }
    input:disabled, textarea:disabled { background-color: #e9ecef; cursor: not-allowed; }
</style>

<?php
// --- PHP LOGIC SECTION ---
$students = [];
$selected_program = '';
$selected_trade = '';
$message = '';
$is_readonly = false; 

// 1. Check if the 'Find Students' button was clicked
if (isset($_POST['find_students'])) {
    if (!$active_cycle) {
        $message = "<div class='alert alert-warning'>⚠️ Student Activity submission is not currently active.</div>";
    } else {
        $selected_program = $_POST['program'];
        $selected_trade = $_POST['trade_id'];

        if (in_array($selected_trade, $assigned_trades)) {
            if (!empty($selected_program) && !empty($selected_trade)) {
                
                $join_type = ($user_designation === 'admin') ? "INNER JOIN" : "LEFT JOIN";
                $cycle_condition = "AND (sa.student_activity_cycle_id = ? OR sa.student_activity_cycle_id IS NULL)";

                $sql = "SELECT s.id, s.name, sa.total_lesson, sa.total_demo, sa.total_practical, sa.total_test, sa.total_tmp, sa.remarks, sa.student_activity_cycle_id
                        FROM students s
                        {$join_type} student_activity sa ON s.id = sa.student_id {$cycle_condition}
                        WHERE s.program = ? AND s.trade = ? 
                        ORDER BY s.name ASC";
                
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iss", $active_cycle['cycle_id'], $selected_program, $selected_trade);
                $stmt->execute();
                $result = $stmt->get_result();
                
                while ($row = $result->fetch_assoc()) {
                    $students[] = $row;
                    if ($user_designation !== 'admin' && $row['student_activity_cycle_id'] == $active_cycle['cycle_id']) {
                        $is_readonly = true;
                    }
                }
                $stmt->close();

                if (empty($students)) {
                    $message_text = ($user_designation === 'admin') 
                        ? "No submitted activity records found for this selection in the current cycle."
                        : "No students found for your assigned trade in the selected program.";
                    $message = "<div class='alert alert-warning'>⚠️ {$message_text}</div>";
                }
            } else {
                $message = "<div class='alert alert-danger'>❌ Please select both Program and Trade.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>❌ Invalid trade selected.</div>";
        }
    }
}

// 2. Check if the 'save_activities' button was clicked
if (isset($_POST['save_activities'])) {
    if (!$active_cycle) {
        $message = "<div class='alert alert-danger'>❌ Cannot save. The student activity cycle is no longer active.</div>";
    } else {
        $student_ids = $_POST['student_id'];
        $total_lessons = $_POST['total_lesson'];
        $total_demos = $_POST['total_demo'];
        $total_practicals = $_POST['total_practical'];
        $total_tests = $_POST['total_test'];
        $total_tmps = $_POST['total_tmp'];
        $remarks = $_POST['remarks'];
        $cycle_id_on_submit = (int)$_POST['active_cycle_id'];
        
        $current_user_id = $_SESSION['admin_data']['teacher_id'];

        if ($cycle_id_on_submit !== $active_cycle['cycle_id']) {
             $message = "<div class='alert alert-danger'>❌ Session mismatch. Please refresh and try again.</div>";
        } else {
            if ($user_designation === 'admin') {
                $sql = "UPDATE student_activity SET 
                        total_lesson = ?, total_demo = ?, total_practical = ?, 
                        total_test = ?, total_tmp = ?, remarks = ?, updated_by = ?, updated_at = NOW(), status = 1, teacher_id = ?  
                        WHERE student_id = ? AND student_activity_cycle_id = ?";
            } else {
                $sql = "INSERT INTO student_activity (student_id, total_lesson, total_demo, total_practical, total_test, total_tmp, remarks, status, created_by, updated_by, teacher_id, student_activity_cycle_id) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE
                        total_lesson = VALUES(total_lesson), total_demo = VALUES(total_demo), total_practical = VALUES(total_practical), 
                        total_test = VALUES(total_test), total_tmp = VALUES(total_tmp), remarks = VALUES(remarks), updated_by = VALUES(updated_by), updated_at = NOW()";
            }
            
            $stmt = $conn->prepare($sql);
            $conn->begin_transaction();
            $all_saved = true;

            foreach ($student_ids as $key => $student_id) {
                if ($user_designation === 'admin') {
                    $stmt->bind_param("iiiiisiiii", 
                        $total_lessons[$key], $total_demos[$key], $total_practicals[$key], 
                        $total_tests[$key], $total_tmps[$key], $remarks[$key], 
                        $current_user_id, $current_user_id, $student_id, $cycle_id_on_submit
                    );
                } else {
                    $stmt->bind_param("iiiiisiiiii", 
                        $student_id, $total_lessons[$key], $total_demos[$key], $total_practicals[$key], 
                        $total_tests[$key], $total_tmps[$key], $remarks[$key], 
                        $current_user_id, $current_user_id, $current_user_id, $cycle_id_on_submit
                    );
                }

                if (!$stmt->execute()) {
                    $all_saved = false;
                    $message = "<div class='alert alert-danger'>❌ Error saving data: " . $stmt->error . "</div>";
                    break;
                }
            }

            if ($all_saved) {
                $conn->commit();
                $success_text = ($user_designation === 'admin') ? "updated" : "saved";
                $message = "<div class='alert alert-success'>✅ Student activities have been {$success_text} successfully!</div>";
                if ($user_designation !== 'admin') $is_readonly = true;
            } else {
                $conn->rollback();
            }
            $stmt->close();
        }
    }
}
?>

<!-- Page content starts here -->
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><h4 class="card-title">Add / Edit Student Activity</h4></div>
        <div class="card-body">
            <?php if(!empty($message)) echo $message; ?>

            <?php if($active_cycle): ?>
            <div class="alert alert-info">
                <strong>Active Cycle:</strong> <?php echo htmlspecialchars($active_cycle['cycle_name']); ?>
                (Ends on: <?php echo (new DateTime($active_cycle['end_date']))->format('d-M-Y'); ?>)
            </div>
            <?php endif; ?>

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
                            foreach ($assigned_trades as $trade_name) {
                                $selected = ($trade_name == $selected_trade) ? 'selected' : '';
                                echo "<option value='" . htmlspecialchars($trade_name) . "' {$selected}>" . htmlspecialchars($trade_name) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4" style="margin-top: 28px;">
                        <button type="submit" name="find_students" class="btn btn-info" <?php if(!$active_cycle) echo 'disabled'; ?>><i class="fa fa-search"></i> Find Students</button>
                    </div>
                </div>
                <hr>

                <?php if (!empty($students)): ?>
                    <input type="hidden" name="active_cycle_id" value="<?php echo $active_cycle['cycle_id']; ?>">
                    <?php if ($is_readonly): ?>
                        <div class="alert alert-info text-center"><strong>Data Locked:</strong> Activities for this group have already been submitted for the current cycle and cannot be edited.</div>
                    <?php endif; ?>

                    <div class="table-responsive table-scrollable">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th><th>Student Name</th><th>Total Lesson</th><th>Total Demo</th><th>Total Practical</th><th>Total Test</th><th>Total TMP</th><th>Remarks</th>
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
                                    <td><input type="number" class="form-control" name="total_lesson[]" value="<?php echo $student['total_lesson'] ?? 0; ?>" min="0" required <?php if ($is_readonly) echo 'disabled'; ?>></td>
                                    <td><input type="number" class="form-control" name="total_demo[]" value="<?php echo $student['total_demo'] ?? 0; ?>" min="0" required <?php if ($is_readonly) echo 'disabled'; ?>></td>
                                    <td><input type="number" class="form-control" name="total_practical[]" value="<?php echo $student['total_practical'] ?? 0; ?>" min="0" required <?php if ($is_readonly) echo 'disabled'; ?>></td>
                                    <td><input type="number" class="form-control" name="total_test[]" value="<?php echo $student['total_test'] ?? 0; ?>" min="0" required <?php if ($is_readonly) echo 'disabled'; ?>></td>
                                    <td><input type="number" class="form-control" name="total_tmp[]" value="<?php echo $student['total_tmp'] ?? 0; ?>" min="0" required <?php if ($is_readonly) echo 'disabled'; ?>></td>
                                    <td><textarea class="form-control" name="remarks[]" <?php if ($is_readonly) echo 'disabled'; ?>><?php echo htmlspecialchars($student['remarks'] ?? ''); ?></textarea></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group row mt-3">
                        <div class="col-lg-12 text-center">
                            <?php if ($user_designation === 'admin'): ?>
                                <button type="submit" name="save_activities" class="btn btn-success"><i class="fa fa-save"></i> Update Activities</button>
                            <?php elseif (!$is_readonly): ?>
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
