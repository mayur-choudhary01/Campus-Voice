<?php
session_start();
include("../database/db.php");

if (!isset($_SESSION['faculty_id'])) {
    header("Location: faculty-login.php");
    exit();
}

if (isset($_POST['submit_action'])) {
    $c_id = mysqli_real_escape_string($conn, $_POST['complaint_id']);
  
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);
    $f_id = $_SESSION['faculty_id'];

   
    $sql = "UPDATE complaints SET 
            status = '$new_status', 
            faculty_remarks = '$remarks', 
            assigned_to = '$f_id' 
            WHERE id = '$c_id'";

    if (mysqli_query($conn, $sql)) {

    header("Location: faculty-dashboard.php?msg=Complaint #$c_id updated successfully!");
    } else {
        // Error handling
        echo "Error updating record: " . mysqli_error($conn);
    }
} else {
    header("Location: faculty-dashboard.php");
}
?>