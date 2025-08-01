<?php
// Use the same session and connection setup from your reference file
require_once 'connection.php';

// Include the header which contains the HTML head, nav, sidebar, and the opening <div class="content-body">
include('header.php'); 

// --- ROLE-BASED DATA FETCHING ---

$user_designation = $_SESSION['admin_data']['designation'];
$teacher_id = $_SESSION['admin_data']['teacher_id'];
$available_trades = []; // This will hold the trades the user is allowed to see/filter

// If the user is an admin, they can see all trades.
if ($user_designation === 'admin') {
    $trade_result = $conn->query("SELECT DISTINCT trade_name FROM trade ORDER BY trade_name ASC");
    while ($trade_row = $trade_result->fetch_assoc()) {
        $available_trades[] = $trade_row['trade_name'];
    }
} 
// If the user is a teacher, fetch only their assigned trades.
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
        $available_trades[] = $row_trade['trade_name'];
    }
    $stmt_trades->close();
}


// --- PHP LOGIC FOR FILTERING ---
$selected_program = '';
$selected_trade = '';
$activities = [];

// Base SQL query
$sql = "SELECT sa.*, s.name as student_name, s.program, s.trade 
        FROM student_activity sa
        JOIN students s ON sa.student_id = s.id";

$where_clauses = [];
$bind_params = [];
$bind_types = '';

// SECURITY: If the user is a teacher, add a clause to only show their trades.
if ($user_designation !== 'admin') {
    if (!empty($available_trades)) {
        // Create a string of placeholders (?,?,?) for the IN clause
        $placeholders = implode(',', array_fill(0, count($available_trades), '?'));
        $where_clauses[] = "s.trade IN ($placeholders)";
        $bind_types .= str_repeat('s', count($available_trades));
        $bind_params = array_merge($bind_params, $available_trades);
    } else {
        // If teacher has no trades, add a condition that will return no results
        $where_clauses[] = "1 = 0"; 
    }
}

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
        // Security check: ensure the selected trade is one the user is allowed to see
        if (in_array($selected_trade, $available_trades)) {
            $where_clauses[] = "s.trade = ?";
            $bind_types .= 's';
            $bind_params[] = $selected_trade;
        }
    }
}

// Append WHERE clauses if any filters are active
if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(" AND ", $where_clauses);
}

$sql .= " ORDER BY s.name ASC";

$stmt = $conn->prepare($sql);

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
                                    // MODIFICATION: The dropdown is now populated with only the trades the user is allowed to see
                                    foreach ($available_trades as $trade_name) {
                                        $selected = ($trade_name == $selected_trade) ? 'selected' : '';
                                        echo "<option value='" . htmlspecialchars($trade_name) . "' {$selected}>" . htmlspecialchars($trade_name) . "</option>";
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
                                    <th>Remarks</th>
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
                                            <td><?php echo htmlspecialchars($activity['remarks']); ?></td>
                                            <td><?php echo date('d-M-Y h:i A', strtotime($activity['updated_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="11" class="text-center">No activity records found for your assigned trades.</td>
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
