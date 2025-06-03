<?php include('header.php');


// Query to fetch all rows
$sql = "SELECT * FROM admin";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}


?>
<!-- content -->
<div class="container-fluid">
    <div class="card-header text-right">
        <a href="add-admin.php" class="btn btn-primary"><i class="fa fa-plus"></i> Add Admin</a>
    </div>
    <div class="card mb-3">

        <div class="card-body">
            <h4 class="card-title">Admin List</h4>
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
                        <?php
                        // Loop through rows
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['mobile']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            if ($row['status'] == 0) {
                                echo "<td><span class='badge badge-pill badge-danger'>Inactive</span></td>";
                            } else {
                                echo "<td><span class='badge badge-pill badge-success'>Active</span></td>";
                            } ?>
                            </td>
                            <td><span>
                                    &nbsp;&nbsp;
                                    <a href="<?php echo 'edit-admin.php?id=' . $row['id']; ?>" class="m-r-10" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil color-muted m-r-10 "></i> </a>&nbsp;&nbsp;
                                    <a onclick="return confirm('Are you sure?')" href="<?php echo 'backend/delete-admin.php?id=' . $row['id']; ?>" class="" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-close color-danger"></i></a>
                                </span></td>

                            </tr>
                        <?php }


                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<!-- end content -->
<?php include('footer.php'); ?>