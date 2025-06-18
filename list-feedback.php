<?php
include('header.php');

// Default values
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : 20;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filters
$teacher = $_GET['teacher'] ?? '';
$subject = $_GET['subject'] ?? '';
$trade = $_GET['trade'] ?? '';
$rating = $_GET['rating'] ?? '';
$date = $_GET['date'] ?? '';

// Build WHERE clause
$where = "WHERE 1";
if ($teacher !== '') $where .= " AND t.name LIKE '%" . mysqli_real_escape_string($conn, $teacher) . "%'";
if ($subject !== '') $where .= " AND s.name LIKE '%" . mysqli_real_escape_string($conn, $subject) . "%'";
if ($trade !== '') $where .= " AND tr.trade_name = '" . mysqli_real_escape_string($conn, $trade) . "'";
if ($rating !== '') $where .= " AND f.rating = '" . mysqli_real_escape_string($conn, $rating) . "'";
if ($date !== '') $where .= " AND DATE(f.created_at) = '" . mysqli_real_escape_string($conn, $date) . "'";

// Total count
$total_sql = "SELECT COUNT(*) AS total FROM feedback f
JOIN teachers t ON t.teacher_id = f.teacher_id
JOIN teacher_subject_trade tst ON tst.teacher_id = t.teacher_id
JOIN trade tr ON tst.trade_id = tr.trade_id
JOIN subject s ON tst.subject_id = s.subject_id
$where";
$total_result = mysqli_query($conn, $total_sql);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

// Data fetch
$sql = "SELECT f.*, t.name AS teacher_name, s.name AS subject_name, tr.trade_name FROM feedback f
JOIN teachers t ON t.teacher_id = f.teacher_id
JOIN teacher_subject_trade tst ON tst.teacher_id = t.teacher_id
JOIN trade tr ON tst.trade_id = tr.trade_id
JOIN subject s ON tst.subject_id = s.subject_id
$where
GROUP BY f.id
ORDER BY f.created_at DESC
LIMIT $offset, $limit";
$result = mysqli_query($conn, $sql);

// Fetch trade list for dropdown
$trade_list = mysqli_query($conn, "SELECT DISTINCT trade_name FROM trade");
?>

<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-body">

            <!-- Filter Form -->
            <form method="GET" class="form-inline mb-3 flex-wrap gap-2">
                <input type="text" name="teacher" value="<?= htmlspecialchars($teacher) ?>" class="form-control form-control-m mr-3 mb-2" placeholder="Teacher Name" style="max-width:150px;">
                <input type="text" name="subject" value="<?= htmlspecialchars($subject) ?>" class="form-control form-control-m mr-3 mb-2" placeholder="Subject Name" style="max-width:150px;">
                <select name="trade" class="form-control form-control-m mr-3 mb-2" style="max-width:200px;">
                    <option value="">Select Trade</option>
                    <?php while ($t = mysqli_fetch_assoc($trade_list)): ?>
                        <option value="<?= $t['trade_name'] ?>" <?= $trade == $t['trade_name'] ? 'selected' : '' ?>>
                            <?= $t['trade_name'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <select name="rating" class="form-control form-control-m mr-3 mb-2" style="max-width:120px;">
                    <option value="">Rating</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>" <?= $rating == $i ? 'selected' : '' ?>><?= $i ?> ⭐</option>
                    <?php endfor; ?>
                </select>
                <input type="date" name="date" value="<?= htmlspecialchars($date) ?>" class="form-control form-control-m mr-3 mb-2" style="max-width:150px;">

                <button type="submit" class="btn btn-success btn-m mr-3 mb-2">Filter</button>
                <a href="list-feedback.php" class="btn btn-danger btn-m mb-2">Reset</a>

                <!-- Show entries dropdown to the right -->
                <div class="ml-auto d-flex align-items-center">
                    <div class="input-group">
                    <div class="input-group-prepend">
                            <label class="input-group-text" for="limit">Show</label>
                        </div>
                    <select name="limit" id="limit" class="custom-select" onchange="this.form.submit()" >
                        <?php foreach ([5, 10, 20, 50, 100] as $opt): ?>
                            <option value="<?= $opt ?>" <?= $limit == $opt ? 'selected' : '' ?>><?= $opt ?></option>
                        <?php endforeach; ?>
                    </select>
                     <div class="input-group-append">
                            <span class="input-group-text">entries</span>
                        </div>
                    </div>
                    <input type="hidden" name="page" value="1">
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped verticle-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student ID</th>
                            <th>Teacher</th>
                            <th>Subject</th>
                            <th>Trade</th>
                            <th>Rating</th>
                            <th>Remarks</th>
                            <th>Submitted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= $row['attendance_id'] ?></td>
                                    <td><?= htmlspecialchars($row['teacher_name']) ?></td>
                                    <td><?= htmlspecialchars($row['subject_name']) ?></td>
                                    <td><?= htmlspecialchars($row['trade_name']) ?></td>
                                    <td><?= $row['rating'] ?> ⭐</td>
                                    <td><?= nl2br(htmlspecialchars($row['remarks'])) ?></td>
                                    <td><?= (new DateTime($row['created_at']))->format('d/m/y h:i A') ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="8" class="text-center">No feedback found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item"><a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">Previous</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages): ?>
                        <li class="page-item"><a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">Next</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

        </div>
    </div>
</div>

<?php include('footer.php'); ?>
