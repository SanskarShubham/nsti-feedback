<?php 
include('header.php');

// Fetch all records from subject table
$sql = "SELECT * FROM subject";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!-- content -->
<div class="container-fluid">
    <div class="card-header text-right">
        <a href="add-subject.php" class="btn btn-primary"><i class="fa fa-plus"></i> Add subject</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h4 class="card-title mb-3">Subject List</h4>

            <div class="table-responsive">
                <table class="table table-bordered table-striped verticle-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Subject Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['subject_id']) ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td>
                                    <a href="edit-subject.php?id=<?= $row['subject_id']; ?>" data-toggle="tooltip" title="Edit">
                                        <i class="fa fa-pencil color-muted m-r-10"></i>
                                    </a>
                                    <a href="backend/delete-subject.php?id=<?= $row['subject_id']; ?>" onclick="return confirm('Are you sure?')" data-toggle="tooltip" title="Delete">
                                        <i class="fa fa-close color-danger"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
