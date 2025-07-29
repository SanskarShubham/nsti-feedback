<?php
session_start();
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
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        if (!empty($cycle_name) && !empty($start_date) && !empty($end_date)) {
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

    // --- UPDATE CYCLE STATUS (ACTIVATE/DEACTIVATE) ---
    if (isset($_POST['update_status'])) {
        $cycle_id_to_update = $_POST['cycle_id'];
        $new_status = $_POST['new_status'];

        $conn->begin_transaction();
        try {
            // If activating a cycle, deactivate all others first to ensure only one is active.
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f4f7f6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: auto;
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .header {
            text-align: center;
            margin-bottom: 2rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 1rem;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0;
        }
        .section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #fdfdfd;
        }
        .section-title {
            font-size: 1.5rem;
            margin-top: 0;
            margin-bottom: 1.5rem;
            color: #34495e;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: .5rem;
            font-weight: 600;
        }
        .input-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        .input-control:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.2s, transform 0.1s;
        }
        .btn-primary {
            background-color: #3498db;
            color: white;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
        .btn:active {
            transform: translateY(1px);
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: 600;
        }
        .status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-active {
            background-color: #2ecc71;
            color: white;
        }
        .status-inactive {
            background-color: #e74c3c;
            color: white;
        }
        .status-pending {
             background-color: #f39c12;
            color: white;
        }
        .status-expired {
             background-color: #95a5a6;
            color: white;
        }
        .btn-activate {
            background-color: #27ae60;
            color: white;
        }
        .btn-deactivate {
            background-color: #c0392b;
            color: white;
        }
        .message {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 6px;
            font-weight: 500;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-cogs"></i> Manage Feedback Cycles</h1>
        </div>

        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="section">
            <h2 class="section-title"><i class="fas fa-plus-circle"></i> Create New Cycle</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="cycle_name">Cycle Name</label>
                    <input type="text" id="cycle_name" name="cycle_name" class="input-control" placeholder="e.g., Mid-Term Feedback 2025" required>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date & Time</label>
                    <input type="datetime-local" id="start_date" name="start_date" class="input-control" required>
                </div>
                <div class="form-group">
                    <label for="end_date">End Date & Time</label>
                    <input type="datetime-local" id="end_date" name="end_date" class="input-control" required>
                </div>
                <button type="submit" name="create_cycle" class="btn btn-primary"><i class="fas fa-save"></i> Create Cycle</button>
            </form>
        </div>

        <div class="section">
            <h2 class="section-title"><i class="fas fa-list-ul"></i> Existing Cycles</h2>
            <div class="table-container">
                <table>
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
                                    $status_class = 'status-active';
                                } elseif ($now > $end) {
                                    $status_text = 'Expired';
                                    $status_class = 'status-expired';
                                } elseif ($now < $start) {
                                    $status_text = 'Pending';
                                    $status_class = 'status-pending';
                                } else {
                                    $status_text = 'Inactive';
                                    $status_class = 'status-inactive';
                                }
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($cycle['cycle_name']); ?></td>
                                    <td><?php echo $start->format('d-M-Y, h:i A'); ?></td>
                                    <td><?php echo $end->format('d-M-Y, h:i A'); ?></td>
                                    <td><span class="status <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                    <td>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="cycle_id" value="<?php echo $cycle['cycle_id']; ?>">
                                            <?php if ($cycle['is_active'] == 1): ?>
                                                <input type="hidden" name="new_status" value="0">
                                                <button type="submit" name="update_status" class="btn btn-deactivate" title="Deactivate this cycle">
                                                    <i class="fas fa-times-circle"></i> Deactivate
                                                </button>
                                            <?php else: ?>
                                                <input type="hidden" name="new_status" value="1">
                                                <button type="submit" name="update_status" class="btn btn-activate" title="Activate this cycle (will deactivate any other active cycle)">
                                                    <i class="fas fa-check-circle"></i> Activate
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
