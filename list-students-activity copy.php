<?php
// Use the same session and connection setup from your reference file
require_once 'connection.php';

// Include the header which contains the HTML head, nav, sidebar, and the opening <div class="content-body">
include('header.php'); 

// --- PHP LOGIC FOR FILTERING ---
$selected_program = '';
$selected_trade = '';
$activities = [];

// Base SQL query with JOIN to get student names. 
// The sa.* will automatically include the new 'remarks' column.
$sql = "SELECT sa.*, s.name as student_name, s.program, s.trade 
        FROM student_activity sa
        JOIN students s ON sa.student_id = s.id";

$where_clauses = [];
$bind_params = [];
$bind_types = '';

// Check if the filter form was submitted
if (isset($_POST['filter'])) {
    if (!empty($_POST['program'])) {
        $selected_program = $_POST['program'];
        $where_clauses[] = "s.program = ?";
        $bind_types .= 's';
        $bind_params[] = $selected_program;
    }
    if (!empty($_POST['trade'])) {
        $selected_trade = $_POST['trade'];
        $where_clauses[] = "s.trade = ?";
        $bind_types .= 's';
        $bind_params[] = $selected_trade;
    }
}

// Append WHERE clauses if any filters are active
if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(" AND ", $where_clauses);
}

$sql .= " ORDER BY s.name ASC";

$stmt = $conn->prepare($sql);

// Bind parameters if filters are used
if (!empty($bind_params)) {
    $stmt->bind_param($bind_types, ...$bind_params);
}

$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $activities[] = $row;
}
$stmt->close();
?>

<!-- CSS for Sticky Table Header -->
<style>
    .table-scrollable {
        max-height: 70vh;
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
</style>

<!-- Page content starts here -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">All Student Activities</h4>
                    
                    <!-- Filter Form -->
                    <form action="" method="POST" class="mb-4">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label for="program">Program</label>
                                <select class="form-control" id="program" name="program">
                                    <option value="">All Programs</option>
                                    <option value="CTS" <?php if ($selected_program == 'CTS') echo 'selected'; ?>>CTS</option>
                                    <option value="CITS" <?php if ($selected_program == 'CITS') echo 'selected'; ?>>CITS</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="trade">Trade</label>
                                <select class="form-control" id="trade" name="trade">
                                    <option value="">All Trades</option>
                                    <?php
                                    $trade_result = $conn->query("SELECT DISTINCT trade_name FROM trade ORDER BY trade_name ASC");
                                    while ($trade_row = $trade_result->fetch_assoc()) {
                                        $selected = ($trade_row['trade_name'] == $selected_trade) ? 'selected' : '';
                                        echo "<option value='{$trade_row['trade_name']}' {$selected}>{$trade_row['trade_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" name="filter" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                                <a href="list-students-activity.php" class="btn btn-secondary"><i class="fa fa-undo"></i> Reset</a>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Data Table -->
                    <div class="table-responsive table-scrollable">
                        <table class="table table-striped table-bordered zero-configuration">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student Name</th>
                                    <th>Program</th>
                                    <th>Trade</th>
                                    <th>Lesson</th>
                                    <th>Demo</th>
                                    <th>Practical</th>
                                    <th>Test</th>
                                    <th>TMP</th>
                                    <th>Remarks</th> <!-- MODIFICATION: Added Remarks Header -->
                                    <th>Last Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($activities)): ?>
                                    <?php foreach ($activities as $index => $activity): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($activity['student_name']); ?></td>
                                            <td><?php echo htmlspecialchars($activity['program']); ?></td>
                                            <td><?php echo htmlspecialchars($activity['trade']); ?></td>
                                            <td><?php echo $activity['total_lesson']; ?></td>
                                            <td><?php echo $activity['total_demo']; ?></td>
                                            <td><?php echo $activity['total_practical']; ?></td>
                                            <td><?php echo $activity['total_test']; ?></td>
                                            <td><?php echo $activity['total_tmp']; ?></td>
                                            <!-- MODIFICATION: Added Remarks Data Cell -->
                                            <td><?php echo htmlspecialchars($activity['remarks']); ?></td>
                                            <td><?php echo date('d-M-Y h:i A', strtotime($activity['updated_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <!-- MODIFICATION: Updated colspan to 11 -->
                                        <td colspan="11" class="text-center">No activity records found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include the footer which closes the <div class="content-body"> and other tags
include('footer.php');
?>
