<?php
session_start();
include("../database/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['complaint_id'])) {
    $c_id = $_POST['complaint_id'];
    $new_status = $_POST['status'];
    $remark = mysqli_real_escape_string($conn, $_POST['remark']);

    // Update query - hum 'assigned_to' bhi update kar sakte hain agar faculty ne solve kiya hai
    $sql = "UPDATE complaints SET status = '$new_status', description = CONCAT(description, '\n\nFaculty Remark: ', '$remark') WHERE id = '$c_id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: faculty_dashboard.php?msg=Status Updated Successfully");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>