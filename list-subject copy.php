<?php 
include('header.php');

// Filter values from GET
$filter_subject_name = isset($_GET['subject_name']) ? trim($_GET['subject_name']) : '';
$filter_program = isset($_GET['program']) ? trim($_GET['program']) : '';

// Pagination setup
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : 20;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Build WHERE clause based on filters
$where = "WHERE 1=1";
if ($filter_subject_name != '') {
    $where .= " AND subject_name LIKE '%" . mysqli_real_escape_string($conn, $filter_subject_name) . "%'";
}
if ($filter_program != '') {
    $where .= " AND program = '" . mysqli_real_escape_string($conn, $filter_program) . "'";
}

// Count total filtered records
$total_query = "SELECT COUNT(*) AS subject FROM subject $where";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

// Fetch filtered, paginated records
$sql = "SELECT * FROM subject $where LIMIT $offset, $limit";
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
            <form method="get" class="form-inline mb-3">
                <input type="text" name="subject_name" class="form-control mr-2" placeholder="Search subject Name" value="<?= htmlspecialchars($filter_subject_name) ?>">

                <select name="program" class="form-control mr-2">
                    <option value="">All Programs</option>
                    <option value="CTS" <?= $filter_program == 'CTS' ? 'selected' : '' ?>>CTS</option>
                    <option value="CITS" <?= $filter_program == 'CITS' ? 'selected' : '' ?>>CITS</option>
                </select>

                <button type="submit" class="btn btn-success mr-2">Filter</button>
                <a href="list-subject.php" class="btn btn-danger">Reset</a>
            </form>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title m-0">subject List</h4>

                <form method="get" id="limitForm" class="form-inline">
                    <?php
                    // Preserve filters in limit form
                    foreach ($_GET as $key => $value) {
                        if ($key != 'limit' && $key != 'page') {
                            echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                        }
                    }
                    ?>
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
                                        <i class="fa fa-pencil color-muted m-r-10 "></i>
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

            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php
                    $base_url = basename($_SERVER['PHP_SELF']) . "?";
                    $query_params = $_GET;
                    $query_params['limit'] = $limit;

                    if ($page > 1) {
                        $query_params['page'] = $page - 1;
                        echo '<li class="page-item"><a class="page-link" href="' . $base_url . http_build_query($query_params) . '">Previous</a></li>';
                    }

                    $start = max(1, $page - 2);
                    $end = min($total_pages, $page + 2);

                    if ($start > 1) {
                        $query_params['page'] = 1;
                        echo '<li class="page-item"><a class="page-link" href="' . $base_url . http_build_query($query_params) . '">1</a></li>';
                        if ($start > 2) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }

                    for ($i = $start; $i <= $end; $i++) {
                        $query_params['page'] = $i;
                        echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '">
                            <a class="page-link" href="' . $base_url . http_build_query($query_params) . '">' . $i . '</a>
                        </li>';
                    }

                    if ($end < $total_pages) {
                        if ($end < $total_pages - 1) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        $query_params['page'] = $total_pages;
                        echo '<li class="page-item"><a class="page-link" href="' . $base_url . http_build_query($query_params) . '">' . $total_pages . '</a></li>';
                    }

                    if ($page < $total_pages) {
                        $query_params['page'] = $page + 1;
                        echo '<li class="page-item"><a class="page-link" href="' . $base_url . http_build_query($query_params) . '">Next</a></li>';
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
