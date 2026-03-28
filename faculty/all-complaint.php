<?php
session_start();
include("../database/db.php");

if (!isset($_SESSION['faculty_id'])) { header("Location: faculty_login.php"); exit(); }

$f_id   = $_SESSION['faculty_id'];
$f_dept = $_SESSION['department'];
$f_role = $_SESSION['role'];
$f_class = isset($_SESSION['assigned_class']) ? $_SESSION['assigned_class'] : '';

// --- Filters Logic ---
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : 'All';

if ($f_role == 'HOD') {
    $base_query = "WHERE department = '$f_dept'";
} elseif ($f_role == 'Coordinator') {
    $base_query = "WHERE department = '$f_dept' AND class = '$f_class'";
} else {
    // Normal Faculty ke liye sirf assigned ya pending
    $base_query = "WHERE (department = '$f_dept' AND status='Pending') OR assigned_to = '$f_id'";
}

if ($status_filter != 'All') {
    $base_query .= " AND status = '$status_filter'";
}
if (!empty($search)) {
    $base_query .= " AND (student_name LIKE '%$search%' OR subject LIKE '%$search%' OR id LIKE '%$search%')";
}

if ($_SESSION['role'] == 'Coordinator') {
    $f_class = $_SESSION['assigned_class'];
    $query = "SELECT * FROM complaints WHERE department = '$f_dept' AND class = '$f_class'";
} else {
    $query = "SELECT * FROM complaints WHERE department = '$f_dept'";
}


$query = "SELECT * FROM complaints $base_query ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Complaints | CampusVoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8fafc; font-family: 'Inter', sans-serif; }
        .main-card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .nav-tabs { border: none; }
        .nav-link { border: none !important; color: #64748b; font-weight: 600; padding: 12px 25px; border-radius: 10px !important; }
        .nav-link.active { background: #0ea5e9 !important; color: white !important; }
        .search-box { border-radius: 10px; border: 1px solid #e2e8f0; padding-left: 40px; }
        .search-icon { position: absolute; left: 15px; top: 12px; color: #94a3b8; }
        .priority-dot { height: 10px; width: 10px; border-radius: 50%; display: inline-block; margin-right: 5px; }
    </style>
</head>
<body>

<div class="container-fluid py-4 px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark"><i class="fas fa-clipboard-list text-info me-2"></i> Complaint Management</h3>
        <a href="faculty-dashboard.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-7">
            <ul class="nav nav-tabs gap-2">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($status_filter=='All')?'active':''; ?>" href="?status=All">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($status_filter=='Pending')?'active':''; ?>" href="?status=Pending">Pending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($status_filter=='Resolved')?'active':''; ?>" href="?status=Resolved">Resolved</a>
                </li>
            </ul>
        </div>
        <div class="col-md-5">
            <form action="" method="GET" class="position-relative">
                <i class="fas fa-search search-icon"></i>
                <input type="hidden" name="status" value="<?php echo $status_filter; ?>">
                <input type="text" name="search" class="form-control search-box" placeholder="Search by Student Name, ID or Subject..." value="<?php echo $search; ?>">
            </form>
        </div>
    </div>

    <div class="card main-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Student Details</th>
                        <th>Complaint Subject</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($result) > 0) { 
                        while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td class="ps-4 fw-bold text-muted">#<?php echo $row['id']; ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <?php echo strtoupper(substr($row['student_name'], 0, 1)); ?>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold"><?php echo $row['student_name']; ?></h6>
                                    <small class="text-muted"><?php echo $row['class']; ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-truncate d-inline-block" style="max-width: 200px;"><?php echo $row['subject']; ?></span><br>
                            <span class="badge bg-light text-dark border-0 small text-muted"><?php echo $row['category']; ?></span>
                        </td>
                        <td>
                            <?php 
                                $p_color = ($row['priority']=='High') ? 'danger' : (($row['priority']=='Medium') ? 'warning' : 'success');
                            ?>
                            <span class="priority-dot bg-<?php echo $p_color; ?>"></span>
                            <span class="small fw-semibold text-<?php echo $p_color; ?>"><?php echo $row['priority']; ?></span>
                        </td>
                        <td>
                            <?php 
                                $s_bg = ($row['status']=='Resolved') ? 'success' : (($row['status']=='In Progress') ? 'info' : 'warning');
                            ?>
                            <span class="badge bg-<?php echo $s_bg; ?>-subtle text-<?php echo $s_bg; ?> rounded-pill px-3 py-2">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                        <td class="text-muted small"><?php echo date('d M, Y', strtotime($row['created_at'])); ?></td>
                        <td class="text-center">
                            <a href="view-complaints.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-white border shadow-sm rounded-pill px-3">
                                <i class="fas fa-eye text-primary me-1"></i> View
                            </a>
                        </td>
                    </tr>
                    <?php } } else { ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">No complaints found.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>