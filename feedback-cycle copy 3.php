<?php
include 'header.php';
// Set the default timezone to Asia/Kolkata
date_default_timezone_set('Asia/Kolkata');
include 'connection.php';
// Set the timezone for the database connection as well
$conn->query("SET time_zone = '+05:30'");

$message = '';
$message_type = '';

// --- 1. HANDLE FORM SUBMISSIONS (POST REQUESTS) ---

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // --- CREATE A NEW CYCLE ---
    if (isset($_POST['create_cycle'])) {
        $cycle_name = $_POST['cycle_name'];
        // Append time to date for full-day coverage
        $start_date = $_POST['start_date'] . ' 00:00:00';
        $end_date = $_POST['end_date'] . ' 23:59:59';

        if (!empty($cycle_name) && !empty($_POST['start_date']) && !empty($_POST['end_date'])) {
            if ($start_date >= $end_date) {
                $message = "Error: Start date must be before the end date.";
                $message_type = 'error';
            } else {
                $stmt = $conn->prepare("INSERT INTO feedback_cycle (cycle_name, start_date, end_date, is_active) VALUES (?, ?, ?, 0)");
                $stmt->bind_param("sss", $cycle_name, $start_date, $end_date);
                if ($stmt->execute()) {
                    $message = "Feedback cycle created successfully!";
                    $message_type = 'success';
                } else {
                    $message = "Error creating cycle: " . $conn->error;
                    $message_type = 'error';
                }
                $stmt->close();
            }
        } else {
            $message = "Please fill in all fields to create a cycle.";
            $message_type = 'error';
        }
    }

    // --- EDIT AN EXISTING CYCLE ---
    if (isset($_POST['edit_cycle'])) {
        $cycle_id = $_POST['edit_cycle_id'];
        $cycle_name = $_POST['edit_cycle_name'];
        // Append time to date for full-day coverage
        $start_date = $_POST['edit_start_date'] . ' 00:00:00';
        $end_date = $_POST['edit_end_date'] . ' 23:59:59';

        if (!empty($cycle_name) && !empty($_POST['edit_start_date']) && !empty($_POST['edit_end_date']) && !empty($cycle_id)) {
             if ($start_date >= $end_date) {
                $message = "Error: Start date must be before the end date.";
                $message_type = 'error';
            } else {
                $stmt = $conn->prepare("UPDATE feedback_cycle SET cycle_name = ?, start_date = ?, end_date = ? WHERE cycle_id = ?");
                $stmt->bind_param("sssi", $cycle_name, $start_date, $end_date, $cycle_id);
                 if ($stmt->execute()) {
                    $message = "Feedback cycle updated successfully!";
                    $message_type = 'success';
                } else {
                    $message = "Error updating cycle: " . $conn->error;
                    $message_type = 'error';
                }
                $stmt->close();
            }
        } else {
             $message = "Please fill in all fields to edit the cycle.";
             $message_type = 'error';
        }
    }

    // --- UPDATE CYCLE STATUS (ACTIVATE/DEACTIVATE) ---
    if (isset($_POST['update_status'])) {
        $cycle_id_to_update = $_POST['cycle_id'];
        $new_status = $_POST['new_status'];

        $conn->begin_transaction();
        try {
            // If activating a cycle, deactivate all others first.
            if ($new_status == 1) {
                $conn->query("UPDATE feedback_cycle SET is_active = 0");
            }
            
            // Now, update the selected cycle's status.
            $stmt = $conn->prepare("UPDATE feedback_cycle SET is_active = ? WHERE cycle_id = ?");
            $stmt->bind_param("ii", $new_status, $cycle_id_to_update);
            $stmt->execute();
            
            $conn->commit();
            $message = "Cycle status updated successfully.";
            $message_type = 'success';
        } catch (mysqli_sql_exception $exception) {
            $conn->rollback();
            $message = "Error updating status: " . $exception->getMessage();
            $message_type = 'error';
        }
        $stmt->close();
    }
}

// --- 2. FETCH ALL CYCLES FOR DISPLAY ---
$cycles = [];
$result = $conn->query("SELECT * FROM feedback_cycle ORDER BY start_date DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $cycles[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Feedback Cycles</title>
    <!-- Link to your main CSS file -->
    <link href="css/style.css" rel="stylesheet">
    <style>
        /* Custom styles for Modal */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1050; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: .625rem;
            position: relative;
        }

        .modal-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e9ecef;
        }

        .modal-body {
            padding: 1.25rem;
        }
        
        .modal-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid #e9ecef;
            text-align: right;
        }

        .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-btn:hover,
        .close-btn:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .btn-group-sm > .btn, .btn-sm {
            padding: .25rem .5rem !important;
            font-size: .875rem;
            line-height: 1.5;
            border-radius: .2rem;
        }
        .beautiful-title {
            text-align: center;
            font-weight: 700;
            margin-bottom: 2rem;
            font-size: 2.25rem;
            background-image: linear-gradient(230deg, #759bff, #843cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body>
    <div id="main-wrapper" class="show">
        <div class="content-body" style="margin-left: 0;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                              <h4 class="beautiful-title">Manage Feedback Cycles</h4>

                                <?php if ($message): ?>
                                    <div class="alert alert-<?php echo $message_type === 'success' ? 'success' : 'danger'; ?>">
                                        <?php echo $message;
                                         ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Create New Cycle Form -->
                                <div class="card">
                                    <div class="card-header">
                                          
                                        <h5 class="card-title">Create New Cycle</h5>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST">
                                            <div class="form-group">
                                                <label for="cycle_name">Cycle Name</label>
                                                <input type="text" id="cycle_name" name="cycle_name" class="form-control" placeholder="e.g., Mid-Term Feedback 2025" required>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="start_date">Start Date</label>
                                                    <input type="date" id="start_date" name="start_date" class="form-control" required>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="end_date">End Date</label>
                                                    <input type="date" id="end_date" name="end_date" class="form-control" required>
                                                </div>
                                            </div>
                                            <button type="submit" name="create_cycle" class="btn btn-primary"><i class="fa fa-save"></i> Create Cycle</button>
                                        </form>
                                    </div>
                                </div>


                                <!-- Existing Cycles Table -->
                                <div class="table-responsive">
                                    <h5 class="mt-4">Existing Cycles</h5>
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>Cycle Name</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($cycles)): ?>
                                                <tr>
                                                    <td colspan="5" style="text-align: center;">No feedback cycles found.</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($cycles as $cycle): 
                                                    $start = new DateTime($cycle['start_date']);
                                                    $end = new DateTime($cycle['end_date']);
                                                    $now = new DateTime();
                                                    
                                                    $status_text = '';
                                                    $status_class = '';

                                                    if ($cycle['is_active'] == 1 && $now >= $start && $now <= $end) {
                                                        $status_text = 'Active';
                                                        $status_class = 'badge-success';
                                                    } elseif ($now > $end) {
                                                        $status_text = 'Expired';
                                                        $status_class = 'badge-secondary';
                                                    } elseif ($now < $start && $cycle['is_active'] == 1) {
                                                        $status_text = 'Pending';
                                                        $status_class = 'badge-warning';
                                                    } else {
                                                        $status_text = 'Inactive';
                                                        $status_class = 'badge-danger';
                                                    }
                                                ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($cycle['cycle_name']); ?></td>
                                                        <td><?php echo $start->format('d-M-Y'); ?></td>
                                                        <td><?php echo $end->format('d-M-Y'); ?></td>
                                                        <td><span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                                        <td>
                                                            <form method="POST" style="display: inline-block;">
                                                                <input type="hidden" name="cycle_id" value="<?php echo $cycle['cycle_id']; ?>">
                                                                <?php if ($cycle['is_active'] == 1): ?>
                                                                    <input type="hidden" name="new_status" value="0">
                                                                    <button type="submit" name="update_status" class="btn btn-danger btn-sm" title="Deactivate this cycle">
                                                                        Deactivate
                                                                    </button>
                                                                <?php else: ?>
                                                                    <input type="hidden" name="new_status" value="1">
                                                                    <button type="submit" name="update_status" class="btn btn-success btn-sm" title="Activate this cycle">
                                                                        Activate
                                                                    </button>
                                                                <?php endif; ?>
                                                            </form>
                                                            <button class="btn btn-info btn-sm" onclick="openEditModal(
                                                                '<?php echo $cycle['cycle_id']; ?>',
                                                                '<?php echo htmlspecialchars(addslashes($cycle['cycle_name']), ENT_QUOTES); ?>',
                                                                '<?php echo $start->format('Y-m-d'); ?>',
                                                                '<?php echo $end->format('Y-m-d'); ?>'
                                                            )">Edit</button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Cycle Modal -->
    <div id="editCycleModal" class="modal">
        <div class="modal-content card">
            <div class="modal-header">
                <h5 class="modal-title">Edit Feedback Cycle</h5>
                <span class="close-btn" onclick="closeEditModal()">&times;</span>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" id="edit_cycle_id" name="edit_cycle_id">
                    <div class="form-group">
                        <label for="edit_cycle_name">Cycle Name</label>
                        <input type="text" id="edit_cycle_name" name="edit_cycle_name" class="form-control" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="edit_start_date">Start Date</label>
                            <input type="date" id="edit_start_date" name="edit_start_date" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit_end_date">End Date</label>
                            <input type="date" id="edit_end_date" name="edit_end_date" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" name="edit_cycle" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('editCycleModal');

        function openEditModal(id, name, start, end) {
            document.getElementById('edit_cycle_id').value = id;
            document.getElementById('edit_cycle_name').value = name;
            document.getElementById('edit_start_date').value = start;
            document.getElementById('edit_end_date').value = end;
            modal.style.display = "block";
        }

        function closeEditModal() {
            modal.style.display = "none";
        }

        // Close modal if user clicks outside of it
        window.onclick = function(event) {
            if (event.target == modal) {
                closeEditModal();
            }
        }

        // Automatically hide the alert message after 5 seconds
        window.setTimeout(function() {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000); // 5000 milliseconds = 5 seconds
    </script>
    <?php include 'footer.php'; ?>
</body>
</html>
