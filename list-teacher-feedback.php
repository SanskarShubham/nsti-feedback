<?php
include('header.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_data'])) {
    header("Location: admin_login.php");
    exit();
}

// Get logged-in teacher's ID with validation
$teacher_id = (int)$_SESSION['admin_data']['teacher_id'];
if ($teacher_id <= 0) {
    die("Invalid teacher ID");
}

// Default values with validation
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : 20;
$limit = max(5, min(100, $limit)); // Ensure limit is between 5 and 100
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Ensure page is at least 1
$offset = ($page - 1) * $limit;

// Filters with validation
$date = $_GET['date'] ?? '';
if ($date && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    $date = '';
}

$rating = $_GET['rating'] ?? '';
if ($rating !== '' && (!is_numeric($rating) || $rating < 1 || $rating > 5)) {
    $rating = '';
}

// Build WHERE clause with prepared statements
$where = "WHERE f.teacher_id = ?";
$params = [$teacher_id];
$types = "i";

if ($rating !== '') {
    $where .= " AND f.rating = ?";
    $params[] = $rating;
    $types .= "i";
}

if ($date !== '') {
    $where .= " AND DATE(f.created_at) = ?";
    $params[] = $date;
    $types .= "s";
}

// Total count with prepared statement
$total_sql = "SELECT COUNT(DISTINCT f.id) AS total FROM feedback f
JOIN teachers t ON t.teacher_id = f.teacher_id
JOIN trade tr ON tr.trade_id = f.trade_id
JOIN subject s ON s.subject_id = f.subject_id
$where";

$stmt = mysqli_prepare($conn, $total_sql);
if ($stmt === false) {
    die("Database error: " . mysqli_error($conn));
}

if ($params) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

if (!mysqli_stmt_execute($stmt)) {
    die("Database error: " . mysqli_stmt_error($stmt));
}

$count_result = mysqli_stmt_get_result($stmt);
$count_row = mysqli_fetch_assoc($count_result);
$total_records = $count_row['total'] ?? 0;
$total_pages = ceil($total_records / $limit);

// Data fetch with prepared statement
$sql = "SELECT f.*, t.name AS teacher_name, s.name AS subject_name, tr.trade_name 
FROM feedback f
JOIN teachers t ON t.teacher_id = f.teacher_id
JOIN trade tr ON tr.trade_id = f.trade_id
JOIN subject s ON s.subject_id = f.subject_id
$where
ORDER BY f.created_at DESC
LIMIT ?, ?";

$stmt = mysqli_prepare($conn, $sql);
if ($stmt === false) {
    die("Database error: " . mysqli_error($conn));
}

// Add limit and offset to params
$params[] = $offset;
$params[] = $limit;
$types .= "ii";

mysqli_stmt_bind_param($stmt, $types, ...$params);

if (!mysqli_stmt_execute($stmt)) {
    die("Database error: " . mysqli_stmt_error($stmt));
}

$result = mysqli_stmt_get_result($stmt);
?>

<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
            <h4><i class="fa fa-comments"></i> Feedback Received</h4>
        </div>
        <div class="card-body">

            <!-- Filter Form (simplified for self-view) -->
            <form method="GET" class="form-inline mb-3 flex-wrap gap-2">
                <select name="rating" class="form-control form-control-m mr-3 mb-2" style="max-width:120px;">
                    <option value="">All Ratings</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= htmlspecialchars($i) ?>" <?= $rating == $i ? 'selected' : '' ?>><?= $i ?> ⭐</option>
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
                                <option value="<?= htmlspecialchars($opt) ?>" <?= $limit == $opt ? 'selected' : '' ?>><?= htmlspecialchars($opt) ?></option>
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
                        $i = 1 + $offset;
                        if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($i++) ?></td>
                                    <td><?= htmlspecialchars($row['subject_name']) ?></td>
                                    <td><?= htmlspecialchars($row['trade_name']) ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?= str_repeat('⭐', (int)$row['rating']) ?>
                                            <?php if ($row['rating'] <= 2): ?>
                                                <span class="badge badge-danger ml-2">Needs Improvement</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?= nl2br(htmlspecialchars($row['remarks'] ?? '')) ?></td>
                                    <td>
                                        <?php 
                                        try {
                                            echo (new DateTime($row['created_at']))->format('d/m/y h:i A');
                                        } catch (Exception $e) {
                                            echo 'Invalid date';
                                        }
                                        ?>
                                    </td>
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
                                <a class="page-link" href="?<?= htmlspecialchars(http_build_query(array_merge($_GET, ['page' => $page - 1]))) ?>">Previous</a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?<?= htmlspecialchars(http_build_query(array_merge($_GET, ['page' => $i]))) ?>"><?= htmlspecialchars($i) ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?<?= htmlspecialchars(http_build_query(array_merge($_GET, ['page' => $page + 1]))) ?>">Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include('footer.php'); ?>