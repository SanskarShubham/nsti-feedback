<?php include('header.php'); ?>

<!-- content -->
<div class="container-fluid">   
    <div class="card">
        <div class="card-body">
            <div class="form-validation">
                <form class="form-valide" action="" method="post" enctype="multipart/form-data" autocomplete="off">
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="val-username">Trade Name <span class="text-danger">*</span></label>
                        <input type="hidden" name="id" value="">
                        <div class="col-lg-6">
                            <input type="text" class="form-control" value="" id="val-username" name="tradename" placeholder="Enter a Trade Name.." required>
                        </div>
                    </div>

                    

                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Status <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <label class="mr-2"><input type="radio" value="0" name="status"> CTS</label>
                            <label><input type="radio" value="1" name="status" checked> CITS</label>
                        </div>
                    </div>

                  

                    <div class="form-group row">
                        <div class="col-lg-8 ml-auto">
                            <button type="submit" name="submit" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                </form>

                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $name = $_POST["tradename"];
                    $status = $_POST["status"];

                   
                                // Prepare SQL Insert
                                $sql = "INSERT INTO trade (trade_name,  status) VALUES ( ?, ?)";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("si", $name, $status);

                                if ($stmt->execute()) {
                                  
                                    echo "<div class='text-success mt-3'>✅ Trade added successfully!</div>";
                                } else {
                                    echo "<div class='text-danger mt-3'>❌ Error: " . $stmt->error . "</div>";
                                }

                                $stmt->close();
                            
                       

                    $conn->close();
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- end content -->
 

<?php include('footer.php'); ?>
