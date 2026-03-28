<?php
session_start();
include('../database/db.php');


if(!isset($_SESSION['admin_logged_in'])){ header("Location: admin-login.php"); exit(); }



if(isset($_POST['update_hero'])){
    // $_POST ke andar wahi naam likho jo input tag ke 'name' attribute mein hain
    $badge = mysqli_real_escape_string($conn, $_POST['badge_text']);
    $t1 = mysqli_real_escape_string($conn, $_POST['main_title_part1']); // Line 12 fix
    $t2 = mysqli_real_escape_string($conn, $_POST['main_title_blue']);
    $desc = mysqli_real_escape_string($conn, $_POST['hero_description']); // Line 13 fix

    $sql = "UPDATE hero_settings SET 
            badge_text='$badge', 
            main_title_part1='$t1', 
            main_title_blue='$t2', 
            hero_description='$desc' 
            WHERE id=1";
            
    if(mysqli_query($conn, $sql)){
        $msg = "Hero Section Updated Successfully!";
    }
}

// 2. Analytics Update
if(isset($_POST['update_analytics'])){
    $total = $_POST['total_complaints'];
    $resolved = $_POST['resolved'];
    $pending = $_POST['in_progress'];
    $rate = $_POST['satisfaction_rate'];
    mysqli_query($conn, "UPDATE analytics SET total_complaints='$total', resolved='$resolved', in_progress='$pending', satisfaction_rate='$rate' WHERE id=1");
    $msg = "Analytics Updated!";
}

// 3. Footer Update
if(isset($_POST['update_footer'])){
    $addr = mysqli_real_escape_string($conn, $_POST['address']);
    $insta = mysqli_real_escape_string($conn, $_POST['insta_link']);
    $copy = mysqli_real_escape_string($conn, $_POST['copyright_text']);
    mysqli_query($conn, "UPDATE footer_settings SET address='$addr', insta_link='$insta', copyright_text='$copy' WHERE id=1");
    $msg = "Footer Settings Updated!";
}

// Hero Fetch
$hero = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM hero_settings WHERE id=1"));

// Footer Fetch
$footer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM footer_settings WHERE id=1"));

// Analytics Fetch
$stats = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM analytics WHERE id=1"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Site Settings | CampusVoice Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .nav-pills .nav-link.active { background-color: #764ba2; }
        .card { border: none; border-radius: 12px; }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="fas fa-tools me-2"></i> Website Master Settings</h2>
    
    <?php if(isset($msg)) echo "<div class='alert alert-success alert-dismissible fade show'>$msg <button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>"; ?>

    <div class="row">
        <div class="col-md-3">
            <div class="nav flex-column nav-pills shadow-sm bg-white p-3 rounded" id="v-pills-tab" role="tablist">
                <a href="admin-dashboard.php" class="nav-link mb-2 text-dark"><i class="fas fa-home me-2"></i> Dashboard</a>
                <button class="nav-link active mb-2" data-bs-toggle="pill" data-bs-target="#hero-tab">Hero Section</button>
                <button class="nav-link mb-2" data-bs-toggle="pill" data-bs-target="#stats-tab">Analytics</button>
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#footer-tab">Footer Details</button>
            </div>
        </div>

        <div class="col-md-9">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="hero-tab">
    <form action="" method="POST" class="card p-4 shadow-sm">
        <h5 class="fw-bold mb-3 text-primary">Hero Section Settings</h5>
        
        <div class="row g-3">
            <div class="col-md-12 mb-2">
                <label class="small fw-bold">Badge Text (e.g. Welcome Back)</label>
                <input type="text" name="badge_text" class="form-control" value="<?php echo $hero['badge_text']; ?>">
            </div>
            
            <div class="col-md-6 mb-2">
                <label class="small fw-bold">Title Part 1 (Black Text)</label>
                <input type="text" name="main_title_part1" class="form-control" value="<?php echo $hero['main_title_part1']; ?>">
            </div>
            
            <div class="col-md-6 mb-2">
                <label class="small fw-bold">Title Part 2 (Blue Text)</label>
                <input type="text" name="main_title_blue" class="form-control" value="<?php echo $hero['main_title_blue']; ?>">
            </div>

            <div class="col-12 mb-3">
                <label class="small fw-bold">Hero Description</label>
                <textarea name="hero_description" class="form-control" rows="3"><?php echo $hero['hero_description']; ?></textarea>
            </div>
        </div>

        <button type="submit" name="update_hero" class="btn btn-primary">Update Hero Section</button>
    </form>
</div>

                <div class="tab-pane fade" id="footer-tab">
                    <form action="" method="POST" class="card p-4 shadow-sm">
                        <h5 class="fw-bold mb-3">Footer & Contact</h5>
                        <div class="mb-3">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control" value="<?php echo $footer['address']; ?>">
                        </div>
                        <div class="mb-3">
                            <label>Instagram Link</label>
                            <input type="text" name="insta_link" class="form-control" value="<?php echo $footer['insta_link']; ?>">
                        </div>
                        <div class="mb-3">
                            <label>Copyright Text</label>
                            <input type="text" name="copyright_text" class="form-control" value="<?php echo $footer['copyright_text']; ?>">
                        </div>
                        <button type="submit" name="update_footer" class="btn btn-primary">Save Footer</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>