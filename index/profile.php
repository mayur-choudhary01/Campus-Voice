<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
include("../database/db.php");

// Security Check
if (!isset($_SESSION['student_id'])) {
    header("Location: ../login1.php");
    exit();
}

$s_id = $_SESSION['student_id'];

// Profile Update Logic
if (isset($_POST['update_profile'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $update_sql = "UPDATE students SET name='$name' WHERE id='$s_id'";
    if (mysqli_query($conn, $update_sql)) {
        $_SESSION['name'] = $name;
        header("Location: profile.php?success=1");
        exit();
    }
}

$res = mysqli_query($conn, "SELECT * FROM students WHERE id='$s_id'");
$student = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elite Profile | CampusVoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-color: #f4f7fe;
            --card-bg: rgba(255, 255, 255, 0.9);
            --text-color: #2b3674;
            --accent-color: #4318FF;
            --input-bg: #F4F7FE;
        }

        [data-theme="dark"] {
            --bg-color: #0b1437;
            --card-bg: rgba(17, 24, 60, 0.8);
            --text-color: #ffffff;
            --accent-color: #7551FF;
            --input-bg: #111c44;
        }

        body { 
            background-color: var(--bg-color); 
            color: var(--text-color);
            transition: all 0.3s ease;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .header-gradient {
            background: linear-gradient(135deg, var(--accent-color) 0%, #b19fff 100%);
            height: 220px;
            border-radius: 0 0 50px 50px;
        }

        .profile-card {
            background: var(--card-bg);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.1);
            margin-top: -120px;
            padding: 40px;
        }

        .avatar-wrapper {
            position: relative;
            width: 130px; height: 130px;
            margin: 0 auto 20px;
        }

        .avatar-main {
            width: 100%; height: 100%;
            background: var(--accent-color);
            color: white;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 4rem; font-weight: 800;
            border: 8px solid var(--card-bg);
            box-shadow: 0 15px 30px rgba(67, 24, 255, 0.3);
        }

        .theme-switch {
            cursor: pointer;
            font-size: 1.5rem;
            color: white;
        }

        .form-label { font-size: 0.8rem; font-weight: 700; color: #A3AED0; margin-bottom: 8px; }

        .custom-input {
            background: var(--input-bg) !important;
            border: 1px solid rgba(0,0,0,0.05) !important;
            color: var(--text-color) !important;
            border-radius: 15px;
            padding: 12px 15px;
            font-weight: 600;
        }

        .stat-box {
            background: var(--input-bg);
            border-radius: 20px;
            padding: 15px;
            text-align: center;
            border: 1px solid rgba(0,0,0,0.02);
        }

        .btn-update {
            background: var(--accent-color);
            border: none; color: white;
            border-radius: 15px;
            padding: 15px 40px;
            font-weight: 700;
            transition: 0.4s;
        }

        .btn-update:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(117, 81, 255, 0.4);
            opacity: 0.9;
        }
    </style>
</head>
<body>

<div class="header-gradient">
    <div class="container pt-4 d-flex justify-content-between align-items-center">
        <a href="complaint.php" class="text-white text-decoration-none fw-bold"><i class="fas fa-arrow-left me-2"></i> Dashboard</a>
        <div class="theme-switch" id="themeToggle">
            <i class="fas fa-moon"></i>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="profile-card">
                <div class="text-center mb-5">
                    <div class="avatar-wrapper">
                        <div class="avatar-main"><?php echo strtoupper(substr($student['name'], 0, 1)); ?></div>
                    </div>
                    <h2 class="fw-bold mb-0"><?php echo $student['name']; ?></h2>
                    <p class="text-muted"><i class="fas fa-map-marker-alt me-1"></i> Indore Campus Student</p>
                </div>

                <?php if(isset($_GET['success'])) echo "<div class='alert alert-success border-0 rounded-4 text-center py-3'><i class='fas fa-magic me-2'></i> Profile magically updated!</div>"; ?>

                <form action="" method="POST">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-uppercase"><i class="fas fa-user me-2"></i>Full Name</label>
                            <input type="text" name="name" class="custom-input form-control" value="<?php echo $student['name']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-uppercase"><i class="fas fa-envelope me-2"></i>Email ID</label>
                            <input type="email" class="custom-input form-control" value="<?php echo $student['email']; ?>" readonly>
                        </div>

                        <div class="col-12 mt-4">
                            <div class="row g-3">
                                <div class="col-md-3 col-6">
                                    <div class="stat-box">
                                        <div class="info-label small fw-bold text-muted">COURSE</div>
                                    <div class="fw-bold"><?php echo $student['course']; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="stat-box">
                                        <div class="info-label small fw-bold text-muted">BRANCH</div>
                                        <div class="fw-bold"><?php echo $student['branch']; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="stat-box">
                                        <div class="info-label small fw-bold text-muted">YEAR</div>
                                        <div class="fw-bold"><?php echo $student['year']; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="stat-box">
                                        <div class="info-label small fw-bold text-muted">SECTION</div>
                                        <div class="fw-bold"><?php echo $student['section']; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 text-center mt-5">
                            <button type="submit" name="update_profile" class="btn btn-update w-100 py-3 shadow-lg">
                                SAVE CHANGES & REFRESH
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <p class="text-center mt-4 text-muted small">
                Student Database ID: <span class="fw-bold">CV-<?php echo $student['id']; ?></span>
            </p>
        </div>
    </div>
</div>

<script>
    const themeToggle = document.getElementById('themeToggle');
    const body = document.body;
    const icon = themeToggle.querySelector('i');

    themeToggle.addEventListener('click', () => {
        if (body.getAttribute('data-theme') === 'dark') {
            body.removeAttribute('data-theme');
            icon.classList.replace('fa-sun', 'fa-moon');
            localStorage.setItem('theme', 'light');
        } else {
            body.setAttribute('data-theme', 'dark');
            icon.classList.replace('fa-moon', 'fa-sun');
            localStorage.setItem('theme', 'dark');
        }
    });

    // Check saved theme
    if (localStorage.getItem('theme') === 'dark') {
        body.setAttribute('data-theme', 'dark');
        icon.classList.replace('fa-moon', 'fa-sun');
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>