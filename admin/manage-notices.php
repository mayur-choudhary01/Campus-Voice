<?php
session_start();
include('../database/db.php');

// Security Check
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

// 1. Notice Add Karna (Fixed with 'description' column)
if (isset($_POST['add_notice'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $desc = mysqli_real_escape_string($conn, $_POST['content']); // Form field ka naam 'content' hi rehne diya hai
    $date = date('Y-m-d H:i:s');

    // Query Updated: 'content' ki jagah 'description' use kiya hai
    $insert_query = "INSERT INTO notices (title, category, description, date) VALUES ('$title', '$category', '$desc', '$date')";
    
    if (mysqli_query($conn, $insert_query)) {
        header("Location: manage-notices.php?success=1");
        exit();
    } else {
        $error_msg = "Database Error: " . mysqli_error($conn);
    }
}

// 2. Notice Delete Karna
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM notices WHERE id = $id");
    header("Location: manage-notices.php?deleted=1");
    exit();
}

// 3. Fetch Notices
$notices_res = mysqli_query($conn, "SELECT * FROM notices ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcements | CampusVoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f4f7fe; color: #2b3674; }
        .top-nav { background: linear-gradient(90deg, #4318FF 0%, #707EAE 100%); padding: 15px 0; margin-bottom: 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .glass-card { background: white; border: none; border-radius: 20px; box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.05); padding: 25px; margin-bottom: 20px; }
        .form-control, .form-select { border-radius: 12px; padding: 12px; border: 1px solid #E0E5F2; background: #f8fbff; }
        .btn-primary { background: #4318FF; border: none; border-radius: 12px; padding: 12px; font-weight: 600; transition: 0.3s; }
        .btn-primary:hover { background: #3311cc; transform: translateY(-2px); }
        .notice-card { border-left: 5px solid #4318FF; transition: 0.3s; }
        .notice-card:hover { transform: translateY(-5px); }
        .cat-badge { font-size: 0.75rem; padding: 5px 12px; border-radius: 20px; font-weight: 600; }
        .bg-urgent { background: #FFE5E5; color: #FF0000; }
        .bg-academic { background: #E5F0FF; color: #4318FF; }
        .bg-event { background: #F0FDF4; color: #15803D; }
        .back-link { color: white; text-decoration: none; font-weight: 600; opacity: 0.8; transition: 0.3s; }
        .back-link:hover { opacity: 1; color: white; }
    </style>
</head>
<body>

    <nav class="top-nav">
        <div class="container d-flex justify-content-between align-items-center">
            <h4 class="fw-bold text-white m-0"><i class="fas fa-university me-2"></i>CampusVoice</h4>
            <a href="admin-dashboard.php" class="back-link"><i class="fas fa-arrow-left me-2"></i>Back to Home</a>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Manage Announcements 📢</h2>
            <div class="text-end">
                <span class="text-muted small d-block"><?php echo date('D, d M Y'); ?></span>
                <span class="badge bg-white text-primary border rounded-pill px-3 py-2 shadow-sm">Indore Campus</span>
            </div>
        </div>

        <?php if(isset($_GET['success'])) echo "<div class='alert alert-success rounded-4 border-0 shadow-sm'>Notice published successfully!</div>"; ?>
        <?php if(isset($error_msg)) echo "<div class='alert alert-danger rounded-4 border-0 shadow-sm'>$error_msg</div>"; ?>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="glass-card sticky-top" style="top: 20px;">
                    <h5 class="fw-bold mb-4 text-primary">Post New Notice</h5>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Title</label>
                            <input type="text" name="title" class="form-control shadow-sm" placeholder="e.g. Exam Schedule" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Category</label>
                            <select name="category" class="form-select shadow-sm">
                                <option value="Academic">Academic</option>
                                <option value="Urgent">Urgent Alert</option>
                                <option value="Event">Event / Workshop</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Detailed Message</label>
                            <textarea name="content" class="form-control shadow-sm" rows="6" placeholder="Write full notice here..." required></textarea>
                        </div>
                        <button type="submit" name="add_notice" class="btn btn-primary w-100 shadow-sm">
                            <i class="fas fa-paper-plane me-2"></i> Publish Notice
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                    <h5 class="fw-bold m-0">Live Board</h5>
                    <span class="small text-muted">Total: <?php echo mysqli_num_rows($notices_res); ?> Notices</span>
                </div>
                
                <?php while($row = mysqli_fetch_assoc($notices_res)) { 
                    $cat_class = 'bg-academic';
                    if($row['category'] == 'Urgent') $cat_class = 'bg-urgent';
                    if($row['category'] == 'Event') $cat_class = 'bg-event';
                ?>
                <div class="glass-card notice-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <span class="cat-badge <?php echo $cat_class; ?> mb-2 d-inline-block">
                                <?php echo $row['category']; ?>
                            </span>
                            <h5 class="fw-bold mb-1" style="color: #1B2559;"><?php echo $row['title']; ?></h5>
                            <p class="text-muted small mb-3" style="line-height: 1.6;">
                                <?php echo isset($row['description']) ? nl2br($row['description']) : 'No details provided'; ?>
                            </p>
                            <div class="d-flex align-items-center text-muted small">
                                <i class="far fa-calendar-alt me-2 text-primary"></i> <?php echo date('d M, Y', strtotime($row['date'])); ?>
                            </div>
                        </div>
                        <a href="manage-notices.php?delete_id=<?php echo $row['id']; ?>" 
                           onclick="return confirm('Are you sure?')" 
                           class="btn btn-light btn-sm rounded-circle text-danger p-2 shadow-sm ms-3" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
                <?php } ?>

                <?php if(mysqli_num_rows($notices_res) == 0) : ?>
                    <div class="text-center py-5 glass-card">
                        <i class="fas fa-bullhorn fa-3x text-light mb-3"></i>
                        <p class="text-muted">Abhi koi notice active nahi hai.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>