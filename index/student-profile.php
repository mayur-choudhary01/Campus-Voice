<?php
session_start();
include("../database/db.php");

if (!isset($_SESSION['student_id'])) { header("Location: login.php"); exit(); }

$s_id = $_SESSION['student_id'];

// Purani details fetch karo taaki form mein pehle se dikhen
$user_query = mysqli_query($conn, "SELECT * FROM students WHERE id = '$s_id'");
$user = mysqli_fetch_assoc($user_query);

if(isset($_POST['update_profile'])) {
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $branch = mysqli_real_escape_string($conn, $_POST['branch']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);

    $update = "UPDATE students SET course='$course', branch='$branch', year='$year', section='$section' WHERE id='$s_id'";
    
    if(mysqli_query($conn, $update)) {
        $msg = "Profile updated successfully!";
        // Refresh local data
        header("Refresh:1"); 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile | CampusVoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f6; }
        .profile-card { border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card profile-card p-4">
                <h3 class="fw-bold mb-4">Update Academic Details</h3>
                <?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
                
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Course</label>
                        <select name="course" class="form-select shadow-sm">
                            <option value="BCA" <?php if($user['course']=='BCA') echo 'selected'; ?>>BCA</option>
                            <option value="MCA" <?php if($user['course']=='MCA') echo 'selected'; ?>>MCA</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Department/Branch</label>
                        <select name="branch" class="form-select shadow-sm">
                            <option value="FCA" <?php if($user['branch']=='FCA') echo 'selected'; ?>>FCA</option>
                            <option value="CS" <?php if($user['branch']=='CS') echo 'selected'; ?>>Computer Science</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Year</label>
                            <input type="text" name="year" class="form-control shadow-sm" value="<?php echo $user['year']; ?>" placeholder="e.g. 2nd Year">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Section</label>
                            <input type="text" name="section" class="form-control shadow-sm" value="<?php echo $user['section']; ?>" placeholder="e.g. A">
                        </div>
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-primary w-100 py-2 fw-bold mt-3 shadow">Save Changes</button>
                    <a href="complaint.php" class="btn btn-light w-100 mt-2">Back to Home</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>