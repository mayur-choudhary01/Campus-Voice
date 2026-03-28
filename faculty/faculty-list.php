<?php
session_start();
include("../database/db.php");

// 1. Check Login
if (!isset($_SESSION['faculty_id'])) {
    header("Location: faculty_login.php");
    exit();
}

$f_id = $_SESSION['faculty_id'];

// 2. 🔥 LIVE SYNC: Database se "Taaza" Department aur Role fetch karo
// Isse Admin ke changes bina logout kiye turant dikhenge
$sync_res = mysqli_query($conn, "SELECT department, role FROM faculty WHERE id = '$f_id'");
$sync_data = mysqli_fetch_assoc($sync_res);

$f_dept = $sync_data['department'];
$f_role = $sync_data['role'];

// 3. Security: Sirf HOD hi is page ko dekh sakta hai
if ($f_role !== 'HOD') {
    echo "<div style='text-align:center; margin-top:50px;'>
            <h3>Access Denied!</h3>
            <p>Only HOD can view this page. Your current role is: <b>$f_role</b></p>
            <a href='faculty_dashboard.php' class='btn btn-primary'>Back to Dashboard</a>
          </div>";
    exit();
}

// 4. Query: Apne department ke baaki sabhi faculty ko nikaalo (TRIM and CASE fix)
$query = "SELECT * FROM faculty 
          WHERE TRIM(LOWER(department)) = TRIM(LOWER('$f_dept')) 
          AND role != 'HOD' 
          ORDER BY role DESC, name ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Faculty Members | <?php echo $f_dept; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f1f5f9; font-family: 'Inter', sans-serif; }
        .faculty-card {
            border: none;
            border-radius: 20px;
            transition: 0.3s;
            overflow: hidden;
            background: white;
        }
        .faculty-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        .card-header-img {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            height: 80px;
        }
        .user-avatar {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border: 4px solid white;
            margin-top: -45px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .role-badge {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 5px 12px;
            border-radius: 50px;
        }
        .stats-box { background: #f8fafc; border-radius: 12px; padding: 10px; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-0">Department Faculty</h2>
            <p class="text-muted">Managing all educators in <strong><?php echo $f_dept; ?> Department</strong></p>
        </div>
        <a href="../admin/admin_add_faculty.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-user-plus me-2"></i> Add New Faculty
        </a>
    </div>

    <div class="row g-4">
        <?php if(mysqli_num_rows($result) > 0) { 
            while($row = mysqli_fetch_assoc($result)) { 
                $faculty_id = $row['id'];
                // Count resolved complaints for this specific faculty
                $count_query = "SELECT count(*) as total FROM complaints WHERE assigned_to = '$faculty_id' AND status = 'Resolved'";
                $c_res = mysqli_fetch_assoc(mysqli_query($conn, $count_query));
        ?>
        <div class="col-md-4 col-lg-3">
            <div class="card faculty-card shadow-sm h-100">
                <div class="card-header-img text-center"></div>
                <div class="card-body text-center pt-0">
                    <img src="../images/<?php echo $row['image']; ?>" class="user-avatar rounded-circle mb-3" alt="Faculty">
                    <h5 class="fw-bold mb-1"><?php echo $row['name']; ?></h5>
                    <p class="text-muted small mb-3"><?php echo $row['designation']; ?></p>
                    
                    <span class="role-badge mb-3 d-inline-block <?php echo ($row['role']=='Coordinator')?'bg-warning-subtle text-warning':'bg-info-subtle text-info'; ?>">
                        <?php echo $row['role']; ?>
                    </span>

                    <div class="stats-box d-flex justify-content-around mt-3">
                        <div class="text-center">
                            <h6 class="fw-bold mb-0"><?php echo $c_res['total']; ?></h6>
                            <small class="text-muted" style="font-size: 10px;">Resolved</small>
                        </div>
                        <div class="text-center border-start ps-3">
                            <h6 class="fw-bold mb-0"><?php echo ($row['assigned_class']) ? $row['assigned_class'] : 'N/A'; ?></h6>
                            <small class="text-muted" style="font-size: 10px;">Class</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 pb-4 text-center">
                    <div class="d-flex gap-2 justify-content-center">
                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3">View Profile</button>
                        <a href="mailto:<?php echo $row['email']; ?>" class="btn btn-sm btn-light rounded-pill px-3"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <?php } } else { ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-users-slash fa-4x opacity-25"></i>
                <p class="text-muted mt-3">No other faculty members found in <b><?php echo $f_dept; ?></b> department.</p>
                <p class="small text-secondary">Check if other faculty members have exactly "<?php echo $f_dept; ?>" in their department field.</p>
            </div>
        <?php } ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>