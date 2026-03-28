<?php
session_start();
include("../database/db.php");

// 1. Security Check
if (!isset($_SESSION['faculty_id'])) {
    header("Location: faculty-login.php");
    exit();
}

$f_id = $_SESSION['faculty_id'];

// 2. AUTO-SYNC: Latest details from Database
$user_check = mysqli_query($conn, "SELECT name, role, department, assigned_class FROM faculty WHERE id = '$f_id'");
$user_data  = mysqli_fetch_assoc($user_check);

$f_name  = $user_data['name'];
$f_role  = $user_data['role'];
$f_dept  = $user_data['department']; 
$f_class = $user_data['assigned_class'];

// 3. DYNAMIC QUERY LOGIC (Fixing the Dashboard visibility)
$where_clause = ""; // Base filter

if ($f_role == 'HOD') {
    // HOD sees everything in their department
    $where_clause = "WHERE department = '$f_dept'";
    $display_title = "HOD Dashboard - $f_dept Dept";
} elseif ($f_role == 'Coordinator') {
    // Coordinator sees only their assigned class & department
    $where_clause = "WHERE department = '$f_dept' AND class = '$f_class'";
    $display_title = "Coordinator Panel - $f_class";
} else {
    // Faculty sees assigned complaints or pending in their dept
    $where_clause = "WHERE (department = '$f_dept' AND status='Pending') OR assigned_to = '$f_id'";
    $display_title = "Faculty Portal";
}

// Final Data Query
$query = "SELECT * FROM complaints $where_clause ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

// 4. FIXED STATS LOGIC (In sync with the role filter)
$total_complaints = mysqli_num_rows($result);
$resolved_count   = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM complaints $where_clause AND status='Resolved'"));
$pending_count    = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM complaints $where_clause AND status='Pending'"));

if ($_SESSION['role'] == 'Coordinator') {
    $f_class = $_SESSION['assigned_class'];
    $query = "SELECT * FROM complaints WHERE department = '$f_dept' AND class = '$f_class'";
} else {
    $query = "SELECT * FROM complaints WHERE department = '$f_dept'";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Faculty Dashboard | CampusVoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --sidebar-bg: #0f172a; --brand-blue: #0ea5e9; }
        body { background-color: #f1f5f9; font-family: 'Inter', sans-serif; overflow-x: hidden; }
        .sidebar { min-height: 100vh; background: var(--sidebar-bg); color: white; position: sticky; top: 0; }
        .nav-link { color: #94a3b8; padding: 12px 20px; border-radius: 10px; margin: 5px 0; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { background: #1e293b; color: var(--brand-blue); }
        .glass-card { background: white; border: none; border-radius: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); transition: 0.3s; }
        .glass-card:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
        .status-pill { border-radius: 50px; padding: 5px 12px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
        .btn-solve { background: var(--brand-blue); color: white; border-radius: 10px; font-weight: 600; border: none; }
        .table thead { background: #f8fafc; border-bottom: 2px solid #e2e8f0; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar d-none d-md-block p-0">
            <div class="p-4 text-center">
                <h4 class="fw-bold text-white mb-0">Campus<span class="text-info">Voice</span></h4>
                <small class="text-muted text-uppercase fw-bold" style="font-size: 10px;"><?php echo $f_role; ?> Account</small>
            </div>
            <nav class="nav flex-column px-3 mt-3">
                <a class="nav-link active" href="#"><i class="fas fa-th-large me-2"></i> Dashboard</a>
                <a class="nav-link" href="all-complaint.php"><i class="fas fa-list me-2"></i> All Complaints</a>
                <?php if($f_role == 'HOD') { ?>
                    <a class="nav-link" href="faculty-list.php"><i class="fas fa-users-cog me-2"></i> Manage Faculty</a>
                <?php } ?>
                <a class="nav-link" href="faculty-profile.php"><i class="fas fa-user-circle me-2"></i> My Profile</a>
                <a class="nav-link text-danger mt-auto mb-4" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
            </nav>
        </div>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-0 text-dark"><?php echo $display_title; ?></h3>
                    <p class="text-muted mb-0 small">Welcome, Prof. <?php echo $f_name; ?> | Dept: <?php echo $f_dept; ?></p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white p-2 rounded-circle shadow-sm border"><i class="fas fa-bell text-warning"></i></div>
                    <img src="../images/<?php echo $_SESSION['image']; ?>" class="rounded-circle border" width="45" height="45">
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="glass-card p-4 border-start border-primary border-5">
                        <div class="d-flex justify-content-between align-items-center">
                            <div><p class="text-muted mb-1 small fw-bold">TOTAL ASSIGNED</p><h2 class="fw-bold mb-0"><?php echo $total_complaints; ?></h2></div>
                            <div class="fs-1 text-primary opacity-25"><i class="fas fa-inbox"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass-card p-4 border-start border-success border-5">
                        <div class="d-flex justify-content-between align-items-center">
                            <div><p class="text-muted mb-1 small fw-bold">RESOLVED</p><h2 class="fw-bold text-success mb-0"><?php echo $resolved_count; ?></h2></div>
                            <div class="fs-1 text-success opacity-25"><i class="fas fa-check-double"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass-card p-4 border-start border-warning border-5">
                        <div class="d-flex justify-content-between align-items-center">
                            <div><p class="text-muted mb-1 small fw-bold">PENDING</p><h2 class="fw-bold text-warning mb-0"><?php echo $pending_count; ?></h2></div>
                            <div class="fs-1 text-warning opacity-25"><i class="fas fa-hourglass-half"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-card overflow-hidden">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white">
                    <h5 class="fw-bold mb-0">Recent Grievances</h5>
                    <span class="badge bg-light text-dark border rounded-pill px-3">Live Feed</span>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-muted small uppercase">
                                <th class="ps-4">Student Details</th>
                                <th>Subject</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th class="text-center">Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) { 
                                    $status_class = ($row['status'] == 'Resolved') ? 'bg-success' : (($row['status'] == 'In Progress') ? 'bg-info' : 'bg-warning');
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($row['student_name']); ?>&background=random" class="rounded-circle me-3" width="35">
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 0.9rem;"><?php echo $row['student_name']; ?></div>
                                            <div class="text-muted" style="font-size: 0.75rem;"><?php echo $row['class']; ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-dark fw-semibold small mb-1"><?php echo $row['subject']; ?></div>
                                    <div class="text-muted" style="font-size: 0.7rem;"><i class="far fa-calendar-alt me-1"></i><?php echo date('d M, Y', strtotime($row['created_at'])); ?></div>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo ($row['priority']=='High')?'danger':'info'; ?>-subtle text-<?php echo ($row['priority']=='High')?'danger':'info'; ?> rounded-pill px-3">
                                        <?php echo $row['priority']; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-pill <?php echo $status_class; ?> text-white"><?php echo $row['status']; ?></span>
                                </td>
                                <td class="text-center">
                                    <a href="view-complaints.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-solve px-3">
                                        Solve <i class="fas fa-arrow-right ms-1" style="font-size: 0.6rem;"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php } } else { ?>
                                <tr>
                                    <td colspan="5" class="text-center p-5">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="opacity-25 mb-3">
                                        <p class="text-muted">No complaints assigned to you yet.</p>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>