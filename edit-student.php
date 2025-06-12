<?php
include('header.php');
include('connection.php');

// Check if ID is passed
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ Student ID is missing in the URL.");
}
$id = intval($_GET['id']);

// Fetch existing student details
$sql = "SELECT * FROM students WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("❌ No student found with this ID.");
}
$row = $result->fetch_assoc();
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Edit Student Details</h4>
        </div>
        <div class="card-body">
            <form action="backend/update-student.php" method="post">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">

                <!-- Attendence ID -->
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Attendence ID <span class="text-danger">*</span></label>
                    <div class="col-lg-6">
                        <input type="text" name="attendence_id" class="form-control" value="<?= htmlspecialchars($row['attendence_id']) ?>" required>
                    </div>
                </div>

                <!-- Name -->
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Name <span class="text-danger">*</span></label>
                    <div class="col-lg-6">
                        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($row['name']) ?>" required>
                    </div>
                </div>

                <!-- Trade -->
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Trade <span class="text-danger">*</span></label>
                    <div class="col-lg-6">
                        <input type="text" name="trade" class="form-control" value="<?= htmlspecialchars($row['trade']) ?>" required>
                    </div>
                </div>

                <!-- Program -->
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Program <span class="text-danger">*</span></label>
                    <div class="col-lg-6">
                        <label><input type="radio" name="program" value="CTS" <?= $row['program'] === 'CTS' ? 'checked' : '' ?>> CTS</label>
                        <label class="ml-3"><input type="radio" name="program" value="CITS" <?= $row['program'] === 'CITS' ? 'checked' : '' ?>> CITS</label>
                    </div>
                </div>

              

                <!-- Submit Button -->
                <div class="form-group row">
                    <div class="col-lg-8 ml-auto">
                        <button type="submit" name="submit" class="btn btn-primary">Update Student</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
