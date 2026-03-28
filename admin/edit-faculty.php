<?php
session_start();
include("../database/db.php");

// 1. Get Faculty Data: URL se ID lekar purana data fetch karna
if (isset($_GET['id'])) {
    $f_id = mysqli_real_escape_string($conn, $_GET['id']);
    $res = mysqli_query($conn, "SELECT * FROM faculty WHERE id = '$f_id'");
    $data = mysqli_fetch_assoc($res);

    if (!$data) { echo "Faculty not found!"; exit(); }
}

// 2. Update Logic: Jab Admin 'Update' button dabaye
if (isset($_POST['update_faculty'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $dept = $_POST['department'];
    $designation = $_POST['designation'];
    $class = ($role == 'Coordinator') ? $_POST['assigned_class'] : NULL;

    $update_sql = "UPDATE faculty SET 
                   name='$name', 
                   email='$email', 
                   role='$role', 
                   department='$dept', 
                   designation='$designation', 
                   assigned_class='$class' 
                   WHERE id='$f_id'";

    if (mysqli_query($conn, $update_sql)) {
        header("Location: manage_faculty.php?msg=Faculty updated successfully!");
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Edit Faculty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f6; font-family: 'Inter', sans-serif; }
        .edit-card { border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card edit-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold text-primary mb-0">Edit Faculty Details</h3>
                    <a href="manage-faculty.php" class="btn btn-light btn-sm">Cancel</a>
                </div>

                <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

                <form action="" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $data['name']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email ID</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $data['email']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="role" id="roleSelect" class="form-select" onchange="toggleField()" required>
                                <option value="HOD" <?php if($data['role']=='HOD') echo 'selected'; ?>>HOD</option>
                                <option value="Coordinator" <?php if($data['role']=='Coordinator') echo 'selected'; ?>>Coordinator</option>
                                <option value="Faculty" <?php if($data['role']=='Faculty') echo 'selected'; ?>>General Faculty</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Department</label>
                            <select name="department" class="form-select">
                                <option value="CS" <?php if($data['department']=='CS') echo 'selected'; ?>>Computer Science</option>
                                <option value="IT" <?php if($data['department']=='IT') echo 'selected'; ?>>Information Technology</option>
                                <option value="FCA" <?php if($data['department']=='FCA') echo 'selected'; ?>>Faculty of Computer Applications</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Designation</label>
                            <input type="text" name="designation" class="form-control" value="<?php echo $data['designation']; ?>">
                        </div>
                        
                        <div class="col-md-12" id="coordDiv" style="display: <?php echo ($data['role']=='Coordinator')?'block':'none'; ?>;">
                            <label class="form-label text-danger fw-bold">Assigned Class</label>
                            <input type="text" name="assigned_class" class="form-control" value="<?php echo $data['assigned_class']; ?>">
                        </div>

                        <div class="col-md-12 mt-4">
                            <button type="submit" name="update_faculty" class="btn btn-success w-100 py-2 fw-bold shadow">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleField() {
    var role = document.getElementById("roleSelect").value;
    document.getElementById("coordDiv").style.display = (role === "Coordinator") ? "block" : "none";
}
</script>

</body>
</html>