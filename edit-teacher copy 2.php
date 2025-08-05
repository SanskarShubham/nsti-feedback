<?php
include('header.php');

// Check if ID is passed in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ Teacher ID is missing in the URL.");
}
$id = intval($_GET['id']);

// Fetch teacher details from DB
$sql = "SELECT * FROM teachers WHERE teacher_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("❌ No teacher found with this ID.");
}

$row = $result->fetch_assoc();
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="form-validation">
                <form action="backend/update-teacher.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $row['teacher_id'] ?>">

                    <!-- Basic Info Section -->
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Name <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($row['name']) ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Email <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email']) ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Mobile <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="text" name="mobile" class="form-control" value="<?= htmlspecialchars($row['mobile_no']) ?>">
                        </div>
                    </div>

                    <!-- Password Update Section -->
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">New Password</label>
                        <div class="col-lg-6">
                            <input type="password" name="new_password" class="form-control" placeholder="Leave blank to keep current password">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Confirm Password</label>
                        <div class="col-lg-6">
                            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Status <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <label class="mr-2">
                                <input type="radio" value="0" name="status" <?= $row['status'] == 0 ? 'checked' : ''; ?>> Inactive
                            </label>
                            <label>
                                <input type="radio" value="1" name="status" <?= $row['status'] == 1 ? 'checked' : ''; ?>> Active
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Designation <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <label class="mr-2">
                                <input type="radio" value="admin" name="designation" <?= $row['designation'] == 'admin' ? 'checked' : ''; ?>> Admin
                            </label>
                            
                            <label>
                                <input type="radio" value="other" name="designation" <?= $row['designation'] == 'other' ? 'checked' : ''; ?>> Teacher
                            </label>
                        </div>
                    </div>

                    <!-- Subject/Trade Section -->
                    <div id="subject-container">
                        <?php
                        // Prefill data for teacher_id
                        $sql = "SELECT tst.*, t.name AS teacher_name, s.name AS subject_name, tr.trade_name 
                                FROM teacher_subject_trade tst
                                JOIN teachers t ON t.teacher_id = tst.teacher_id
                                JOIN trade tr ON tst.trade_id = tr.trade_id
                                JOIN subject s ON tst.subject_id = s.subject_id
                                WHERE tst.teacher_id = $id";
                        $result = mysqli_query($conn, $sql);

                        // Fetch all trades and subjects for dropdown options
                        $all_trades = mysqli_query($conn, "SELECT trade_id, trade_name FROM trade ORDER BY trade_name ASC");
                        $all_subjects = mysqli_query($conn, "SELECT subject_id, name FROM subject ORDER BY name ASC");

                        // Convert to arrays
                        $trades = [];
                        while ($row = mysqli_fetch_assoc($all_trades)) {
                            $trades[] = $row;
                        }

                        $subjects = [];
                        while ($row = mysqli_fetch_assoc($all_subjects)) {
                            $subjects[] = $row;
                        }

                        $i = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <div class="form-group row subject-row">
                                <input type="hidden" name="teacher_subject_trade_id[]" value="<?= $row['id'] ?>">
                                <label class="col-lg-4 col-form-label">Program / Trade / Subject <span class="text-danger">*</span></label>
                                <div class="col-lg-2">
                                    <select class="form-control" name="program[]">
                                        <option value="">Select Program</option>
                                        <option value="CTS" <?= $row['program'] == 'CTS' ? 'selected' : '' ?>>CTS</option>
                                        <option value="CITS" <?= $row['program'] == 'CITS' ? 'selected' : '' ?>>CITS</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <select class="form-control" name="trade[]">
                                        <option value="">Select Trade</option>
                                        <?php foreach ($trades as $trade): ?>
                                            <option value="<?= $trade['trade_id'] ?>" <?= $trade['trade_id'] == $row['trade_id'] ? 'selected' : '' ?>>
                                                <?= $trade['trade_name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <select class="form-control" name="subject[]">
                                        <option value="">Select Subject</option>
                                        <?php foreach ($subjects as $subject): ?>
                                            <option value="<?= $subject['subject_id'] ?>" <?= $subject['subject_id'] == $row['subject_id'] ? 'selected' : '' ?>>
                                                <?= $subject['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <?php if ($i == 0): ?>
                                        <button type="button" class="btn btn-success" onclick="addSubjectRow(this)"><i class="fa fa-plus"></i> ADD</button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-danger" onclick="removeSubjectRow(this)"><i class="fa fa-minus"></i> Remove</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php
                            $i++;
                        }
                        ?>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-8 ml-auto">
                            <button type="submit" name="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
<script>
    function addSubjectRow(button) {
        let container = document.getElementById("subject-container");
        let originalRow = button.closest(".subject-row");
        let newRow = originalRow.cloneNode(true);

        newRow.querySelectorAll("select").forEach(select => select.value = "");

        let actionBtn = newRow.querySelector("button");
        actionBtn.innerText = "Remove";
        actionBtn.className = "btn btn-danger";
        actionBtn.setAttribute("onclick", "removeSubjectRow(this)");

        container.appendChild(newRow);
    }

    function removeSubjectRow(button) {
        let row = button.closest(".subject-row");
        row.remove();
    }
</script>