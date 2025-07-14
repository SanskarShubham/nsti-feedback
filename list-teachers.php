<?php include('header.php');

// Get filter values
$nameFilter = isset($_GET['name']) ? trim($_GET['name']) : '';
$mobileFilter = isset($_GET['mobile']) ? trim($_GET['mobile']) : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';

// Build WHERE clause
$where = ["designation = 'other'"]; // Base condition to only show 'other' designation
if (!empty($nameFilter)) {
    $where[] = "name LIKE '%" . mysqli_real_escape_string($conn, $nameFilter) . "%'";
}
if (!empty($mobileFilter)) {
    $where[] = "mobile_no LIKE '%" . mysqli_real_escape_string($conn, $mobileFilter) . "%'";
}
if ($statusFilter !== '') {
    $where[] = "status = '" . mysqli_real_escape_string($conn, $statusFilter) . "'";
}
$whereClause = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Query
$sql = "SELECT * FROM teachers $whereClause";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!-- content -->
<div class="container-fluid">
    <div class="card-header text-right">
        <a href="add-teacher.php" class="btn btn-primary"><i class="fa fa-plus"></i> Add Teacher</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h4 class="card-title">Teachers List (Other Designation)</h4>

            <!-- Filter Form -->
            <form method="get" class="form-row mb-4">
                <div class="col-md-3 mb-2">
                    <input type="text" name="name" class="form-control" placeholder="Teacher Name" value="<?= htmlspecialchars($nameFilter) ?>">
                </div>
                <div class="col-md-3 mb-2">
                    <input type="text" name="mobile" class="form-control" placeholder="Mobile Number" value="<?= htmlspecialchars($mobileFilter) ?>">
                </div>
                <div class="col-md-3 mb-2">
                    <select name="status" class="form-control">
                        <option value="">-- Select Status --</option>
                        <option value="1" <?= $statusFilter === '1' ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= $statusFilter === '0' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2 d-flex">
                    <button type="submit" class="btn btn-success mr-2"><i class="fa fa-filter mr-1" ></i>Filter</button>
                    <a href="list-teachers.php" class="btn btn-danger"><i class="fa fa-refresh mr-1"></i>Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped verticle-middle">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Name</th>
                            <th scope="col">Mobile</th>
                            <th scope="col">Email</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['teacher_id']) ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['mobile_no']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td>
                                        <?php if ($row['status'] == 0): ?>
                                            <span class='badge badge-pill badge-danger'>Inactive</span>
                                        <?php else: ?>
                                            <span class='badge badge-pill badge-success'>Active</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="edit-teacher.php?id=<?= $row['teacher_id']; ?>" data-toggle="tooltip" title="Edit">
                                             <button class="btn btn-success"> <i class="fa fa-pencil color-muted m-r-10 "></i></button>
                                        </a>&nbsp;&nbsp;
                                        <a onclick="return confirm('Are you sure?')" href="backend/delete-teacher.php?id=<?= $row['teacher_id']; ?>" data-toggle="tooltip" title="Delete">
                                            <button class="btn btn-danger"> <i class="fa fa-close color-danger"></i></button>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center text-danger">No records found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
<!-- end content -->

<?php include('footer.php'); ?>