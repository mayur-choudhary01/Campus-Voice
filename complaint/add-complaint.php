<?php
session_start();
include("../database/db.php");

// Auth Check
if (!isset($_SESSION['student_id'])) {
    header("Location: ../login1.php");
    exit();
}

if (isset($_POST['submit_issue'])) {
    $s_id = $_SESSION['student_id'];
    
    if (isset($_SESSION['student_name']) && !empty($_SESSION['student_name'])) {
        $s_name = $_SESSION['student_name'];
    } else {
        $user_res = mysqli_query($conn, "SELECT name FROM students WHERE id = '$s_id'");
        $user_row = mysqli_fetch_assoc($user_res);
        $s_name = $user_row['name'] ?? 'Student'; 
    }

    // Inputs fetching
    $dept = mysqli_real_escape_string($conn, $_POST['target_dept']);
    $course = $_POST['target_course']; 
    $year = $_POST['target_year'];     
    $section = $_POST['target_section']; 


    $class = mysqli_real_escape_string($conn, "$course $year"); 

    $priority = mysqli_real_escape_string($conn, $_POST['priority']);
    $category = mysqli_real_escape_string($conn, $_POST['category'] ?? 'General');
    $subject = mysqli_real_escape_string($conn, ucfirst($_POST['subject']));
    $desc = mysqli_real_escape_string($conn, ucfirst($_POST['description']));

    // Case ID generation
    $case_id = "CV-" . strtoupper(substr($dept, 0, 3)) . "-" . rand(1000, 9999);

    // INSERT Query
    $query = "INSERT INTO complaints (student_id, student_name, department, class, subject, category, description, priority, status) 
              VALUES ('$s_id', '$s_name', '$dept', '$class', '$subject', '$category', '$desc', '$priority', 'Pending')";

    if (mysqli_query($conn, $query)) {
        $success = true;
    } else {
        $error_msg = mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elite Support | CampusVoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap');

        body {
            background: #f8fafc;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #334155;
            overflow-x: hidden;
        }

        .main-gradient {
            background: linear-gradient(135deg, #1e40af, #7c3aed);
            padding: 60px 0 100px;
            color: white;
            border-radius: 0 0 50px 50px;
        }

        .form-container {
            margin-top: -80px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
        }

        /* Auto Capitalization Style */
        .cap-text {
            text-transform: capitalize;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            padding: 14px;
            font-size: 0.95rem;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.1);
        }

        /* Priority Selector */
        .priority-btn {
            cursor: pointer;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px;
            text-align: center;
            transition: 0.3s;
            font-size: 0.85rem;
            font-weight: 700;
            background: #fff;
        }

        .priority-input:checked+.priority-btn.low {
            background: #f0fdf4;
            border-color: #22c55e;
            color: #166534;
        }

        .priority-input:checked+.priority-btn.mid {
            background: #fffbeb;
            border-color: #f59e0b;
            color: #92400e;
        }

        .priority-input:checked+.priority-btn.high {
            background: #fef2f2;
            border-color: #ef4444;
            color: #991b1b;
        }

        .footer-full {
            background: #0f172a;
            color: #94a3b8;
            width: 100%;
            padding: 60px 0 30px;
            margin-top: 80px;
        }

        .btn-submit {
            background: linear-gradient(90deg, #1e40af, #7c3aed);
            border: none;
            border-radius: 15px;
            padding: 18px;
            font-weight: 700;
            transition: 0.4s;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(124, 58, 237, 0.4);
        }
    </style>
</head>

<body>

    <?php if (isset($success)): ?>
        <script>
            Swal.fire({
                title: 'Success!',
                text: 'Your complaint has been registered as: <?php echo $case_id; ?>',
                icon: 'success',
                confirmButtonColor: '#7c3aed'
            }).then(() => { window.location = 'my-complaints.php'; });
        </script>
    <?php endif; ?>

    <div class="main-gradient text-center">
        <div class="container">
            <h2 class="fw-bold mb-2"><i class="fas fa-headset me-2"></i>Official Support Desk</h2>
            <p class="opacity-75">Acropolis Indore - Resolution Portal for BCA/MCA Students</p>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-8">
                <div class="form-container p-4 p-md-5">
                    <form action="" method="POST">

                        <div class="section-label mb-3 fw-bold text-primary"><i class="fas fa-university me-2"></i>1.
                            DEPARTMENT & AUTHORITY</div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <select name="target_dept" class="form-select" required>
                                    <option value="FCA">Faculty of Computer Apps (FCA)</option>
                                    <option value="IMCA">Integrated MCA</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select name="target_role" class="form-select" required>
                                    <option value="Coordinator">Class Coordinator</option>
                                    <option value="HOD">Department HOD</option>
                                </select>
                            </div>
                        </div>

                        <div class="section-label mb-3 fw-bold text-primary"><i
                                class="fas fa-graduation-cap me-2"></i>2. ACADEMIC DETAILS</div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4"><select name="target_course" class="form-select" required>
                                    <option value="BCA">BCA Degree</option>
                                    <option value="MCA">MCA Degree</option>
                                </select></div>
                            <div class="col-md-4"><select name="target_year" class="form-select" required>
                                    <option value="1st Year">1st Year</option>
                                    <option value="2nd Year">2nd Year</option>
                                    <option value="3rd Year">3rd Year</option>
                                </select></div>
                            <div class="col-md-4"><select name="target_section" class="form-select" required>
                                    <option value="A">Section A</option>
                                    <option value="B">Section B</option>
                                </select></div>
                        </div>

                        <div class="section-label mb-3 fw-bold text-primary"><i
                                class="fas fa-exclamation-triangle me-2"></i>3. ISSUE DESCRIPTION</div>
                        <div class="mb-4">
                            <label class="small fw-bold mb-2">Set Urgency</label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input type="radio" name="priority" value="Low" id="p1"
                                        class="d-none priority-input" checked>
                                    <label for="p1" class="priority-btn low d-block">Routine</label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" name="priority" value="Medium" id="p2"
                                        class="d-none priority-input">
                                    <label for="p2" class="priority-btn mid d-block">Urgent</label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" name="priority" value="High" id="p3"
                                        class="d-none priority-input">
                                    <label for="p3" class="priority-btn high d-block">Critical</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <select name="category" class="form-select" required>
                                <option value="">-- Select Issue Category --</option>
                                <option value="Academic">Academic / Lectures</option>
                                <option value="Infrastructure">Infrastructure / Lab</option>
                                <option value="Behavior">Faculty/Staff Behavior</option>
                                <option value="Other">Other Miscellaneous</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <input type="text" name="subject" class="form-control cap-text"
                                placeholder="Subject (e.g., Projector not working)" required>
                        </div>
                        <div class="mb-4">
                            <textarea name="description" class="form-control cap-text" rows="5"
                                placeholder="Please describe your issue in detail..." required></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="is_anonymous" id="anon">
                            <label class="form-check-label text-danger fw-bold" for="anon">
                                Submit as Anonymous (Hide my Name & Enrollment) 🔒
                            </label>
                        </div>
                        <button type="submit" name="submit_issue"
                            class="btn btn-primary btn-submit w-100 text-white shadow-lg text-uppercase">
                            Lock Complaint <i class="fas fa-lock ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 rounded-4 p-4 shadow-sm mb-4"
                    style="border-left: 6px solid #7c3aed !important;">
                    <h5 class="fw-bold mb-4 text-primary">English Guidelines</h5>
                    <div class="d-flex mb-4">
                        <div class="me-3 text-primary h4"><i class="fas fa-keyboard"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">Clear Input</h6>
                            <p class="small text-muted mb-0">Be specific about dates and faculty names for faster
                                resolution.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-4">
                        <div class="me-3 text-primary h4"><i class="fas fa-clock"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">Turnaround Time</h6>
                            <p class="small text-muted mb-0">Most issues are verified within 24 working hours.</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="me-3 text-primary h4"><i class="fas fa-history"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">Tracking</h6>
                            <p class="small text-muted mb-0">Use your Case ID to track live updates on your dashboard.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer-full">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="text-white fw-bold mb-3">CampusVoice Acropolis</h5>
                    <p class="small">The official grievance redressal system of Faculty of Computer Applications. We
                        bridge the gap between students and management.</p>
                </div>
                <div class="col-lg-4">
                    <h5 class="text-white fw-bold mb-3">Support Features</h5>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><i class="fas fa-check-circle me-2 text-success"></i> Anonymous (Optional)
                            reporting</li>
                        <li class="mb-2"><i class="fas fa-check-circle me-2 text-success"></i> Direct HOD escalation
                        </li>
                        <li class="mb-2"><i class="fas fa-check-circle me-2 text-success"></i> Real-time status tracking
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5 class="text-white fw-bold mb-3">Contact Support</h5>
                    <p class="small mb-1"><i class="fas fa-envelope me-2"></i> helpdesk.fca@acropolis.in</p>
                    <p class="small mb-1"><i class="fas fa-phone-alt me-2"></i> +91 731-258XXXX</p>
                    <p class="small"><i class="fas fa-map-marker-alt me-2"></i> Indore, MP, India</p>
                </div>
            </div>
            <hr class="mt-5 opacity-25">
            <p class="text-center small mb-0">&copy; 2026 CampusVoice | Designed for Acropolis Institution.</p>
        </div>
    </footer>

</body>

</html>