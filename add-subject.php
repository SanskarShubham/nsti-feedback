<?php include('header.php'); ?>

<!-- content -->
<div class="container-fluid">   
    <div class="card-header text-right">
    <a href="list-subject.php" class="btn btn-primary"><i class="fa fa-eye"></i> View subject</a>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="form-validation">
                <form class="form-valide" action="" method="post" enctype="multipart/form-data" autocomplete="off">
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="val-username">Subject Name <span class="text-danger">*</span></label>
                        <input type="hidden" name="id" value="">
                        <div class="col-lg-6">
                            <input type="text" class="form-control" value="" id="val-username" name="subjectname" placeholder="Enter a Subject Name.." required>
                        </div>
                    </div>

                    
            

                    <div class="form-group row">
                        <div class="col-lg-8 ml-auto">
                            <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-plus"> </i> Add</button>
                        </div>
                    </div>
                    
                </form>
                <div class="form-group row">
                    
                </div>

                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $name = $_POST["subjectname"];

                   
                                // Prepare SQL Insert
                                $sql = "INSERT INTO subject (name) VALUES ( ? )";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("s", $name);

                                if ($stmt->execute()) {
                                  
                                    echo "<div class='text-success mt-3'>✅ Subject added successfully!</div>";
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
