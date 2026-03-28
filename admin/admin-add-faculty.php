<?php
session_start();
include("../database/db.php");


if(isset($_POST['add_faculty'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; 
    $role = $_POST['role'];
    $dept = $_POST['department'];
    $designation = $_POST['designation'];
    $class = ($role == 'Coordinator') ? $_POST['assigned_class'] : NULL;
    $image = "default_user.jpeg";

    $sql = "INSERT INTO faculty (name, email, password, role, department, designation, assigned_class, image) 
            VALUES ('$name', '$email', '$password', '$role', '$dept', '$designation', '$class', '$image')";
    
    if(mysqli_query($conn, $sql)) {
        $msg = "Faculty added successfully!";
    } else {
        $msg = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Add Faculty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f6; }
        .form-card { border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card form-card p-4">
                <h3 class="fw-bold mb-4 text-primary">Add New Faculty / HOD</h3>
                <?php if(isset($msg)) echo "<div class='alert alert-info'>$msg</div>"; ?>
                
                <form action="" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email ID</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="role" id="roleSelect" class="form-select" onchange="toggleCoordinatorField()" required>
                                <option value="HOD">HOD</option>
                                <option value="Coordinator">Coordinator</option>
                                <option value="Faculty">General Faculty</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Department</label>
                            <select name="department" class="form-select">
                                <option value="CS">Computer Science</option>
                                <option value="FCA">FCA</option>

                                <option value="IT">Information Technology</option>
                                <option value="Management">Management</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Designation</label>
                            <input type="text" name="designation" class="form-control" placeholder="e.g. Asst. Professor">
                        </div>
                        
                        <div class="col-md-12" id="coordinatorField" style="display: none;">
                            <label class="form-label text-danger fw-bold">Assign Class (For Coordinator Only)</label>
                            <input type="text" name="assigned_class" class="form-control" placeholder="e.g. BCA 2nd Year">
                        </div>

                        <div class="col-md-12 mt-4">
                            <button type="submit" name="add_faculty" class="btn btn-primary w-100 py-2 fw-bold shadow">Register Faculty</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCoordinatorField() {
    var role = document.getElementById("roleSelect").value;
    var field = document.getElementById("coordinatorField");
    if(role === "Coordinator") {
        field.style.display = "block";
    } else {
        field.style.display = "none";
    }
}
</script>

</body>
</html>