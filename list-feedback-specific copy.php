<?php 
include('header.php'); 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get filters from URL parameters
$teacher = $_GET['teacher'] ?? '';
$subject = $_GET['subject'] ?? '';
$trade = $_GET['trade'] ?? '';
$rating = $_GET['rating'] ?? '';
$date = $_GET['date'] ?? '';

// Build WHERE clause for all queries
$where = "WHERE 1";
if ($teacher !== '') $where .= " AND t.name LIKE '%" . mysqli_real_escape_string($conn, $teacher) . "%'";
if ($subject !== '') $where .= " AND s.name LIKE '%" . mysqli_real_escape_string($conn, $subject) . "%'";
if ($trade !== '') $where .= " AND tr.trade_name = '" . mysqli_real_escape_string($conn, $trade) . "'";
if ($rating !== '') $where .= " AND f.rating = '" . mysqli_real_escape_string($conn, $rating) . "'";
if ($date !== '') $where .= " AND DATE(f.created_at) = '" . mysqli_real_escape_string($conn, $date) . "'";

// Base query for all feedback
$baseQuery = "FROM feedback f
JOIN teachers t ON t.teacher_id = f.teacher_id
JOIN teacher_subject_trade tst ON tst.teacher_id = t.teacher_id
JOIN trade tr ON tst.trade_id = tr.trade_id
JOIN subject s ON tst.subject_id = s.subject_id
$where";

// Total Feedback Count
$query = "SELECT COUNT(DISTINCT f.id) as total $baseQuery";
$result = mysqli_query($conn, $query);
$totalFeedback = mysqli_fetch_assoc($result)['total'];

// Positive Feedback (3-5 stars)
$query2 = "SELECT COUNT(DISTINCT f.id) as total $baseQuery AND f.rating >=3 AND f.rating <=5";
$result2 = mysqli_query($conn, $query2);
$positiveFeedback = mysqli_fetch_assoc($result2)['total'];

// Negative Feedback (1-2 stars)
$query3 = "SELECT COUNT(DISTINCT f.id) as total $baseQuery AND f.rating >=1 AND f.rating <=2";
$result3 = mysqli_query($conn, $query3);
$negativeFeedback = mysqli_fetch_assoc($result3)['total'];

// Average Rating
$query4 = "SELECT AVG(f.rating) as average $baseQuery";
$result4 = mysqli_query($conn, $query4);
$rowAvg = mysqli_fetch_assoc($result4);

// Rating Distribution
$ratingCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
$queryRating = "SELECT f.rating, COUNT(DISTINCT f.id) as count $baseQuery GROUP BY f.rating";
$resultRating = mysqli_query($conn, $queryRating);
while ($row = mysqli_fetch_assoc($resultRating)) {
    $rating = (int)$row['rating'];
    $count = (int)$row['count'];
    if ($rating >= 1 && $rating <= 5) {
        $ratingCounts[$rating] = $count;
    }
}

// Calculate suggested max for Y-axis
$maxCount = max($ratingCounts);
$suggestedMax = ceil(($maxCount + 50) / 100) * 100; // round to nearest 100

// Fetch trade list for dropdown
$trade_list = mysqli_query($conn, "SELECT DISTINCT trade_name FROM trade");
?>

<div class="container-fluid mt-3">
    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title">Filter Feedback Data</h4>
            <form method="GET" class="form-inline flex-wrap gap-2">
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
                
                <button type="submit" class="btn btn-success btn-m mr-3 mb-2">Apply Filters</button>
                <a href="dashboard.php" class="btn btn-danger btn-m mb-2">Reset Filters</a>
            </form>
            
            <?php if ($teacher || $subject || $trade || $rating || $date): ?>
                <div class="mt-2">
                    <small class="text-muted">Showing results for: 
                        <?php 
                        $filters = [];
                        if ($teacher) $filters[] = "Teacher: $teacher";
                        if ($subject) $filters[] = "Subject: $subject";
                        if ($trade) $filters[] = "Trade: $trade";
                        if ($rating) $filters[] = "Rating: $rating";
                        if ($date) $filters[] = "Date: $date";
                        echo implode(', ', $filters);
                        ?>
                    </small>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <!-- Total Feedback -->
        <div class="col-lg-3 col-sm-6">
            <div class="card gradient-1">
                <div class="card-body">
                    <h3 class="card-title text-white">Total Feedback</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white"><?= $totalFeedback ?></h2>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-comments"></i></span>
                </div>
            </div>
        </div>

        <!-- Positive Feedback -->
        <div class="col-lg-3 col-sm-6">
            <div class="card gradient-2">
                <div class="card-body">
                    <h3 class="card-title text-white">Positive Feedback</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white"><?= $positiveFeedback ?></h2>
                        <small class="text-white">
                            (<?= $totalFeedback > 0 ? round(($positiveFeedback / $totalFeedback) * 100, 1) : 0 ?>%)
                        </small>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-thumbs-up"></i></span>
                </div>
            </div>
        </div>

        <!-- Negative Feedback -->
        <div class="col-lg-3 col-sm-6">
            <div class="card gradient-3">
                <div class="card-body">
                    <h3 class="card-title text-white">Negative Feedback</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white"><?= $negativeFeedback ?></h2>
                        <small class="text-white">
                            (<?= $totalFeedback > 0 ? round(($negativeFeedback / $totalFeedback) * 100, 1) : 0 ?>%)
                        </small>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-thumbs-down"></i></span>
                </div>
            </div>
        </div>

        <!-- Average Rating -->
        <div class="col-lg-3 col-sm-6">
            <div class="card gradient-4">
                <div class="card-body">
                    <h3 class="card-title text-white">Average Rating</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white"><?= $totalFeedback > 0 ? number_format((float)$rowAvg['average'], 1, '.', '') : 'N/A' ?></h2>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-star"></i></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Rating Bar Chart -->
        <div class="col-xl-6 col-lg-6 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Feedback Rating Distribution</h4>
                    <?php if ($totalFeedback > 0): ?>
                        <div style="height: 300px;">
                            <canvas id="feedbackChart"></canvas>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">No feedback data available with current filters</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Rating Pie Chart -->
        <div class="col-xl-6 col-lg-6 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Feedback Rating Proportion</h4>
                    <?php if ($totalFeedback > 0): ?>
                        <div style="height: 300px;">
                            <canvas id="feedbackPieChart"></canvas>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">No feedback data available with current filters</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($totalFeedback > 0): ?>
<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Color palette for charts
    const chartColors = {
        1: '#ff6384', // Red
        2: '#ff9f40', // Orange
        3: '#ffcd56', // Yellow
        4: '#4bc0c0', // Teal
        5: '#36a2eb'  // Blue
    };
    
    // Bar Chart
    const barChartEl = document.getElementById('feedbackChart');
    if (barChartEl) {
        const ctx = barChartEl.getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['⭐ 1', '⭐ 2', '⭐ 3', '⭐ 4', '⭐ 5'],
                datasets: [{
                    label: 'Feedback Count',
                    data: [
                        <?= $ratingCounts[1] ?>,
                        <?= $ratingCounts[2] ?>,
                        <?= $ratingCounts[3] ?>,
                        <?= $ratingCounts[4] ?>,
                        <?= $ratingCounts[5] ?>
                    ],
                    backgroundColor: [
                        chartColors[1],
                        chartColors[2],
                        chartColors[3],
                        chartColors[4],
                        chartColors[5]
                    ],
                    borderRadius: 6,
                    barThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#333',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 10
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#666', font: { size: 13 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#eee' },
                        ticks: {
                            color: '#666',
                            font: { size: 13 },
                            stepSize: 100,
                            callback: function(value) {
                                return value;
                            }
                        },
                        suggestedMax: <?= $suggestedMax ?>
                    }
                }
            }
        });
    }
    
    // Pie Chart
    const pieChartEl = document.getElementById('feedbackPieChart');
    if (pieChartEl) {
        const ctx = pieChartEl.getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['⭐ 1', '⭐ 2', '⭐ 3', '⭐ 4', '⭐ 5'],
                datasets: [{
                    data: [
                        <?= $ratingCounts[1] ?>,
                        <?= $ratingCounts[2] ?>,
                        <?= $ratingCounts[3] ?>,
                        <?= $ratingCounts[4] ?>,
                        <?= $ratingCounts[5] ?>
                    ],
                    backgroundColor: [
                        chartColors[1],
                        chartColors[2],
                        chartColors[3],
                        chartColors[4],
                        chartColors[5]
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            padding: 20,
                            font: {
                                size: 13
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#333',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 10,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
<?php endif; ?>

<?php include_once('footer.php'); ?>