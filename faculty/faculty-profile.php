<?php
session_start();
include("../database/db.php");

if (!isset($_SESSION['faculty_id'])) {
    header("Location: faculty_login.php");
    exit();
}

$f_id = $_SESSION['faculty_id'];

// Database se fresh data nikalna (In case update hua ho)
$query = "SELECT * FROM faculty WHERE id = '$f_id'";
$res = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | CampusVoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --sidebar-bg: #0f172a; --accent: #0ea5e9; }
        body { background: #f1f5f9; font-family: 'Inter', sans-serif; }
        
        /* Sidebar Same as Dashboard */
        .sidebar { min-height: 100vh; background: var(--sidebar-bg); color: white; position: fixed; width: 250px; }
        .main-content { margin-left: 250px; padding: 40px; }
        
        .profile-card {
            background: white;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .profile-header {
            background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
            height: 120px;
        }
        .profile-img-container {
            margin-top: -60px;
            position: relative;
            display: inline-block;
        }
        .profile-img {
            width: 130px;
            height: 130px;
            object-fit: cover;
            border: 5px solid white;
            border-radius: 50%;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .form-label { font-weight: 600; color: #475569; font-size: 0.9rem; }
        .form-control { border-radius: 10px; padding: 10px 15px; border: 1px solid #e2e8f0; }
        .form-control:focus { box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1); border-color: var(--accent); }
        
        .nav-link { color: #94a3b8; padding: 12px 20px; border-radius: 10px; margin: 5px 0; }
        .nav-link.active { background: #1e293b; color: var(--accent); }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-md-2 sidebar p-3 d-none d-md-block">
            <div class="text-center mb-4">
                <img src="../images/logo.png" width="50" alt="Logo">
                <h5 class="mt-2 fw-bold text-white">Campus<span class="text-info">Voice</span></h5>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link" href="faculty-dashboard.php"><i class="fas fa-th-large me-2"></i> Dashboard</a>
                <a class="nav-link" href="#"><i class="fas fa-inbox me-2"></i> Complaints</a>
                <a class="nav-link active" href="#"><i class="fas fa-user-circle me-2"></i> Profile</a>
                <a class="nav-link text-danger mt-5" href="logout.php"><i class="fas fa-power-off me-2"></i> Logout</a>
            </nav>
        </div>

        <div class="col-md-10 main-content">
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <h3 class="fw-bold mb-4">Account Settings</h3>
                    
                    <div class="profile-card">
                        <div class="profile-header"></div>
                        <div class="px-4 pb-4">
                            <div class="profile-img-container mb-3">
                                <img src="../images/<?php echo $user['image']; ?>" class="profile-img" alt="Profile">
                                <label for="upload-img" class="btn btn-sm btn-dark rounded-circle position-absolute bottom-0 end-0 shadow">
                                    <i class="fas fa-camera"></i>
                                </label>
                            </div>
                            
                            <h4 class="fw-bold mb-0"><?php echo $user['name']; ?></h4>
                            <p class="text-muted"><?php echo $user['role']; ?> | <?php echo $user['department']; ?></p>
                            
                            <hr class="my-4 opacity-50">

                            <form action="update-profile-logic.php" method="POST" enctype="multipart/form-data">
                                <input type="file" id="upload-img" name="profile_image" hidden onchange="this.form.submit()">
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" name="name" class="form-control" value="<?php echo $user['name']; ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email (Login ID)</label>
                                        <input type="email" class="form-control" value="<?php echo $user['email']; ?>" disabled>
                                        <small class="text-muted">Email cannot be changed.</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Designation</label>
                                        <input type="text" name="designation" class="form-control" value="<?php echo $user['designation']; ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Department</label>
                                        <input type="text" class="form-control" value="<?php echo $user['department']; ?>" disabled>
                                    </div>
                                    <?php if($user['role'] == 'Coordinator') { ?>
                                    <div class="col-md-12">
                                        <label class="form-label">Assigned Class</label>
                                        <input type="text" class="form-control" value="<?php echo $user['assigned_class']; ?>" disabled>
                                    </div>
                                    <?php } ?>

                                    <div class="col-md-12 mt-4">
                                        <h5 class="fw-bold mb-3 border-bottom pb-2">Security</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">New Password</label>
                                        <input type="password" name="new_password" class="form-control" placeholder="Leave blank to keep current">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Confirm New Password</label>
                                        <input type="password" name="confirm_password" class="form-control" placeholder="••••••••">
                                    </div>
                                </div>

                                <div class="mt-5 d-flex gap-2">
                                    <button type="submit" name="update_profile" class="btn btn-primary px-4 rounded-pill">Save Changes</button>
                                    <a href="faculty-dashboard.php" class="btn btn-outline-secondary px-4 rounded-pill">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>