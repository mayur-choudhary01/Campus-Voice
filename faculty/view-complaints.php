<?php
session_start();
include("../database/db.php");

if (!isset($_SESSION['faculty_id']) || !isset($_GET['id'])) {
    header("Location: faculty_dashboard.php");
    exit();
}

$c_id = mysqli_real_escape_string($conn, $_GET['id']);
$f_dept = $_SESSION['department'];

// Data Fetch logic (Security: Sirf apne dept ki complaint dikhegi)
$query = "SELECT * FROM complaints WHERE id = '$c_id' AND department = '$f_dept'";
$res = mysqli_query($conn, $query);
$complaint = mysqli_fetch_assoc($res);

if (!$complaint) {
    echo "<div class='alert alert-danger'>Unauthorized Access or Complaint Not Found!</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complaint Details | #<?php echo $c_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .ticket-card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .status-badge { padding: 8px 16px; border-radius: 50px; font-weight: 600; font-size: 0.85rem; }
        .info-label { color: #64748b; font-size: 0.8rem; text-transform: uppercase; font-weight: 700; }
        .description-box { background: #f8fafc; border-radius: 12px; padding: 20px; border-left: 5px solid #0ea5e9; }
        .sidebar-info { background: white; border-radius: 15px; padding: 25px; }
    </style>
</head>
<body>

<div class="container py-5">
    <a href="faculty-dashboard.php" class="btn btn-link text-decoration-none text-dark mb-4 p-0">
        <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
    </a>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card ticket-card p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <span class="badge bg-primary-subtle text-primary mb-2">ID: #<?php echo $complaint['id']; ?></span>
                        <h3 class="fw-bold"><?php echo $complaint['subject']; ?></h3>
                        <p class="text-muted small"><i class="far fa-clock me-1"></i> Submitted on: <?php echo date('d M Y, h:i A', strtotime($complaint['created_at'])); ?></p>
                    </div>
                    <?php 
                        $status_cls = ($complaint['status']=='Resolved') ? 'bg-success' : (($complaint['status']=='In Progress') ? 'bg-info' : 'bg-warning');
                    ?>
                    <span class="status-badge <?php echo $status_cls; ?> text-white"><?php echo $complaint['status']; ?></span>
                </div>

                <div class="mb-4">
                    <p class="info-label">Category</p>
                    <span class="badge bg-light text-dark border px-3 py-2"><?php echo $complaint['category']; ?></span>
                </div>

                <div class="mb-4">
                    <p class="info-label">Description</p>
                    <div class="description-box">
                        <?php echo nl2br($complaint['description']); ?>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="fw-bold mb-3"><i class="fas fa-edit me-2"></i> Take Action</h5>
                <form action="process-action.php" method="POST">
                    <input type="hidden" name="complaint_id" value="<?php echo $c_id; ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Update Status</label>
                            <select name="status" class="form-select border-2">
                                <option value="Pending" <?php if($complaint['status']=='Pending') echo 'selected'; ?>>Pending</option>
                                <option value="In Progress" <?php if($complaint['status']=='In Progress') echo 'selected'; ?>>In Progress</option>
                                <option value="Resolved" <?php if($complaint['status']=='Resolved') echo 'selected'; ?>>Resolved</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Faculty Remarks</label>
                            <textarea name="remarks" class="form-control border-2" rows="4" placeholder="Student ko batayein ki kya action liya gaya..."></textarea>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" name="submit_action" class="btn btn-primary px-5 py-2 fw-bold rounded-pill">
                                Update Complaint Status
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sidebar-info shadow-sm">
                <h5 class="fw-bold mb-4 border-bottom pb-2">Student Information</h5>
                <div class="mb-3">
                    <p class="info-label mb-1">Name</p>
                    <p class="fw-bold mb-0"><?php echo $complaint['student_name']; ?></p>
                </div>
                <div class="mb-3">
                    <p class="info-label mb-1">Enrollment No.</p>
                    <p class="fw-bold mb-0">0108CS221XXX</p> </div>
                <div class="mb-3">
                    <p class="info-label mb-1">Class / Section</p>
                    <p class="fw-bold mb-0 text-primary"><?php echo $complaint['class']; ?></p>
                </div>
                <div class="mb-0">
                    <p class="info-label mb-1">Department</p>
                    <p class="fw-bold mb-0"><?php echo $complaint['department']; ?></p>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4 p-4 text-center <?php echo ($complaint['priority']=='High')?'bg-danger-subtle':'bg-primary-subtle'; ?>">
                <p class="info-label mb-1">Priority Level</p>
                <h4 class="fw-bold <?php echo ($complaint['priority']=='High')?'text-danger':'text-primary'; ?> mb-0">
                    <i class="fas fa-exclamation-circle me-1"></i> <?php echo $complaint['priority']; ?>
                </h4>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>