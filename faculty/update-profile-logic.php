<?php
session_start();
include("../database/db.php");

if (!isset($_SESSION['faculty_id'])) {
    header("Location: faculty-login.php");
    exit();
}

$f_id = $_SESSION['faculty_id'];

if (isset($_POST['update_profile']) || isset($_FILES['profile_image'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $designation = mysqli_real_escape_string($conn, $_POST['designation']);
    $new_pass = $_POST['new_password'];
    $conf_pass = $_POST['confirm_password'];


    if (!empty($_FILES['profile_image']['name'])) {
        $img_name = time() . '_' . $_FILES['profile_image']['name'];
        $target = "../images/" . $img_name;

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target)) {

        $old_img = $_SESSION['image'];
            if ($old_img != 'default_user.jpeg' && file_exists("../images/" . $old_img)) {
                unlink("../images/" . $old_img);
            }
            
            // Database mein update
            mysqli_query($conn, "UPDATE faculty SET image = '$img_name' WHERE id = '$f_id'");
            $_SESSION['image'] = $img_name; // Session update
        }
    }

    // 2. NAME & DESIGNATION UPDATE
    $update_info = "UPDATE faculty SET name = '$name', designation = '$designation' WHERE id = '$f_id'";
    mysqli_query($conn, $update_info);
    $_SESSION['name'] = $name;

    if (!empty($new_pass)) {
        if ($new_pass === $conf_pass) {

        $update_pass = "UPDATE faculty SET password = '$new_pass' WHERE id = '$f_id'";
            mysqli_query($conn, $update_pass);
        } else {
            header("Location: faculty-profile.php?error=Password Mismatch");
            exit();
        }
    }

    header("Location: faculty-profile.php?success=Profile Updated");
}
?>