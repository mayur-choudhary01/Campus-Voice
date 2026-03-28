<?php
session_start();
include("../database/db.php");

// Security: Sirf HOD hi reports dekh sakega
if (!isset($_SESSION['faculty_id']) || $_SESSION['role'] !== 'HOD') {
    header("Location: faculty_dashboard.php");
    exit();
}

$f_dept = $_SESSION['department'];

// 1. DATA FETCHING LOGIC (Queries)

// Total Complaints
$total_q = mysqli_query($conn, "SELECT count(*) as total FROM complaints WHERE department = '$f_dept'");
$total_data = mysqli_fetch_assoc($total_q);
$total = $total_data['total'];

// Status Wise Count (For Pie Chart)
$res_q = mysqli_query($conn, "SELECT count(*) as count FROM complaints WHERE department = '$f_dept' AND status = 'Resolved'");
$resolved = mysqli_fetch_assoc($res_q)['count'];

$pend_q = mysqli_query($conn, "SELECT count(*) as count FROM complaints WHERE department = '$f_dept' AND status = 'Pending'");
$pending = mysqli_fetch_assoc($pend_q)['count'];

$prog_q = mysqli_query($conn, "SELECT count(*) as count FROM complaints WHERE department = '$f_dept' AND status = 'In Progress'");
$progress = mysqli_fetch_assoc($prog_q)['count'];

// Category Wise Count (For Bar Chart)
// Maan lo categories hain: Infrastructure, Academic, Library, Others
$cat_q = mysqli_query($conn, "SELECT category, count(*) as count FROM complaints WHERE department = '$f_dept' GROUP BY category");
$categories = [];
$counts = [];
while($row = mysqli_fetch_assoc($cat_q)) {
    $categories[] = $row['category'];
    $counts[] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Department Reports | CampusVoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background: #f8fafc; font-family: 'Inter', sans-serif; }
        .stat-card { border: none; border-radius: 15px; transition: 0.3s; }
        .chart-container { background: white; border-radius: 20px; padding: 25px; box-shadow: 0 10px 25px rgba(0,0,0,0.02); }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="fas fa-chart-line text-primary me-2"></i> Departmental Analytics</h3>
        <button onclick="window.print()" class="btn btn-outline-dark btn-sm rounded-pill px-3">
            <i class="fas fa-print me-1"></i> Print Report
        </button>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card stat-card bg-primary text-white p-4 shadow-sm">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-white-50">Total Complaints</h6>
                        <h2 class="fw-bold mb-0"><?php echo $total; ?></h2>
                    </div>
                    <i class="fas fa-folder-open fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-success text-white p-4 shadow-sm">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-white-50">Resolved Cases</h6>
                        <h2 class="fw-bold mb-0"><?php echo $resolved; ?></h2>
                    </div>
                    <i class="fas fa-check-circle fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-warning text-dark p-4 shadow-sm">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-black-50">Pending Actions</h6>
                        <h2 class="fw-bold mb-0"><?php echo $pending + $progress; ?></h2>
                    </div>
                    <i class="fas fa-clock fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="chart-container h-100">
                <h5 class="fw-bold mb-4">Complaint Status Distribution</h5>
                <canvas id="statusPieChart"></canvas>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="chart-container h-100">
                <h5 class="fw-bold mb-4">Issue Categories Breakdown</h5>
                <canvas id="categoryBarChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
// 1. Status Pie Chart Logic
const ctx1 = document.getElementById('statusPieChart').getContext('2d');
new Chart(ctx1, {
    type: 'doughnut',
    data: {
        labels: ['Resolved', 'Pending', 'In Progress'],
        datasets: [{
            data: [<?php echo $resolved; ?>, <?php echo $pending; ?>, <?php echo $progress; ?>],
            backgroundColor: ['#10b981', '#f59e0b', '#0ea5e9'],
            borderWidth: 0
        }]
    },
    options: {
        plugins: { legend: { position: 'bottom' } },
        cutout: '70%'
    }
});

// 2. Category Bar Chart Logic
const ctx2 = document.getElementById('categoryBarChart').getContext('2d');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($categories); ?>,
        datasets: [{
            label: 'Number of Complaints',
            data: <?php echo json_encode($counts); ?>,
            backgroundColor: '#6366f1',
            borderRadius: 8
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true, grid: { display: false } },
            x: { grid: { display: false } }
        }
    }
});
</script>

</body>
</html>