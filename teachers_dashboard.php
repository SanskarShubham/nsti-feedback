<?php 
include('header.php'); 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Feedback Counts
$query = "SELECT * FROM feedback";
$result = mysqli_query($conn, $query);

// Positive Feedback
$query2 = "SELECT * FROM feedback WHERE rating >=3 && rating <=5";
$result2 = mysqli_query($conn, $query2);

// Negative Feedback
$query3 = "SELECT * FROM feedback WHERE rating >=1 && rating <=2";
$result3 = mysqli_query($conn, $query3);

// Average Rating
$query4 = "SELECT AVG(rating) as average FROM feedback";
$result4 = mysqli_query($conn, $query4);
$rowAvg = mysqli_fetch_assoc($result4);

// Rating Distribution
$ratingCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
$queryRating = "SELECT rating, COUNT(*) as count FROM feedback GROUP BY rating";
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
?>

<div class="container-fluid mt-3">
    <div class="row">
        <!-- Total Feedback -->
        <div class="col-lg-3 col-sm-6">
            <div class="card gradient-1">
                <div class="card-body">
                    <h3 class="card-title text-white">Total Feedback of hii</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white"><?php echo mysqli_num_rows($result); ?></h2>
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
                        <h2 class="text-white"><?php echo mysqli_num_rows($result2); ?></h2>
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
                        <h2 class="text-white"><?php echo mysqli_num_rows($result3); ?></h2>
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
                        <h2 class="text-white"><?php echo number_format((float)$rowAvg['average'], 1, '.', ''); ?></h2>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-star"></i></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Bar Chart -->
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Feedback Rating Overview</h4>
                    <div style="height: 300px;">
                        <canvas id="feedbackChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const chartEl = document.getElementById('feedbackChart');
    if (chartEl) {
        const ctx = chartEl.getContext('2d');
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
                    backgroundColor: '#4dc9f6',
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
});
</script>

<?php include_once('footer.php'); ?>
