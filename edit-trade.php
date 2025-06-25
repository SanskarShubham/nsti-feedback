<?php
include('header.php');

// Check if ID is passed in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ trade ID is missing in the URL.");
}
$id = intval($_GET['id']); // Securely get ID

// Fetch trade details from DB
$sql = "SELECT * FROM trade WHERE trade_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("❌ No trade found with this ID.");
}

$row = $result->fetch_assoc(); // existing trade data
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="form-validation">
                <form action="backend/update-trade.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $row['trade_id'] ?>">
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Trade Name <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="text" name="tradename" class="form-control" value="<?= htmlspecialchars($row['trade_name']) ?>">
                        </div>
                    </div>                                   
                   

                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">PROGRAM <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <label class="mr-2">
                                   <input type="radio" value="CTS" name="program" <?= $row['program'] == "CTS" ? 'checked' : ''; ?>> CTS</label>
                            <label><input type="radio" value="CITS" name="program" <?= $row['program'] == "CITS" ? 'checked' : ''; ?>> CITS</label>
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
