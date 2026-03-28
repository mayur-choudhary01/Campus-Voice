<?php
session_start();
include('../database/db.php');

// Security Check
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

// 1. Status Update Logic
if (isset($_POST['update_status'])) {
    $c_id = $_POST['complaint_id'];
    $new_status = $_POST['status'];
    
    $update_query = "UPDATE complaints SET status = '$new_status' WHERE id = $c_id";
    if (mysqli_query($conn, $update_query)) {
        $success_msg = "Complaint status updated successfully!";
    }
}

// 2. Fetch All Complaints
$complaints_res = mysqli_query($conn, "SELECT * FROM complaints ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Complaints | CampusVoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7fe;
            color: #2b3674;
        }

        /* Top Navbar */
        .top-nav {
            background: linear-gradient(90deg, #4318FF 0%, #707EAE 100%);
            padding: 15px 0;
            margin-bottom: 40px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .glass-card {
            background: white;
            border: none;
            border-radius: 20px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin-bottom: 20px;
        }

        /* Table Styling */
        .table-custom thead {
            background-color: #f8fbff;
            color: #a3aed0;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
        }

        .table-custom td {
            padding: 18px 12px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f4f9;
        }

        /* Status Badges */
        .status-badge {
            font-size: 0.8rem;
            padding: 6px 14px;
            border-radius: 10px;
            font-weight: 700;
        }
        .bg-pending-light { background: #FFF4E5; color: #FFB547; }
        .bg-resolved-light { background: #E6FFF1; color: #01B574; }

        .btn-update {
            background: #4318FF;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 8px 16px;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-update:hover { background: #3311cc; transform: scale(1.05); }

        .form-select-custom {
            border-radius: 10px;
            border: 1px solid #E0E5F2;
            font-size: 0.85rem;
            padding: 6px 10px;
            background-color: #f8fbff;
        }

        .back-link { color: white; text-decoration: none; font-weight: 600; opacity: 0.8; }
        .back-link:hover { opacity: 1; color: white; }
    </style>
</head>
<body>

    <nav class="top-nav">
        <div class="container d-flex justify-content-between align-items-center">
            <h4 class="fw-bold text-white m-0"><i class="fas fa-university me-2"></i>CampusVoice</h4>
            <a href="admin-dashboard.php" class="back-link"><i class="fas fa-arrow-left me-2"></i>Admin Dashboard</a>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="d-flex justify-content-between align-items-center mb-4 px-2">
            <div>
                <h2 class="fw-bold m-0 text-dark">Student Complaints 📩</h2>
                <p class="text-muted small mb-0">Manage and resolve issues submitted by students.</p>
            </div>
            <div class="text-end">
                <span class="badge bg-white text-primary border rounded-pill px-3 py-2 shadow-sm">
                    Total Issues: <?php echo mysqli_num_rows($complaints_res); ?>
                </span>
            </div>
        </div>

        <?php if(isset($success_msg)) echo "<div class='alert alert-success rounded-4 border-0 shadow-sm mb-4'>$success_msg</div>"; ?>

        <div class="glass-card overflow-hidden p-0">
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Student Info</th>
                            <th>Subject</th>
                            <th>Date Filed</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        while($row = mysqli_fetch_assoc($complaints_res)) { 
                            $status = isset($row['status']) ? $row['status'] : 'Pending';
                            $badge_class = ($status == 'Pending') ? 'bg-pending-light' : 'bg-resolved-light';
                            
                            // Anonymous Check (is_anonymous column database mein hona chahiye)
                            $is_anon = (isset($row['is_anonymous']) && $row['is_anonymous'] == 1);
                            
                            $s_name = $is_anon ? "Confidential Student" : (isset($row['student_name']) ? $row['student_name'] : 'Unknown');
                            $enroll = $is_anon ? "HIDDEN" : (isset($row['enrollment_no']) ? $row['enrollment_no'] : 'N/A');
                            $msg = isset($row['message']) ? $row['message'] : 'No details provided';
                        ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3 text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px; font-weight: bold; background-color: <?php echo $is_anon ? '#6c757d' : '#4318FF'; ?>;">
                                        <?php echo $is_anon ? '<i class="fas fa-user-secret"></i>' : strtoupper(substr($s_name, 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold <?php echo $is_anon ? 'text-secondary' : 'text-dark'; ?>"><?php echo $s_name; ?></div>
                                        <div class="text-muted small"><?php echo $enroll; ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark"><?php echo $row['subject']; ?></div>
                                <div class="text-muted small text-truncate" style="max-width: 250px;">
                                    <?php echo $msg; ?>
                                </div>
                            </td>
                            <td>
                                <div class="small text-dark fw-medium"><?php echo date('d M, Y', strtotime($row['created_at'])); ?></div>
                                <div class="text-muted small"><?php echo date('h:i A', strtotime($row['created_at'])); ?></div>
                            </td>
                            <td>
                                <span class="status-badge <?php echo $badge_class; ?>">
                                    ● <?php echo $status; ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" class="d-flex justify-content-center gap-2">
                                    <input type="hidden" name="complaint_id" value="<?php echo $row['id']; ?>">
                                    <select name="status" class="form-select-custom shadow-sm border-0">
                                        <option value="Pending" <?php if($status == 'Pending') echo 'selected'; ?>>Pending</option>
                                        <option value="Resolved" <?php if($status == 'Resolved') echo 'selected'; ?>>Resolved</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn-update shadow-sm">
                                        Save
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php } // While loop ends here ?>

                        <?php if(mysqli_num_rows($complaints_res) == 0) : ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-check-circle fa-3x text-light mb-3"></i>
                                <p class="text-muted">No complaints found. All students are happy!</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>