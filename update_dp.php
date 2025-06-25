<?php
session_start();

require_once 'connection.php';
// Replace with session user ID (assuming you're logged in)
$admin_id = $_SESSION['admin_data']['id'];
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_pic'])) {
    $fileName = $_FILES['profile_pic']['name'];
    $tmpName = $_FILES['profile_pic']['tmp_name'];
    $folder = ".images/dp/";



    // Rename file to avoid duplication
    $uniqueName = uniqid() . '_' . basename($fileName);
    $destination = $folder . $uniqueName;

    if (move_uploaded_file($tmpName, $destination)) {
        // Save new path to database
        $stmt = $conn->prepare("UPDATE admin SET dp_file_path = ? WHERE id = ?");
        $stmt->bind_param("si", $destination, $admin_id);
        if ($stmt->execute()) {
            echo "Profile picture updated successfully!";
        } else {
            echo "DB error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Failed to upload image.";
    }
}

// Fetch current image
$result = $conn->query("SELECT dp_fil_epath FROM teahcers WHERE teacher_id = $admin_id");
$row = $result->fetch_assoc();
$currentPic = !empty($row['filepath']) ? $row['filepath'] : 'dp/default.png';
$conn->close();
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Update Profile Picture</title>
</head>
<body>
    <h2>Current Profile Picture</h2>
    <img src="<?php echo $currentPic; ?>" width="150" height="150" style="border-radius: 50%;"><br><br>

    <form method="post" enctype="multipart/form-data">
        <label>Select New Profile Picture:</label><br>
        <input type="file" name="profile_pic" required><br><br>
        <input type="submit" value="Update Picture">
    </form>
</body>
</html>