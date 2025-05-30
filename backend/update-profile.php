<?php 
require_once('../connection.php');
// print_r($_POST);
// $conn = new mysqli("localhost", "root", "", "nsti");

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

$sql = "UPDATE admin SET name='".$_POST['username']."', email='".$_POST['email']."', mobile='".$_POST['phone']."' WHERE id=1";

if ($conn->query($sql) === TRUE) {
   header("Location: ../profile.php");
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
exit;

?>