<?php include('header.php');

// Records per page (default: 20)
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : 20;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Total records and pages from feedback table
$total_query = "SELECT COUNT(*) AS total FROM feedback";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

// Fetch paginated feedback records
$sql = "SELECT * FROM feedback ORDER BY created_at DESC LIMIT $offset, $limit";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!-- content -->
<div class="container-fluid">
    <div class="card-header text-right">
        <!-- If you want to add a button for adding feedback manually -->
        <!-- <a href="add-feedback.php" class="btn btn-primary"><i class="fa fa-plus"></i> Add Feedback</a> -->
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title m-0">Feedback List</h4>
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
                            <th>ID</th>
                            <th>Student ID</th>
                            <th>Teacher Name</th>
                            <th>Subject Name</th>
                            <th>Trade</th>
                            <th>Rating</th>
                            <th>Remarks</th>
                            <th>Submitted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['attendance_id']) ?></td>
                                <td>
                                    <?php
                                    $teacher_query = "SELECT t.name AS teacher_name, tr.trade_name, s.name AS subject_name FROM teachers t
                                                      JOIN teacher_subject_trade tst ON t.teacher_id = tst.teacher_id
                                                      JOIN trade tr ON tst.trade_id = tr.trade_id
                                                      JOIN subject s ON tst.subject_id = s.subject_id
                                                      WHERE t.teacher_id = " . intval($row['teacher_id']);
                                    $teacher_result = mysqli_query($conn, $teacher_query);
                                    $teacher_row = mysqli_fetch_assoc($teacher_result);
                                    ?>
                                    <?= htmlspecialchars($teacher_row['teacher_name']) ?>
                                </td>
                                <td><?= htmlspecialchars($teacher_row['subject_name']) ?></td>
                                <td><?= htmlspecialchars($teacher_row['trade_name']) ?></td>
                                <td><?= htmlspecialchars($row['rating']) ?> ⭐</td>
                                <td><?= nl2br(htmlspecialchars($row['remarks'])) ?></td>
                                <td>
                                    <?php
                                    $dt = new DateTime($row['created_at']);
                                    echo $dt->format('d/m/y h:i:s A');
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>">Previous</a>
                        </li>
                    <?php endif; ?>

                    <?php
                        $start = max(1, $page - 2);
                        $end = min($total_pages, $page + 2);

                        if ($start > 1) {
                            echo '<li class="page-item"><a class="page-link" href="?page=1&limit=' . $limit . '">1</a></li>';
                            if ($start > 2) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }

                        for ($i = $start; $i <= $end; $i++): ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&limit=<?= $limit ?>"><?= $i ?></a>
                            </li>
                    <?php endfor;

                        if ($end < $total_pages) {
                            if ($end < $total_pages - 1) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            echo '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . '&limit=' . $limit . '">' . $total_pages . '</a></li>';
                        }
                    ?>

                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>
<!-- end content -->

<?php include('footer.php'); ?>
