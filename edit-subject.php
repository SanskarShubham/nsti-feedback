<?php
include('header.php');

// Check if ID is passed in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ subject ID is missing in the URL.");
}
$id = intval($_GET['id']); // Securely get ID

// Fetch subject details from DB
$sql = "SELECT * FROM subject WHERE subject_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("❌ No subject found with this ID.");
}

$row = $result->fetch_assoc(); // existing subject data
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="form-validation">
                <form action="backend/update-subject.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $row['subject_id'] ?>">
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Subject Name <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="text" name="subjectname" class="form-control" value="<?= htmlspecialchars($row['name']) ?>">
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
