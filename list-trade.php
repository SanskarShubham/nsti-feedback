<?php 
include('header.php');

// Get records per page from GET or default to 5
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Count total records
$total_query = "SELECT COUNT(*) AS total FROM trade";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

// Fetch paginated records
$sql = "SELECT * FROM trade LIMIT $offset, $limit";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!-- content -->
<div class="container-fluid">
    <div class="card-header text-right">
        <a href="add-trade.php" class="btn btn-primary"><i class="fa fa-plus"></i> Add trade</a>
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title m-0">Trade List</h4>

                <!-- Records per page dropdown styled -->
                <form method="get" id="limitForm" class="form-inline">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="limit">Show</label>
                        </div>
                        <select name="limit" id="limit" class="custom-select" onchange="document.getElementById('limitForm').submit()">
                            <?php foreach ([5, 10, 20, 50, 100] as $opt): ?>
                                <option value="<?= $opt ?>" <?= $limit == $opt ? 'selected' : '' ?>><?= $opt ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="input-group-append">
                            <span class="input-group-text">entries</span>
                        </div>
                    </div>
                    <input type="hidden" name="page" value="1">
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped verticle-middle">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Trade Name</th>
                            <th>Program</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['trade_id']) ?></td>
                                <td><?= htmlspecialchars($row['trade_name']) ?></td>
                                <td>
                                    <?php 
                                        if ($row['program'] == "CTS") {
                                            echo "<span class='badge badge-pill badge-danger'>CTS</span>";
                                        } elseif ($row['program'] == "CITS") {
                                            echo "<span class='badge badge-pill badge-success'>CITS</span>";
                                        }
                                    ?>
                                </td>
                                <td>
                                    <a href="edit-trade.php?id=<?= $row['trade_id']; ?>" data-toggle="tooltip" title="Edit">
                                        <i class="fa fa-pencil color-muted m-r-10 "></i>
                                    </a>
                                    <a href="backend/delete-trade.php?id=<?= $row['trade_id']; ?>" onclick="return confirm('Are you sure?')" data-toggle="tooltip" title="Delete">
                                        <i class="fa fa-close color-danger"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>">Previous</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&limit=<?= $limit ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>">Next</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>
<!-- end content -->

<?php include('footer.php'); ?>
