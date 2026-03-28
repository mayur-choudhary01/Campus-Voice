<?php
session_start();
include("../database/db.php");

if (isset($_GET['delete_id'])) {
    $del_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM faculty WHERE id = '$del_id'";
    if (mysqli_query($conn, $delete_query)) {
        header("Location: manage_faculty.php?msg=Faculty Deleted Successfully");
    }
}

$query = "SELECT * FROM faculty ORDER BY department ASC, role DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Manage Faculty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8fafc; font-family: 'Inter', sans-serif; }
        .admin-card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .table thead { background: #f1f5f9; color: #475569; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; }
        .role-badge { padding: 4px 10px; border-radius: 50px; font-size: 11px; font-weight: 600; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">Manage Faculty</h2>
            <p class="text-muted">Add, Edit or Remove Faculty Members & HODs</p>
        </div>
        <a href="admin-add-faculty.php" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-plus me-2"></i> Add New Faculty
        </a>
    </div>

    <?php if(isset($_GET['msg'])) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_GET['msg']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

    <div class="card admin-card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Faculty</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Assigned Class</th>
                        <th>Email</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <img src="../images/<?php echo $row['image']; ?>" class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                                <div>
                                    <h6 class="mb-0 fw-bold"><?php echo $row['name']; ?></h6>
                                    <small class="text-muted"><?php echo $row['designation']; ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php 
                                $role = $row['role'];
                                $bg = ($role == 'HOD') ? 'bg-danger-subtle text-danger' : (($role == 'Coordinator') ? 'bg-warning-subtle text-warning' : 'bg-info-subtle text-info');
                            ?>
                            <span class="role-badge <?php echo $bg; ?>"><?php echo $role; ?></span>
                        </td>
                        <td class="fw-semibold text-dark"><?php echo $row['department']; ?></td>
                        <td><?php echo $row['assigned_class'] ? $row['assigned_class'] : '<span class="text-muted small">N/A</span>'; ?></td>
                        <td class="text-muted small"><?php echo $row['email']; ?></td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="edit-faculty.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="manage-faculty.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bhai, pakka delete karna hai?')" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>