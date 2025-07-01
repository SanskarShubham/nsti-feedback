<?php
include('header.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_data'])) {
    header("Location: admin_login.php");
    exit();
}

// Get logged-in teacher's ID
$teacher_id = $_SESSION['admin_data']['teacher_id'];

// Default values
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : 20;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filters (only showing date filter since others don't make sense for self-view)
$date = $_GET['date'] ?? '';
$rating = $_GET['rating'] ?? '';

// Build WHERE clause - always filter by current teacher
$where = "WHERE f.teacher_id = $teacher_id";
if ($rating !== '') $where .= " AND f.rating = '" . mysqli_real_escape_string($conn, $rating) . "'";
if ($date !== '') $where .= " AND DATE(f.created_at) = '" . mysqli_real_escape_string($conn, $date) . "'";

// Total count - fixed joins to match feedback with actual teacher assignments
$total_sql = "SELECT COUNT(DISTINCT f.id) AS total FROM feedback f
JOIN teachers t ON t.teacher_id = f.teacher_id
JOIN trade tr ON tr.trade_id = f.trade_id
JOIN subject s ON s.subject_id = f.subject_id
$where";
$total_result = mysqli_query($conn, $total_sql);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

// Data fetch - fixed joins to use the trade and subject IDs stored in feedback table
$sql = "SELECT f.*, t.name AS teacher_name, s.name AS subject_name, tr.trade_name 
FROM feedback f
JOIN teachers t ON t.teacher_id = f.teacher_id
JOIN trade tr ON tr.trade_id = f.trade_id
JOIN subject s ON s.subject_id = f.subject_id
$where
ORDER BY f.created_at DESC
LIMIT $offset, $limit";
$result = mysqli_query($conn, $sql);
?>

<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
            <h4><i class="fas fa-comments"></i> Feedback Received</h4>
        </div>
        <div class="card-body">

            <!-- Filter Form (simplified for self-view) -->
            <form method="GET" class="form-inline mb-3 flex-wrap gap-2">
                <select name="rating" class="form-control form-control-m mr-3 mb-2" style="max-width:120px;">
                    <option value="">All Ratings</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>" <?= $rating == $i ? 'selected' : '' ?>><?= $i ?> ⭐</option>
                    <?php endfor; ?>
                </select>
                <input type="date" name="date" value="<?= htmlspecialchars($date) ?>" class="form-control form-control-m mr-3 mb-2" style="max-width:150px;">

                <button type="submit" class="btn btn-success btn-m mr-3 mb-2">Filter</button>
                <a href="list-feedback.php" class="btn btn-danger btn-m mb-2">Reset</a>

                <!-- Show entries dropdown -->
                <div class="ml-auto d-flex align-items-center">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="limit">Show</label>
                        </div>
                        <select name="limit" id="limit" class="custom-select" onchange="this.form.submit()">
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
                            <th>##</th>
                            <th>Subject</th>
                            <th>Trade</th>
                            <th>Rating</th>
                            <th>Remarks</th>
                            <th>Submitted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1 + $offset; // Start numbering from correct position for pagination
                        if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($row['subject_name']) ?></td>
                                    <td><?= htmlspecialchars($row['trade_name']) ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?= str_repeat('⭐', $row['rating']) ?>
                                            <?php if ($row['rating'] <= 2): ?>
                                                <span class="badge badge-danger ml-2">Needs Improvement</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?= nl2br(htmlspecialchars($row['remarks'])) ?></td>
                                    <td><?= (new DateTime($row['created_at']))->format('d/m/y h:i A') ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center">No feedback found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">Previous</a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include('footer.php'); ?>