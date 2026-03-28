<?php
session_start();
include("../database/db.php");

// 1. Agar user PEHLE SE login hai, toh use login page mat dikhao, seedha dashboard bhejo
if (isset($_SESSION['student_id'])) {
    header("Location: complaint.php");
    exit();
}

if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM students WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);
        
        session_regenerate_id(true);

        $_SESSION['student_id'] = $row['id'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['assigned_class'] = $row['assigned_class']; 
        
        header("Location: complaint.php");
        exit(); // Exit lagana bahut zaroori hai redirect ke baad
    } else {
        $error = "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login | CCMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f7f6;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }
        .card-header {
            background: #0d6efd;
            color: white;
            text-align: center;
            padding: 2rem;
            border: none;
        }
        .btn-login {
            background: #0d6efd;
            border: none;
            padding: 10px;
            border-radius: 8px;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-login:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
        }
        .form-control {
            border-radius: 8px;
            padding: 12px;
        }
        .register-link {
            text-decoration: none;
            color: #0d6efd;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card login-card mx-auto">
                <div class="card-header">
                    <i class="bi bi-person-circle fs-1"></i>
                    <h3 class="mt-2">Student Login</h3>
                    <p class="mb-0 opacity-75">College Complaint Management System</p>
                </div>
                <div class="card-body p-4">
                    
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger py-2 small" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            </div>
                        </div>

                        <button type="submit" name="login" class="btn btn-login btn-primary w-100 mb-3">
                            Login Now
                        </button>
                        
                        <div class="text-center small">
                            Don't have an account? <a href="register1.php" class="register-link">Register here</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="index.php" class="text-muted text-decoration-none small"><i class="bi bi-arrow-left"></i> Back to Home</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>