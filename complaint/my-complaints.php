<?php
session_start();
include("../database/db.php");
if (!isset($_SESSION['student_id'])) { header("Location: login.php"); exit(); }
$s_id = $_SESSION['student_id'];

// --- SMART LOGIC ---
$active_res = mysqli_query($conn, "SELECT * FROM complaints WHERE student_id = '$s_id' AND status != 'Resolved' ORDER BY id DESC LIMIT 1");
if(mysqli_num_rows($active_res) == 0) {
    $active_res = mysqli_query($conn, "SELECT * FROM complaints WHERE student_id = '$s_id' ORDER BY id DESC LIMIT 1");
}
$latest = mysqli_fetch_assoc($active_res);
$curr_status = $latest ? $latest['status'] : 'None';
$curr_token = ($latest && !empty($latest['token'])) ? $latest['token'] : ($latest['id'] ?? '000');

// Stats for Header
$total_q = mysqli_query($conn, "SELECT COUNT(*) as total FROM complaints WHERE student_id = '$s_id'");
$total = mysqli_fetch_assoc($total_q)['total'];
$resolved_q = mysqli_query($conn, "SELECT COUNT(*) as res FROM complaints WHERE student_id = '$s_id' AND status='Resolved'");
$resolved = mysqli_fetch_assoc($resolved_q)['res'];

$result = mysqli_query($conn, "SELECT * FROM complaints WHERE student_id = '$s_id' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard | CampusVoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { --primary: #4318FF; --success: #01B574; --bg: #F4F7FE; --text: #2B3674; }
        body { background: var(--bg); font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text); }
        
        /* Glassmorphism Header */
        .glass-header { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border-radius: 20px; border: 1px solid rgba(255,255,255,0.3); }
        
        /* Stats Card */
        .stat-card { border: none; border-radius: 20px; padding: 20px; transition: 0.3s; }
        .icon-box { width: 50px; height: 50px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        
        /* Improved Tracker */
        .tracker-card { border-radius: 24px; border: none; position: relative; overflow: hidden; }
        .progress-track { height: 6px; background: #E9EDF7; border-radius: 10px; margin: 25px 0; position: relative; }
        .progress-fill { height: 100%; background: linear-gradient(90deg, #4318FF, #B09FFF); border-radius: 10px; transition: 1.5s cubic-bezier(0.17, 0.67, 0.83, 0.67); }
        
        /* List UI */
        .complaint-item { border: none; border-radius: 22px; background: #fff; margin-bottom: 15px; transition: 0.4s; border: 1px solid transparent; }
        .complaint-item:hover { border-color: var(--primary); transform: translateX(10px); }
        
        .status-badge { padding: 5px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; }
        .badge-pending { background: #FFF4E5; color: #FFB547; }
        .badge-resolved { background: #E6FFF1; color: #01B574; }
        
        /* Floating Action Button */
        .fab { position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; box-shadow: 0 10px 20px rgba(67, 24, 255, 0.4); text-decoration: none; transition: 0.3s; z-index: 1000; }
        .fab:hover { transform: scale(1.1) rotate(90deg); color: white; }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="glass-header p-4 mb-4 d-flex justify-content-between align-items-center shadow-sm">
        <div>
            <h4 class="fw-bold m-0">Hi, Mayur 👋</h4>
            <p class="text-muted small m-0">Indore Campus Support Dashboard</p>
        </div>
        <div class="d-flex gap-3">
            <div class="stat-card bg-white shadow-sm d-flex align-items-center gap-3">
                <div class="icon-box bg-light text-primary"><i class="fas fa-list"></i></div>
                <div><h6 class="m-0 fw-bold"><?php echo $total; ?></h6><small class="text-muted">Total</small></div>
            </div>
            <div class="stat-card bg-white shadow-sm d-flex align-items-center gap-3">
                <div class="icon-box bg-light text-success"><i class="fas fa-check-circle"></i></div>
                <div><h6 class="m-0 fw-bold"><?php echo $resolved; ?></h6><small class="text-muted">Solved</small></div>
            </div>
        </div>
    </div>

    <?php if($latest): ?>
    <div class="card tracker-card p-4 shadow-sm mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="fw-bold"><i class="fas fa-bolt text-warning me-2"></i>Active Tracking: #<?php echo $curr_token; ?></h6>
            <span class="status-badge <?php echo ($curr_status=='Resolved')?'badge-resolved':'badge-pending'; ?>"><?php echo $curr_status; ?></span>
        </div>
        
        <div class="progress-track">
            <?php 
                $perc = ($curr_status=='Pending') ? '25%' : (($curr_status=='In-Progress') ? '65%' : '100%');
            ?>
            <div class="progress-fill" style="width: <?php echo $perc; ?>;"></div>
        </div>

        <div class="d-flex justify-content-between text-center small fw-bold text-muted">
            <div class="step-label text-primary"><i class="fas fa-paper-plane d-block mb-1"></i>Sent</div>
            <div class="<?php echo ($curr_status != 'Pending') ? 'text-primary' : ''; ?>"><i class="fas fa-clock d-block mb-1"></i>Pending</div>
            <div class="<?php echo ($curr_status == 'In-Progress' || $curr_status == 'Resolved') ? 'text-primary' : ''; ?>"><i class="fas fa-spinner d-block mb-1"></i>Process</div>
            <div class="<?php echo ($curr_status == 'Resolved') ? 'text-success' : ''; ?>"><i class="fas fa-check-double d-block mb-1"></i>Final</div>
        </div>
    </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold">Your Complaints</h5>
        <div class="input-group w-25">
            <span class="input-group-text bg-white border-0 shadow-sm"><i class="fas fa-search text-muted"></i></span>
            <input type="text" class="form-control border-0 shadow-sm" placeholder="Search token..." id="searchInput">
        </div>
    </div>

    <div class="row" id="complaintList">
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="col-12 complaint-box">
            <div class="card complaint-item p-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-primary fw-bold">#<?php echo $row['token'] ?: $row['id']; ?></small>
                        <h6 class="fw-bold mt-1"><?php echo $row['subject']; ?></h6>
                        <p class="text-muted small mb-0"><?php echo substr($row['description'], 0, 100); ?>...</p>
                    </div>
                    <div class="text-end">
                        <span class="status-badge <?php echo ($row['status']=='Resolved')?'badge-resolved':'badge-pending'; ?> mb-2 d-inline-block"><?php echo $row['status']; ?></span>
                        <div class="text-muted" style="font-size: 10px;"><i class="far fa-calendar me-1"></i><?php echo date('d M', strtotime($row['created_at'])); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<a href="/DBMSPROJECT/complaint/add-complaint.php" class="fab"><i class="fas fa-plus"></i></a>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let items = document.querySelectorAll('.complaint-box');
    items.forEach(item => {
        let text = item.textContent.toLowerCase();
        item.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>

</body>
</html>