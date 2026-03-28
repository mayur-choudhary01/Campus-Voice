<?php
session_start();
include("../database/db.php");

$error = "";

if (isset($_POST['login'])) {
$email = mysqli_real_escape_string($conn, $_POST['email']);    $password = $_POST['password']; // Password hash check karna better hai, par abhi simple rakhte hain

    $sql = "SELECT * FROM faculty WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Session me data store karna
        $_SESSION['faculty_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['department'] = $user['department'];
        $_SESSION['assigned_class'] = $user['assigned_class'];
        $_SESSION['image'] = $user['image'];

        header("Location: faculty-dashboard.php");
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
    <title>Faculty Login | CampusVoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            color: white;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 12px;
            padding: 12px;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-color: #0ea5e9;
            box-shadow: none;
        }
        .btn-login {
            background: #0ea5e9;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-login:hover {
            background: #0284c7;
            transform: translateY(-2px);
        }
        .logo-img {
            height: 60px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="login-card text-center">
    <img src="/DBMSPROJECT/images/logoA.png" alt="Logo" class="logo-img">
    <h3 class="fw-bold">Faculty <span class="text-info">Portal</span></h3>
    <p class="text-muted small mb-4">Enter your credentials to manage complaints</p>

    <?php if($error != "") { ?>
        <div class="alert alert-danger py-2 small"><?php echo $error; ?></div>
    <?php } ?>

    <form action="" method="POST">
        <div class="mb-3 text-start">
            <label class="small mb-1 opacity-75">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-transparent border-0 text-white opacity-50"><i class="fas fa-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="name@college.edu" required>
            </div>
        </div>

        <div class="mb-4 text-start">
            <label class="small mb-1 opacity-75">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-transparent border-0 text-white opacity-50"><i class="fas fa-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit" name="login" class="btn btn-login w-100 text-white mb-3">
            Login to Dashboard <i class="fas fa-sign-in-alt ms-2"></i>
        </button>
        
        <div class="mt-3">
            <a href="../index.php" class="text-info text-decoration-none small"><i class="fas fa-arrow-left me-1"></i> Back to Home</a>
        </div>
    </form>
</div>

</body>
</html>