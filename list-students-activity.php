<?php include('header.php');

// Filter Inputs
$name_filter = isset($_GET['name']) ? trim($_GET['name']) : '';
$trade_filter = isset($_GET['trade']) ? trim($_GET['trade']) : '';
$program_filter = isset($_GET['program']) ? trim($_GET['program']) : '';

// Prepare SQL WHERE clause
$where = [];
if (!empty($name_filter)) $where[] = "name LIKE '%" . mysqli_real_escape_string($conn, $name_filter) . "%'";
if (!empty($trade_filter)) {
    $trades = explode(',', $trade_filter);
    $escaped_trades = array_map(function ($t) use ($conn) {
        return "'" . mysqli_real_escape_string($conn, $t) . "'";
    }, $trades);
    $where[] = "trade IN (" . implode(',', $escaped_trades) . ")";
}
if (!empty($program_filter)) $where[] = "program = '" . mysqli_real_escape_string($conn, $program_filter) . "'";
$where_sql = !empty($where) ? "WHERE " . implode(" AND ", $where) : '';

// Pagination
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : 20;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Total records
$total_query = "SELECT COUNT(*) AS total FROM students $where_sql";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

// Fetch students
$sql = "SELECT * FROM students $where_sql LIMIT $offset, $limit";
$result = mysqli_query($conn, $sql);

// Get all trades for filter dropdown
$trades_query = "SELECT DISTINCT trade FROM students WHERE trade IS NOT NULL AND trade != ''";
$trades_result = mysqli_query($conn, $trades_query);
$available_trades = [];
while ($row = mysqli_fetch_assoc($trades_result)) {
    $available_trades[] = $row['trade'];
}

// Handle truncate action
if (isset($_GET['truncate']) && $_GET['truncate'] === 'true') {
    $sql = "TRUNCATE TABLE students";
    if ($conn->query($sql)) {
        echo "<script>alert('Students table truncated successfully.'); window.location.href='".basename($_SERVER['PHP_SELF'])."';</script>";
        exit;
    } else {
        echo "<script>alert('Error truncating table: " . $conn->error . "');</script>";
    }
}
?>

<!-- content -->
<div class="container-fluid">
    <div class="card-header text-right">
        <a href="add-student.php" class="btn btn-primary"><i class="fa fa-plus"></i> Add Student</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">

            <!-- Filter Form -->
            <form method="get" id="filterForm" class="form-row align-items-end mb-3">
                <div class="col-md-2">
                    <label>Student Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name_filter) ?>">
                </div>

                <div class="col-md-3">
                    <label>Trade</label>
                    <div class="form-control" style="height:auto; position:relative;">
                        <div id="customDropdown" class="w-100 p-2 border" style="cursor:pointer; background-color:#f8f9fa;" onclick="toggleTradeDropdown()">Select Trade</div>
                        <div id="tradeOptions" class="border rounded bg-white p-2" style="display:none; position:absolute; z-index:10; width:100%; max-height:150px; overflow-y:auto;">
                            <?php foreach ($available_trades as $trade): ?>
                                <div class="form-check">
                                    <input class="form-check-input trade-check" type="checkbox" value="<?= $trade ?>" id="trade_<?= $trade ?>" <?= in_array($trade, explode(',', $trade_filter)) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="trade_<?= $trade ?>"><?= $trade ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" name="trade" id="selectedTrade" value="<?= htmlspecialchars($trade_filter) ?>">
                    </div>
                </div>

                <div class="col-md-2">
                    <label>Program</label>
                    <select name="program" class="form-control">
                        <option value="">All</option>
                        <option value="CTS" <?= $program_filter == 'CTS' ? 'selected' : '' ?>>CTS</option>
                        <option value="CITS" <?= $program_filter == 'CITS' ? 'selected' : '' ?>>CITS</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-success w-100"><i class="fa fa-filter mr-1" ></i>Filter</button>
                </div>
                <div class="col-md-1">
                    <label>&nbsp;</label>
                    <a href="<?= basename($_SERVER['PHP_SELF']) ?>" class="btn btn-danger w-100"><i class="fa fa-refresh mr-1"></i>Reset</a>
                </div>
                
                <!-- Download Button -->
                <div class="col-md-1">
                    <label>&nbsp;</label>
                    <a href="download-students.php?<?= http_build_query($_GET) ?>" class="btn btn-primary w-100">
                        <i class="fa fa-download mr-1"></i> Download
                    </a>
                </div>
                
                <!-- Truncate Button - Only show if you really need this functionality -->
                <div class="col-md-1">
                    <label>&nbsp;</label>
                    <a href="?truncate=true" class="btn btn-danger w-100" onclick="return confirm('WARNING: This will delete ALL student records. Are you sure?')">
                        <i class="fa fa-trash mr-1"></i> Truncate
                    </a>
                </div>
            </form>

          

            <!-- Limit Dropdown -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title m-0">Students List</h4>
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

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped verticle-middle">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Attendance ID</th>
                            <th>Name</th>
                            <th>Trade</th>
                            <th>Program</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['attendance_id']) ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['trade']) ?></td>
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
                                    <a href="edit-student.php?id=<?= $row['id']; ?>" data-toggle="tooltip" title="Edit">
                                        <button class="btn btn-success"> <i class="fa fa-pencil color-muted m-r-10 "></i></button>
                                    </a>&nbsp;&nbsp;
                                    <a href="backend/delete-student.php?id=<?= $row['id']; ?>" onclick="return confirm('Are you sure?')" data-toggle="tooltip" title="Delete">
                                        <button class="btn btn-danger"> <i class="fa fa-close color-danger"></i></button>
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
                    $query_params['limit'] = $limit; // ensure limit always present

                    // Previous
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

                    // Next
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

<!-- JS Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function toggleTradeDropdown() {
        $('#tradeOptions').slideToggle(150);
    }

    $(function() {
        $('#filterForm').on('submit', function() {
            let selected = [];
            $('.trade-check:checked').each(function() {
                selected.push($(this).val());
            });
            $('#selectedTrade').val(selected.join(','));
        });

        // Close dropdown if click outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#customDropdown, #tradeOptions').length) {
                $('#tradeOptions').hide();
            }
        });
    });
</script>

<?php include('footer.php'); ?>