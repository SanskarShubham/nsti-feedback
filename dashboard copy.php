<?php include('header.php'); ?>

<?php
$query = "SELECT * FROM feedback";
$result = mysqli_query($conn, $query);
?>

<?php
// Get rating count for each rating value from 1 to 5
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

?>


<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="card gradient-1">
                <div class="card-body">
                    <h3 class="card-title text-white">Total Feedback</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white"><?php echo mysqli_num_rows($result); ?></h2>
                        <!-- <p class="text-white mb-0">Jan - March 2019</p> -->
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-comments"></i></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card gradient-2">
                <div class="card-body">
                    <?php
                    $query2 = "SELECT * FROM feedback WHERE rating >=3 && rating <=5";
                    $result2 = mysqli_query($conn, $query2);
                    ?>
                    <h3 class="card-title text-white">Total Positive Feedback </h3>
                    <div class="d-inline-block">
                        <h2 class="text-white"><?php echo mysqli_num_rows($result2); ?></h2>
                        <!-- <p class="text-white mb-0">Jan - March 2019</p> -->
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-money"></i></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card gradient-3">
                <div class="card-body">
                    <?php
                    $query3 = "SELECT * FROM feedback WHERE rating >=1 && rating <=2";
                    $result3 = mysqli_query($conn, $query3);
                    ?>
                    <h3 class="card-title text-white">Total Negative Feedback </h3>
                    <div class="d-inline-block">
                        <h2 class="text-white"><?php echo mysqli_num_rows($result3); ?></h2>
                        <!-- <p class="text-white mb-0">Jan - March 2019</p> -->
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-users"></i></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card gradient-4">
                <div class="card-body">
                    <?php
                    $query4 = "SELECT AVG(rating) as average FROM feedback";
                    $result4 = mysqli_query($conn, $query4);
                    $row = mysqli_fetch_assoc($result4);
                    ?>
                    <h3 class="card-title text-white">Average Rating</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white"><?php echo number_format((float)$row['average'], 1, '.', ''); ?></h2>
                        <!-- <p class="text-white mb-0">Jan - March 2019</p> -->
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-heart"></i></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body pb-0 d-flex justify-content-between">
                            <div>
                                <h4 class="mb-1">Product Sales</h4>
                                <p>Total Earnings of the Month</p>
                                <h3 class="m-0">$ 12,555</h3>
                            </div>
                            <div>
                                <ul>
                                    <li class="d-inline-block mr-3"><a class="text-dark" href="#">Day</a></li>
                                    <li class="d-inline-block mr-3"><a class="text-dark" href="#">Week</a></li>
                                    <li class="d-inline-block"><a class="text-dark" href="#">Month</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="chart_widget_2"></canvas>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="w-100 mr-2">
                                    <h6>Pixel 2</h6>
                                    <div class="progress" style="height: 6px">
                                        <div class="progress-bar bg-danger" style="width: 40%"></div>
                                    </div>
                                </div>
                                <div class="ml-2 w-100">
                                    <h6>iPhone X</h6>
                                    <div class="progress" style="height: 6px">
                                        <div class="progress-bar bg-primary" style="width: 80%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Order Summary</h4>
                    <div id="morris-bar-chart"></div>
                </div>
            </div>

        </div>

    </div>



    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="active-member">
                        <div class="table-responsive">
                            <table class="table table-xs mb-0">
                                <thead>
                                    <tr>
                                        <th>Customers</th>
                                        <th>Product</th>
                                        <th>Country</th>
                                        <th>Status</th>
                                        <th>Payment Method</th>
                                        <th>Activity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><img src="./images/avatar/1.jpg" class=" rounded-circle mr-3" alt="">Sarah Smith</td>
                                        <td>iPhone X</td>
                                        <td>
                                            <span>United States</span>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="progress" style="height: 6px">
                                                    <div class="progress-bar bg-success" style="width: 50%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><i class="fa fa-circle-o text-success  mr-2"></i> Paid</td>
                                        <td>
                                            <span>Last Login</span>
                                            <span class="m-0 pl-3">10 sec ago</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><img src="./images/avatar/2.jpg" class=" rounded-circle mr-3" alt="">Walter R.</td>
                                        <td>Pixel 2</td>
                                        <td><span>Canada</span></td>
                                        <td>
                                            <div>
                                                <div class="progress" style="height: 6px">
                                                    <div class="progress-bar bg-success" style="width: 50%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><i class="fa fa-circle-o text-success  mr-2"></i> Paid</td>
                                        <td>
                                            <span>Last Login</span>
                                            <span class="m-0 pl-3">50 sec ago</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><img src="./images/avatar/3.jpg" class=" rounded-circle mr-3" alt="">Andrew D.</td>
                                        <td>OnePlus</td>
                                        <td><span>Germany</span></td>
                                        <td>
                                            <div>
                                                <div class="progress" style="height: 6px">
                                                    <div class="progress-bar bg-warning" style="width: 50%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><i class="fa fa-circle-o text-warning  mr-2"></i> Pending</td>
                                        <td>
                                            <span>Last Login</span>
                                            <span class="m-0 pl-3">10 sec ago</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><img src="./images/avatar/6.jpg" class=" rounded-circle mr-3" alt=""> Megan S.</td>
                                        <td>Galaxy</td>
                                        <td><span>Japan</span></td>
                                        <td>
                                            <div>
                                                <div class="progress" style="height: 6px">
                                                    <div class="progress-bar bg-success" style="width: 50%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><i class="fa fa-circle-o text-success  mr-2"></i> Paid</td>
                                        <td>
                                            <span>Last Login</span>
                                            <span class="m-0 pl-3">10 sec ago</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><img src="./images/avatar/4.jpg" class=" rounded-circle mr-3" alt=""> Doris R.</td>
                                        <td>Moto Z2</td>
                                        <td><span>England</span></td>
                                        <td>
                                            <div>
                                                <div class="progress" style="height: 6px">
                                                    <div class="progress-bar bg-success" style="width: 50%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><i class="fa fa-circle-o text-success  mr-2"></i> Paid</td>
                                        <td>
                                            <span>Last Login</span>
                                            <span class="m-0 pl-3">10 sec ago</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><img src="./images/avatar/5.jpg" class=" rounded-circle mr-3" alt="">Elizabeth W.</td>
                                        <td>Notebook Asus</td>
                                        <td><span>China</span></td>
                                        <td>
                                            <div>
                                                <div class="progress" style="height: 6px">
                                                    <div class="progress-bar bg-warning" style="width: 50%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><i class="fa fa-circle-o text-warning  mr-2"></i> Pending</td>
                                        <td>
                                            <span>Last Login</span>
                                            <span class="m-0 pl-3">10 sec ago</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-lg-6 col-sm-6 col-xxl-6">

            <div class="card">
                <div class="chart-wrapper mb-4">
                    <div class="px-4 pt-4 d-flex justify-content-between">
                        <div>
                            <h4>Sales Activities</h4>
                            <p>Last 6 Month</p>
                        </div>
                        <div>
                            <span><i class="fa fa-caret-up text-success"></i></span>
                            <h4 class="d-inline-block text-success">720</h4>
                            <p class=" text-danger">+120.5(5.0%)</p>
                        </div>
                    </div>
                    <div>
                        <canvas id="chart_widget_3"></canvas>
                    </div>
                </div>
                <div class="card-body border-top pt-4">
                    <div class="row">
                        <div class="col-sm-6">
                            <ul>
                                <li>5% Negative Feedback</li>
                                <li>95% Positive Feedback</li>
                            </ul>
                            <div>
                                <h5>Customer Feedback</h5>
                                <h3>385749</h3>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div id="chart_widget_3_1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-12 col-sm-12 col-xxl-12">
            <div class="card">
                <div class="card-body">
                    <!-- <h4 class="card-title mb-0">Store Location</h4> -->
                    <div id="world-map" style="height: 470px;"></div>
                </div>
            </div>
        </div>
    </div>





    <div class="col-xl-6 col-lg-6 col-md-12">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Feedback Rating Overview</h4>
            <canvas id="feedbackChart" height="180"></canvas>
        </div>
    </div>
</div>

    



</div>

<?php include('footer.php'); ?>