<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | CampusVoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        .brand-logo {
            font-size: 2rem;
            font-weight: 800;
            color: #764ba2;
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-login {
            background: #764ba2;
            color: white;
            border: none;
            padding: 12px;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-login:hover {
            background: #5a368c;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

<div class="login-card animate__animated animate__fadeIn">
    <div class="brand-logo">
        <i class="fas fa-user-shield me-2"></i>CampusVoice
    </div>
    <h5 class="text-center text-muted mb-4">Admin Control Panel</h5>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger py-2 small text-center"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="adminlogic.php" method="POST">
        <div class="mb-3">
            <label class="form-label small fw-bold">Username</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="fas fa-user text-muted"></i></span>
                <input type="text" name="username" class="form-control" placeholder="Enter username" required>
            </div>
        </div>
        
        <div class="mb-4">
            <label class="form-label small fw-bold">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="fas fa-lock text-muted"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
        </div>

        <button type="submit" name="login" class="btn btn-login w-100 rounded-pill">
            Login Securely <i class="fas fa-sign-in-alt ms-2"></i>
        </button>
    </form>
    
    <div class="text-center mt-4">
        <a href="admin-dashboard.php" class="text-decoration-none small text-muted">
            <i class="fas fa-arrow-left me-1"></i> Back to Site
        </a>
    </div>
</div>

</body>
</html>