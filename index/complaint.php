<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['student_id'])) {
    header("Location: index/login1.php");
    exit();
}

// connect database
include("../database/db.php");

$s_id = $_SESSION['student_id'];
$student_query = "SELECT * FROM students WHERE id = '$s_id'"; 
$student_res = mysqli_query($conn, $student_query);
$student = mysqli_fetch_assoc($student_res);


$hero_query = "SELECT * FROM hero_settings WHERE id = 1";
$hero_res = mysqli_query($conn, $hero_query);
$hero = mysqli_fetch_assoc($hero_res);

// live notice data 
$notice_query = "SELECT * FROM notices ORDER BY id DESC";
$notice_result = mysqli_query($conn, $notice_query);

// Analytics table se data nikalna
$total_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM complaints");
$total_row = mysqli_fetch_assoc($total_res);
$total_count = $total_row['total'];

$resolved_res = mysqli_query($conn, "SELECT COUNT(*) AS res FROM complaints WHERE status = 'Resolved'");
$resolved_row = mysqli_fetch_assoc($resolved_res);
$resolved_count = $resolved_row['res'];

$progress_res = mysqli_query($conn, "SELECT COUNT(*) AS prog FROM complaints WHERE status = 'Pending'");
$progress_row = mysqli_fetch_assoc($progress_res);
$progress_count = $progress_row['prog'];

if ($total_count > 0) {
    $sat_rate = round(($resolved_count / $total_count) * 100);
} else {
    $sat_rate = 0;
}

$stats = [
    'total_complaints' => $total_count,
    'resolved' => $resolved_count,
    'in_progress' => $progress_count,
    'satisfaction_rate' => $sat_rate
];

$services_res = mysqli_query($conn, "SELECT * FROM services");

$side_notices_res = mysqli_query($conn, "SELECT * FROM notices ORDER BY date DESC LIMIT 2");

$faculty_query = "SELECT * FROM faculty WHERE role = 'HOD' ORDER BY id ASC";
$faculty_res = mysqli_query($conn, $faculty_query);

if (!$faculty_res) {
    die("Faculty Query Failed: " . mysqli_error($conn));
}

$footer_res = mysqli_query($conn, "SELECT * FROM footer_settings WHERE id = 1");
$f = mysqli_fetch_assoc($footer_res);
?>










<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusVoice | Smart College Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --brand-blue: #0ea5e9;
            --brand-orange: #f97316;
            --brand-green: #15803d;
            --dark-blue: #1e3a8a;
            --text-dark: #333333;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fafafa;
            color: var(--text-dark);
        }

        /* Navbar matching logo text and colors */
        .navbar {
            background-color: #f8f9fa;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-bottom: 2px solid #eee;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: #222 !important;
        }

        .navbar-brand span.cv-voice {
            color: var(--brand-blue);
        }

        /* Nav links styled with green, inspired by 'Open For All' */
        .nav-link {
            font-weight: 600;
            color: #444 !important;
        }

        .nav-link:hover {
            color: var(--brand-green) !important;
        }


        .btn-brand-blue {
            background-color: var(--brand-blue);
            color: white;
            border: none;
        }

        .btn-brand-blue:hover {
            background-color: #0784c3;
            color: white;
        }

        .btn-brand-orange-outline {
            border: 2px solid var(--brand-orange);
            color: var(--brand-orange);
            background-color: transparent;
        }

        .btn-brand-orange-outline:hover {
            background-color: var(--brand-orange);
            color: white;
        }

        .stat-card {
            border: none;
            border-left: 6px solid var(--brand-blue);
        }

        .stat-card-green {
            border-left-color: var(--brand-green);
        }

        .stat-card-orange {
            border-left-color: var(--brand-orange);
        }

        .feature-box i {
            color: var(--brand-blue);
            font-size: 2rem;
        }

        .notice-board {
            background: #fff;
            border-right: 5px solid var(--brand-orange);
            border-radius: 8px;
        }

        .notice-badge {
            background-color: var(--brand-orange);
            color: white;
        }

        .dept-card {
            border: none;
            background-color: white;
            border-radius: 12px;
        }

        .dept-icon {
            font-size: 1.8rem;
            color: var(--brand-blue);
            margin-right: 15px;
        }

        .hod-card img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid var(--brand-blue);
        }

        footer {
            background-color: #222;
            color: #ddd;
        }

        footer .cv-name {
            color: var(--brand-green);
            font-weight: bold;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#" style="margin-left: -10px;">
                <img src="/DBMSPROJECT/images/logo.png" class="me-2"
                    style=" height: 50px; width: 80px; margin-top: -8px; margin-left: -90px; background-color: transparent; position: absolute; z-index: 1000;">
                Campus<span class="cv-voice">Voice</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto gap-3 text-uppercase" style="font-size: 0.8rem; align-items: center;">
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#notices">Notice Board</a></li>
                    <li class="nav-item"><a class="nav-link" href="#analytics">Analytics</a></li>


                    <li class="nav-item"><a class="nav-link btn btn-brand-blue text-white px-4 rounded-pill fw-bold"
                            href="/DBMSPROJECT/complaint/add-complaint.php">Register Complaint</a></li>
                    <?php if (isset($_SESSION['student_id'])):
                        // Naam ka pehla letter nikalne ke liye
                        $first_letter = strtoupper(substr($_SESSION['name'], 0, 1));
                        ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link p-0" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <div
                                    style="width: 35px; height: 35px; background: #224abe; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1rem; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                                    <?php echo $first_letter; ?>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2"
                                style="border-radius: 12px; text-transform: none;">
                                <li class="px-3 py-2 border-bottom">
                                    <span class="d-block fw-bold text-dark"><?php echo $_SESSION['name']; ?></span>
                                    <small class="text-muted"><?php echo $_SESSION['email']; ?></small>
                                </li>
                                <li><a class="dropdown-item mt-2" href="student-profile.php"><i
                                            class="fas fa-user-edit me-2"></i> Edit Profile</a></li>
                                <li><a class="dropdown-item" href="/DBMSPROJECT/complaint/my-complaints.php"><i
                                            class="fas fa-list-alt me-2"></i> My Complaints</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="logoutS.php"><i
                                            class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link btn btn-brand-orange-outline px-4 rounded-pill"
                                href="admin/admin-login.php">Admin/HOD Login</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-brand-blue text-white px-4 rounded-pill fw-bold"
                                href="student-login.php">Student Login</a></li>
                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-wrapper overflow-hidden position-relative">
        <div class="hero-shapes">
            <div class="shape s1"></div>
            <div class="shape s2"></div>
        </div>

        <div class="container position-relative z-3 text-center">
            <div class="welcome-badge mb-4 animate__animated animate__fadeInDown">
                <span class="badge rounded-pill bg-glass p-2 px-3">
                    <i class="fas fa-sparkles text-warning me-2"></i>
                    <?php echo $hero['badge_text']; ?>, <span
                        class="text-gradient fw-bold"><?php echo $_SESSION['name']; ?>!</span>
                </span>
            </div>

            <h1 class="display-2 fw-black main-title mb-4">
                <?php echo $hero['main_title_part1']; ?> <span id="typed-text" class="text-orange"></span><br>
                <span class="text-blue"><?php echo $hero['main_title_blue']; ?></span>
            </h1>

            <p class="lead mx-auto mb-5 hero-desc opacity-0">
                <?php echo $hero['hero_description']; ?>
            </p>

            <div class="d-flex justify-content-center gap-4 flex-wrap">
                <a href="#features" class="btn btn-primary-glass btn-lg group">
                    Explore Features <i class="fas fa-arrow-right ms-2 group-hover"></i>
                </a>
                <a href="#notices" class="btn btn-outline-glass btn-lg">
                    <i class="fas fa-bell me-2"></i> Live Notices
                </a>
            </div>

            <div class="row mt-5 pt-5 g-3 justify-content-center">
                <div class="col-6 col-md-3">
                    <div class="stat-mini-card shadow-lg">
                        <span class="d-block fw-bold fs-4"><?php echo $hero['stat1_val']; ?></span>
                        <span class="small text-muted"><?php echo $hero['stat1_label']; ?></span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-mini-card shadow-lg">
                        <span class="d-block fw-bold fs-4"><?php echo $hero['stat2_val']; ?></span>
                        <span class="small text-muted"><?php echo $hero['stat2_label']; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <section id="notices" class="py-5 bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark m-0">Latest Notices</h2>
                <a href="all_notices.php" class="btn btn-primary btn-sm rounded-pill px-4">View All</a>
            </div>

            <div class="swiper noticeSwiper">
                <div class="swiper-wrapper">
                    <?php
                    mysqli_data_seek($notice_result, 0);
                    while ($row = mysqli_fetch_assoc($notice_result)) {
                        $category = strtolower($row['category']);

                         // Bootstrap Contextual Colors
                         $badge_bg = "bg-primary";
                         if ($category == 'urgent')
                             $badge_bg = "bg-danger";
                         else if ($category == 'event')
                             $badge_bg = "bg-success";
                         else if ($category == 'update')
                            $badge_bg = "bg-info text-dark";

                        $img = !empty($row['image']) ? "uploads/" . $row['image'] : "https://via.placeholder.com/150";
                        ?>
                        <div class="swiper-slide h-auto">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-2" style="height: 160px;">
                                <div class="row g-0 h-100">
                                    <div class="col-4">
                                        <img src="<?php echo $img; ?>" class="img-fluid h-100 w-100"
                                            style="object-fit: cover;" alt="Notice">
                                    </div>
                                    <div class="col-8">
                                        <div class="card-body d-flex flex-column justify-content-between h-100 py-3">
                                            <div>
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="badge rounded-pill <?php echo $badge_bg; ?>"
                                                        style="font-size: 0.7rem;">
                                                        <?php echo strtoupper($category); ?>
                                                    </span>
                                                    <small class="text-muted" style="font-size: 0.75rem;">
                                                        <?php echo date('d M', strtotime($row['date'])); ?>
                                                    </small>
                                                </div>
                                                <h6 class="card-title fw-bold text-dark mb-1" style="font-size: 1rem;">
                                                    <?php echo substr($row['title'], 0, 40); ?>...
                                                </h6>
                                                <p class="card-text text-muted small mb-0"
                                                    style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.3;">
                                                    <?php echo $row['description']; ?>
                                                </p>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <div class="text-danger small fw-bold" style="cursor: pointer;"
                                                    onclick="toggleLike(this)">
                                                    <i class="far fa-heart"></i>
                                                    <span><?php echo $row['likes'] ?? 0; ?></span>
                                                </div>
                                                <a href="details.php?id=<?php echo $row['id']; ?>"
                                                    class="btn btn-link btn-sm p-0 text-decoration-none fw-bold text-primary"
                                                    style="font-size: 0.8rem;">
                                                    READ MORE <i class="fas fa-chevron-right ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="swiper-pagination mt-4 position-relative"></div>
            </div>
        </div>
    </section>
    <style>
        .stat-card {
            border: none;
            border-radius: 24px;
            background: #ffffff;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100px;
            height: 100px;
            background: rgba(0, 0, 0, 0.03);
            border-radius: 50%;
            z-index: -1;
            transition: 0.5s;
        }

        .stat-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06) !important;
        }

        .stat-card:hover::after {
            transform: scale(3);
        }

        /* Icon Backgrounds */
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 20px;
        }

        .bg-soft-blue {
            background: #e0f2fe;
            color: #0ea5e9;
        }

        .bg-soft-green {
            background: #dcfce7;
            color: #22c55e;
        }

        .bg-soft-orange {
            background: #ffedd5;
            color: #f97316;
        }

        .bg-soft-purple {
            background: #f3e8ff;
            color: #a855f7;
        }

        /* Counter Animation Style */
        .stat-number {
            font-size: 2.2rem;
            letter-spacing: -1px;
        }

        /*  */
        .noticeSwiper {
            padding: 20px 10px;
        }

        .notice-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            height: 250px;
            position: relative;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid #e3d8d8;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .notice-card:hover {
            transform: scale(1.03);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
        }

        .notice-card.urgent {
            border-top: 5px solid #ef4444;
        }

        .notice-card.update {
            border-top: 5px solid var(--brand-blue);
        }

        .notice-card.event {
            border-top: 5px solid var(--brand-green);
        }

        .card-tag {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 50px;
        }

        .urgent .card-tag {
            background: #fee2e2;
            color: #ef4444;
        }

        .update .card-tag {
            background: #e0f2fe;
            color: var(--brand-blue);
        }

        .event .card-tag {
            background: #dcfce7;
            color: var(--brand-green);
        }

        .notice-content i {
            font-size: 2rem;
            color: #334155;
        }

        .urgent i {
            color: #ef4444;
        }

        .update i {
            color: var(--brand-blue);
        }

        .notice-content h5 {
            font-weight: 800;
            margin-bottom: 10px;
            color: #1e293b;
        }

        .notice-content p {
            font-size: 0.9rem;
            color: #64748b;
            line-height: 1.5;
        }

        .notice-date {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 15px;
            display: block;
            font-style: italic;
        }

        /* Swiper Dots Color */
        .swiper-pagination-bullet-active {
            background: var(--brand-orange) !important;
        }





        /* live notice */

        /* Swiper Slide spacing */
        .swiper-slide {
            padding: 10px;
        }

        /* Heart Animation */
        .text-danger i.fas {
            animation: heartBeat 0.3s ease-in-out;
        }

        @keyframes heartBeat {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.3);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Swiper Pagination Color */
        .swiper-pagination-bullet-active {
            background-color: #0d6efd !important;
            /* Bootstrap Primary Blue */
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".noticeSwiper", {
            slidesPerView: 1,
            spaceBetween: 20,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
            },
        });

        // live notice
        // Like functionality with local storage (taaki user ek hi baar like kare)
        function likeNotice(id, btn) {
            let countSpan = btn.querySelector('span');
            let currentCount = parseInt(countSpan.innerText);

            // Simple UI feedback
            btn.classList.toggle('bg-danger');
            btn.classList.toggle('text-white');

            if (btn.classList.contains('text-white')) {
                countSpan.innerText = currentCount + 1;
                // AJAX yahan aayega: update_likes.php?id=id&action=plus
            } else {
                countSpan.innerText = currentCount - 1;
                // AJAX yahan aayega: update_likes.php?id=id&action=minus
            }
        }

        // Swiper with Autoplay & Centered effect
        var swiper = new Swiper(".noticeSwiper", {
            slidesPerView: 1,
            spaceBetween: 30,
            autoplay: { delay: 4000 },
            pagination: {
                el: ".swiper-pagination",
                dynamicBullets: true,
                clickable: true
            },
            breakpoints: {
                768: { slidesPerView: 2 },
                1200: { slidesPerView: 3 }
            }
        });
    </script>
    <style>
        :root {
            --brand-blue: #0ea5e9;
            --brand-orange: #f97316;
            --brand-green: #15803d;
            --dark-bg: #0f172a;
        }

        .hero-wrapper {
            min-height: 90vh;
            display: flex;
            align-items: center;
            background: radial-gradient(circle at top right, #1e293b, #0f172a);
            color: white;
        }

        /* Background Shapes */
        .hero-shapes .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 1;
            opacity: 0.4;
        }

        .s1 {
            width: 400px;
            height: 400px;
            background: var(--brand-blue);
            top: -100px;
            right: -50px;
        }

        .s2 {
            width: 300px;
            height: 300px;
            background: var(--brand-orange);
            bottom: -50px;
            left: -50px;
        }

        .fw-black {
            font-weight: 900;
        }

        .bg-glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .text-gradient {
            background: linear-gradient(to right, #0ea5e9, #22d3ee);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .text-orange {
            color: var(--brand-orange);
        }

        .text-blue {
            color: var(--brand-blue);
        }

        /* Custom Buttons */
        .btn-primary-glass {
            background: var(--brand-blue);
            color: white;
            border-radius: 15px;
            padding: 15px 35px;
            border: none;
            transition: 0.4s;
        }

        .btn-primary-glass:hover {
            background: #0284c7;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(14, 165, 233, 0.4);
            color: white;
        }

        .btn-outline-glass {
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 15px;
            padding: 15px 35px;
            backdrop-filter: blur(5px);
            transition: 0.4s;
        }

        .btn-outline-glass:hover {
            background: white;
            color: var(--dark-bg);
        }

        .stat-mini-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }

        /* Animations */
        .hero-desc {
            animation: fadeInUp 1s forwards 0.5s;
            max-width: 600px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /*  */
        :root {
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.1);
            --accent-blue: #38bdf8;
            --accent-orange: #fb923c;
        }

        /* Feature Box Upgrade */
        .feature-box {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 24px !important;
            transition: all 0.3s ease;
            position: relative;
        }

        .feature-box:hover {
            border-color: var(--accent-blue);
            background: #f8fafc;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        }

        /* Pulse Animation for Notification */
        .pulse-icon {
            position: relative;
            display: inline-block;
        }

        .pulse-icon::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 10px;
            height: 10px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid white;
            animation: pulse-red 2s infinite;
        }

         @keyframes pulse-red {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            }

            70% {
                transform: scale(1);
                box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
            }

            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
            } 
        } 

        /* Quick Action Buttons */
        .btn-action {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            color: #475569;
            transition: 0.3s;
        }

        .btn-action:hover {
            background: var(--accent-blue);
            color: white;
            transform: rotate(-10deg);
        }
    </style>

    <script src="https://unpkg.com/typed.js@2.0.16/dist/typed.umd.js"></script>
    <script>
        // Typing Effect
        var typed = new Typed('#typed-text', {
            strings: ['Matters.', 'is Power.', 'is Action.'],
            typeSpeed: 60,
            backSpeed: 40,
            loop: true,
            showCursor: false
        });

        // Parallax Effect on Mouse Move
        document.addEventListener("mousemove", function (e) {
            const shapes = document.querySelectorAll(".shape");
            const x = (window.innerWidth - e.pageX * 2) / 50;
            const y = (window.innerHeight - e.pageY * 2) / 50;

            shapes.forEach(shape => {
                shape.style.transform = `translateX(${x}px) translateY(${y}px)`;
            });
        });
    </script>
    <section id="analytics" class="py-5 mb-5">
        <div class="container">
            <div class="row g-4">

                <div class="col-md-3">
                    <div class="card stat-card p-4 shadow-sm border-0">
                        <div class="stat-icon bg-soft-blue">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <h2 class="fw-black stat-number text-dark mb-1">
                            <span class="counter"><?php echo $stats['total_complaints']; ?></span>+
                        </h2>
                        <p class="text-muted small fw-bold text-uppercase">Total Requests</p>
                        <div class="mt-3 progress" style="height: 4px; background: #f1f5f9;">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card stat-card p-4 shadow-sm border-0">
                        <div class="stat-icon bg-soft-green">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <h2 class="fw-black stat-number text-success mb-1">
                            <span class="counter"><?php echo $stats['resolved']; ?></span>+
                        </h2>
                        <p class="text-muted small fw-bold text-uppercase">Solved Cases</p>
                        <div class="mt-3 progress" style="height: 4px; background: #f1f5f9;">
                            <?php
                            $res_perc = ($stats['total_complaints'] > 0) ? ($stats['resolved'] / $stats['total_complaints'] * 100) : 0;
                            ?>
                            <div class="progress-bar bg-success" style="width: <?php echo $res_perc; ?>%"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card stat-card p-4 shadow-sm border-0">
                        <div class="stat-icon bg-soft-orange">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                        <h2 class="fw-black stat-number text-warning mb-1">
                            <span class="counter"><?php echo $stats['in_progress']; ?></span>
                        </h2>
                        <p class="text-muted small fw-bold text-uppercase">Active Processing</p>
                        <div class="mt-3 progress" style="height: 4px; background: #f1f5f9;">
                            <div class="progress-bar bg-warning" style="width: 45%"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card stat-card p-4 shadow-sm border-0">
                        <div class="stat-icon bg-soft-purple">
                            <i class="fas fa-smile-beam"></i>
                        </div>
                        <h2 class="fw-black stat-number text-dark mb-1">
                            <span class="counter"><?php echo $stats['satisfaction_rate']; ?></span>%
                        </h2>
                        <p class="text-muted small fw-bold text-uppercase">Happy Students</p>
                        <div class="mt-3 d-flex align-items-center gap-2">
                            <i class="fas fa-star text-warning" style="font-size: 10px;"></i>
                            <i class="fas fa-star text-warning" style="font-size: 10px;"></i>
                            <i class="fas fa-star text-warning" style="font-size: 10px;"></i>
                            <i class="fas fa-star text-warning" style="font-size: 10px;"></i>
                            <i class="fas fa-star-half-alt text-warning" style="font-size: 10px;"></i>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <div class="container py-5">
        <div class="row g-5">

            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <h2 class="fw-bold m-0" style="color: #1e293b;">Campus Services</h2>
                        <p class="text-muted small">Manage your academic requests easily</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="profile.php" class="btn-action shadow-sm" title="Profile"><i
                                class="fas fa-user"></i></a>
                        <a href="settings.php" class="btn-action shadow-sm" title="Settings"><i
                                class="fas fa-cog"></i></a>
                        <div class="pulse-icon">
                            <a href="notifications.php" class="btn-action shadow-sm" title="Alerts"><i
                                    class="fas fa-bell"></i></a>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <?php
                    mysqli_data_seek($services_res, 0);
                    while ($service = mysqli_fetch_assoc($services_res)) { ?>
                        <div class="col-md-6">
                            <div class="feature-box p-4 shadow-sm h-100">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-circle bg-light p-3 rounded-3 me-3" style="color: var(--accent-blue);">
                                        <i class="fas <?php echo $service['icon']; ?> fa-lg"></i>
                                    </div>
                                    <h5 class="fw-bold m-0" style="color: #1e293b;"><?php echo $service['title']; ?></h5>
                                </div>
                                <p class="text-muted small mb-3"><?php echo $service['description']; ?></p>
                                <a href="service-detail.php?id=<?php echo $service['id']; ?>"
                                    class="text-decoration-none small fw-bold text-primary">
                                    Open Service <i class="fas fa-arrow-right ms-1" style="font-size: 10px;"></i>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="p-4" style="background: #1e293b;">
                        <h5 class="text-white fw-bold m-0"><i class="fas fa-bullhorn me-2 text-warning"></i>Important
                            Notices</h5>
                    </div>
                    <div class="p-3 bg-white" style="max-height: 550px; overflow-y: auto;">
                        <?php
                        mysqli_data_seek($side_notices_res, 0);
                        while ($side_notice = mysqli_fetch_assoc($side_notices_res)) {
                            $is_urgent = ($side_notice['category'] == 'urgent');
                            ?>
                            <div class="p-3 mb-3 border-bottom hover-bg-light transition rounded-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span
                                        class="badge rounded-pill <?php echo $is_urgent ? 'bg-danger' : 'bg-info'; ?> mb-2"
                                        style="font-size: 9px;">
                                        <?php echo strtoupper($side_notice['category']); ?>
                                    </span>
                                    <small class="text-muted"
                                        style="font-size: 11px;"><?php echo date('d M', strtotime($side_notice['date'])); ?></small>
                                </div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">
                                    <?php echo $side_notice['title']; ?></h6>
                                <p class="text-muted mb-0" style="font-size: 0.85rem; line-height: 1.4;">
                                    <?php echo substr($side_notice['description'], 0, 90); ?>...</p>
                            </div>
                        <?php } ?>
                        <a href="all-notices.php"
                            class="btn btn-outline-primary w-100 mt-3 rounded-pill fw-bold btn-sm">View Archive</a>
                    </div>
                </div>

                <div class="card mt-4 border-0 rounded-4 bg-primary text-white p-4">
                    <h6 class="fw-bold mb-2">Need Help?</h6>
                    <p class="small opacity-75">Facing issues with the portal? Contact the FCA Department directly.</p>
                    <a href="contact.php" class="btn btn-light btn-sm rounded-pill fw-bold">Contact Support</a>
                </div>
            </div>

        </div>
    </div>
    <section id="departments" class="bg-light py-5">
        <div class="container text-center">
            <h2 class="fw-bold mb-5">HOD Profiles & Departments</h2>
            <div class="row g-4">

                <?php while ($faculty = mysqli_fetch_assoc($faculty_res)) { ?>
                    <div class="col-md-4">
                        <div
                            class="hod-card bg-white p-4 rounded shadow-sm dept-card d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i
                                    class="fas <?php echo $faculty['icon_class']; ?> dept-icon <?php echo $faculty['icon_color']; ?>"></i>
                                <div class="text-start ms-3">
                                    <h6 class="fw-bold mb-0"><?php echo $faculty['name']; ?></h6>
                                    <p class="text-muted small mb-0"><?php echo $faculty['designation']; ?></p>
                                </div>
                            </div>
                            <img src="/DBMSPROJECT/images/<?php echo $faculty['image']; ?>" alt="HOD"
                                style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>
    </section>


    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>About CampusVoice</h5>
                    <p class="small text-muted"><?php echo $f['about_text']; ?></p>
                </div>
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <ul class="list-unstyled small">
                        <li><i class="fas fa-map-marker-alt me-2"></i><?php echo $f['address']; ?></li>
                        <li><i class="fas fa-envelope me-2"></i><?php echo $f['email']; ?></li>
                    </ul>
                </div>
                <div class="col-md-4 text-center">
                    <h5>Follow Us</h5>
                    <a href="<?php echo $f['insta_link']; ?>" class="text-white me-3"><i
                            class="fab fa-instagram"></i></a>
                    <a href="<?php echo $f['linkedin_link']; ?>" class="text-white"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            <hr>
            <p class="text-center small mb-0">&copy; <?php echo date('Y'); ?> <?php echo $f['copyright_text']; ?></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>