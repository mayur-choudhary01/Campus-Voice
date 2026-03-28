<?php
// 1. Database connection include karein
include '../database/db.php';

$message = ""; // Success ya Error message ke liye

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    
    // Abhi hum sirf 'student' ka logic implement kar rahe hain
    if ($role == 'student') {
        $name     = $_POST['name'];
        $email    = $_POST['email'];
        $password = $_POST['password']; // Security ke liye password_hash use kar sakte hain
        $course   = $_POST['course'];
        $year     = $_POST['year'];
        $section  = $_POST['section'];

        // 2. SQL Query with Prepared Statement (Safe Way)
        $sql = "INSERT INTO students (name, email, password, course, year, section) VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            // "ssssss" matlab 6 strings hain
            mysqli_stmt_bind_param($stmt, "ssssss", $name, $email, $password, $course, $year, $section);
            
            if (mysqli_stmt_execute($stmt)) {
                $message = "<p style='color:green; font-weight:bold;'>Student Registered Successfully! <a href='login1.php'>Login here</a></p>";
            } else {
                $message = "<p style='color:red;'>Error: Email already exists or DB error.</p>";
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        // HOD aur Coordinator ka logic baad mein yahan aayega
        $message = "<p style='color:orange;'>Logic for $role will be added later!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dynamic Registration</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; padding: 40px; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .hidden { display: none; }
    </style>
</head>
<body>

<div class="container">
    <h2>Register</h2>
    
    <?php echo $message; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label>Select Role:</label>
            <select name="role" id="roleSelect" onchange="toggleFields()">
                <option value="student">Student</option>
                <option value="coordinator">Coordinator</option>
                <option value="hod">HOD</option>
            </select>
        </div>

        <div class="form-group">
            <label>Full Name:</label>
            <input type="text" name="name" required>
        </div>

        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>

        <div id="studentFields">
            <div class="form-group">
                <label>Course:</label>
                <input type="text" name="course">
            </div>
            <div class="form-group">
                <label>Year:</label>
                <input type="text" name="year">
            </div>
            <div class="form-group">
                <label>Section:</label>
                <input type="text" name="section">
            </div>
        </div>

        <div id="staffFields" class="hidden">
            <div class="form-group">
                <label>Department:</label>
                <input type="text" name="dept" placeholder="CS, IT, etc.">
            </div>
        </div>

        <button type="submit" class="btn">Register Now</button>
    </form>
</div>

<script>
function toggleFields() {
    var role = document.getElementById("roleSelect").value;
    var studentFields = document.getElementById("studentFields");
    var staffFields = document.getElementById("staffFields");

    if (role === "student") {
        studentFields.classList.remove("hidden");
        staffFields.classList.add("hidden");
    } else {
        studentFields.classList.add("hidden");
        staffFields.classList.remove("hidden");
    }
}
</script>

</body>
</html>