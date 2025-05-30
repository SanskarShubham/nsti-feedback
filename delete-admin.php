<?php include('header.php'); ?>


<!-- content -->
<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM admin WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    // header("Location: list-admin.php");

}

?>


<!-- end content -->
<?php include('footer.php'); ?>