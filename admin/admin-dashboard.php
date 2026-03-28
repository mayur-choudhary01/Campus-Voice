<?php
session_start();
include('../database/db.php');

// Security Check
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

// 1. TOTAL COMPLAINTS (Ye missing tha, isliye error aa rahi thi)
$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM complaints");
$total_c = mysqli_fetch_assoc($total_query);

// 2. PENDING CASES
$pending_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM complaints WHERE status='Pending'");
$pending_c = mysqli_fetch_assoc($pending_query);

// 3. RESOLVED CASES
$resolved_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM complaints WHERE status='Resolved'");
$resolved_c = mysqli_fetch_assoc($resolved_query);

// 4. TOTAL STUDENTS
$students_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM students");
$total_students = mysqli_fetch_assoc($students_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | CampusVoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --brand-color: #764ba2;
        }

        body {
            background-color: #f8f9fa;
        }

        /* Sidebar Style */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            background: #fff;
            border-right: 1px solid #ddd;
            padding-top: 20px;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
        }   

        .nav-link {
            color: #333;
            padding: 12px 20px;
            font-weight: 500;
            transition: 0.3s;
        }

        .nav-link:hover,
        .nav-link.active {
            background: #f0ebf7;
            color: var(--brand-color);
            border-right: 4px solid var(--brand-color);
        }

        .nav-link i {
            width: 25px;
        }

        /* Card Style */
        .stat-card {
            border: none;
            border-radius: 15px;
            transition: 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="text-center mb-4">
            <h4 class="fw-bold text-primary"><i class="fas fa-university me-2"></i>Admin Panel</h4>
            <span class="badge bg-light text-dark border">Indore Campus</span>
        </div>
        <nav class="nav flex-column">
            <a class="nav-link active" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a class="nav-link" href="manage-notices.php"><i class="fas fa-bullhorn"></i> Manage Notices</a>
            <a class="nav-link" href="manage-faculty.php"><i class="fas fa-user-tie"></i> HOD & Faculty</a>
            <a class="nav-link" href="manage-complaints.php"><i class="fas fa-list-alt"></i> Complaints</a>
            <a class="nav-link" href="settings.php"><i class="fas fa-cog"></i> Site Settings</a>
            <hr>
            <a class="nav-link text-danger" href="ad-logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Welcome, <?php echo $_SESSION['admin_user']; ?>! 👋</h2>
                <p class="text-muted">Yahan se aap poora CampusVoice portal control kar sakte hain.</p>
            </div>
            <div class="admin-profile d-flex align-items-center">
                <div class="text-end me-3">
                    <p class="mb-0 fw-bold">Admin User</p>
                    <small class="text-success">Online</small>
                </div>
                <img src="https://ui-avatars.com/api/?name=Admin&background=764ba2&color=fff" class="rounded-circle"
                    width="50">
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card stat-card bg-white p-3 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Total Complaints</h6>
                            <h3 class="fw-bold mb-0"><?php echo $total_c['total']; ?></h3>
                        </div>
                        <div class="icon-box bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-folder-open text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-white p-3 shadow-sm border-start border-warning border-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Pending Cases</h6>
                            <h3 class="fw-bold mb-0"><?php echo $pending_c['total']; ?></h3>
                        </div>
                        <div class="icon-box bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fas fa-clock text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-white p-3 shadow-sm border-start border-success border-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Resolved</h6>
                            <h3 class="fw-bold mb-0"><?php echo $resolved_c['total']; ?></h3>
                        </div>
                        <div class="icon-box bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-check-circle text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-white p-3 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Students</h6>
                            <h3 class="fw-bold mb-0"><?php echo $total_students['total']; ?></h3>
                        </div>
                        <div class="icon-box bg-info bg-opacity-10 p-3 rounded">
                            <i class="fas fa-users text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm p-4">
                    <h5 class="fw-bold mb-4">Recent Activity</h5>
                    <p class="text-muted">Yahan naye complaints ki list aayegi (Table format mein)...</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 bg-primary text-white">
                    <h5 class="fw-bold">Quick Task</h5>
                    <p class="small">Naya notice turant publish karein:</p>
                    <a href="manage-notices.php" class="btn btn-light btn-sm w-100">Add New Notice</a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>