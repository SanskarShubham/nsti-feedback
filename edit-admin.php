<?php
include('header.php');

// Check if ID is passed in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ Admin ID is missing in the URL.");
}
$id = intval($_GET['id']); // Securely get ID

// Fetch admin details from DB
$sql = "SELECT * FROM teachers WHERE teacher_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("❌ No admin found with this ID.");
}

$row = $result->fetch_assoc(); // existing admin data
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="form-validation">
                <form action="backend/update-admin.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $row['teacher_id'] ?>">
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Username <span class="text-danger">*</span></label>
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

                    
                   

                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Status <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <label class="mr-2">
                                   <input type="radio" value="0" name="status" <?= $row['status'] == 0 ? 'checked' : ''; ?>> Inactive</label>
                            <label><input type="radio" value="1" name="status" <?= $row['status'] == 1 ? 'checked' : ''; ?>> Active</label>
                        </div>
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
