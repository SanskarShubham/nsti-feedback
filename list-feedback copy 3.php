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
if ($teacher !== '') $where .= " AND t.teacher_id = '" . mysqli_real_escape_string($conn, $teacher) . "'";
if ($subject !== '') $where .= " AND s.subject_id = '" . mysqli_real_escape_string($conn, $subject) . "'";
if ($trade !== '') $where .= " AND tr.trade_name = '" . mysqli_real_escape_string($conn, $trade) . "'";
if ($rating !== '') $where .= " AND f.rating = '" . mysqli_real_escape_string($conn, $rating) . "'";
if ($date !== '') $where .= " AND DATE(f.created_at) = '" . mysqli_real_escape_string($conn, $date) . "'";

// First get the total count of feedback records
$count_sql = "SELECT COUNT(f.id) AS total FROM feedback f
JOIN teachers t ON t.teacher_id = f.teacher_id
JOIN trade tr ON tr.trade_id = f.trade_id
JOIN subject s ON s.subject_id = f.subject_id
$where";
$count_result = mysqli_query($conn, $count_sql);
$count_row = mysqli_fetch_assoc($count_result);
$total_records = $count_row['total'];
$total_pages = ceil($total_records / $limit);

// Ensure current page is within valid range
$page = max(1, min($page, $total_pages));
$offset = ($page - 1) * $limit;

// Data fetch
$sql = "SELECT f.*, t.name AS teacher_name, s.name AS subject_name, tr.trade_name 
FROM feedback f
JOIN teachers t ON t.teacher_id = f.teacher_id
JOIN trade tr ON tr.trade_id = f.trade_id
JOIN subject s ON s.subject_id = f.subject_id
$where
ORDER BY f.created_at DESC
LIMIT $offset, $limit";
$result = mysqli_query($conn, $sql);

// Fetch lists for dropdowns
$trade_list = mysqli_query($conn, "SELECT DISTINCT trade_name FROM trade ORDER BY trade_name");
$teacher_list = mysqli_query($conn, "SELECT teacher_id, name FROM teachers ORDER BY name");
$subject_list = mysqli_query($conn, "SELECT subject_id, name FROM subject ORDER BY name");
?>

<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-body">

            <!-- Filter Form -->
            <form method="GET" class="form-inline mb-3 flex-wrap gap-2">
                <!-- Teacher Dropdown -->
                <select name="teacher" class="form-control form-control-m mr-3 mb-2" style="max-width:200px;">
                    <option value="">Select Teacher</option>
                    <?php while ($tch = mysqli_fetch_assoc($teacher_list)): ?>
                        <option value="<?= $tch['teacher_id'] ?>" <?= $teacher == $tch['teacher_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($tch['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                
                <!-- Subject Dropdown -->
                <select name="subject" class="form-control form-control-m mr-3 mb-2" style="max-width:200px;">
                    <option value="">Select Subject</option>
                    <?php while ($sub = mysqli_fetch_assoc($subject_list)): ?>
                        <option value="<?= $sub['subject_id'] ?>" <?= $subject == $sub['subject_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($sub['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                
                <!-- Trade Dropdown -->
                <select name="trade" class="form-control form-control-m mr-3 mb-2" style="max-width:200px;">
                    <option value="">Select Trade</option>
                    <?php while ($t = mysqli_fetch_assoc($trade_list)): ?>
                        <option value="<?= $t['trade_name'] ?>" <?= $trade == $t['trade_name'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['trade_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                
                <!-- Rating Dropdown -->
                <select name="rating" class="form-control form-control-m mr-3 mb-2" style="max-width:120px;">
                    <option value="">Rating</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>" <?= $rating == $i ? 'selected' : '' ?>><?= $i ?> ⭐</option>
                    <?php endfor; ?>
                </select>
                
                <!-- Date Input -->
                <input type="date" name="date" value="<?= htmlspecialchars($date) ?>" class="form-control form-control-m mr-3 mb-2" style="max-width:150px;">

                <button type="submit" class="btn btn-success btn-m mr-3 mb-2"><i class="fa fa-filter mr-1" ></i> Filter</button>
                <a href="list-feedback.php" class="btn btn-danger btn-m mr-3 mb-2"><i class="fa fa-refresh mr-1"></i> Reset</a>

                <!-- Download Button -->
                <a href="download-feedback.php?<?= http_build_query($_GET) ?>" class="btn btn-primary btn-m mb-2 mr-3">
                    <i class="fa fa-download mr-1"></i> Download
                </a>
                
                     


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
            <?php if ($total_pages > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center">
                        <!-- Previous button -->
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => max(1, $page - 1)])) ?>">Previous</a>
                        </li>
                        
                        <!-- First page -->
                        <?php if ($page > 3): ?>
                            <li class="page-item">
                                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>">1</a>
                            </li>
                            <?php if ($page > 4): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <!-- Page numbers around current page -->
                        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <!-- Last page -->
                        <?php if ($page < $total_pages - 2): ?>
                            <?php if ($page < $total_pages - 3): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>
                            <li class="page-item">
                                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $total_pages])) ?>"><?= $total_pages ?></a>
                            </li>
                        <?php endif; ?>
                        
                        <!-- Next button -->
                        <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => min($total_pages, $page + 1)])) ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include('footer.php'); ?>