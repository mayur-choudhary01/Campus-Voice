<?php 
// Connection file ka path (Database folder agar same directory mein hai)
include('../database/db.php'); 

// Assume session se user_id aa raha hai
$user_id = 1; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications | Campus Services</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .noti-container {
            max-width: 850px;
            margin: 50px auto;
        }

        /* Header Styling */
        .page-title {
            color: #1e293b;
            font-weight: 800;
            letter-spacing: -1px;
        }

        /* Modern Notification Card */
        .noti-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 20px;
            padding: 20px;
            display: flex;
            align-items: flex-start;
            gap: 20px;
            transition: all 0.3s ease;
            position: relative;
            margin-bottom: 15px;
        }

        .noti-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.05);
            background: #fff;
        }

        /* Unread Dot */
        .unread-dot {
            height: 10px;
            width: 10px;
            background: #6366f1;
            border-radius: 50%;
            position: absolute;
            top: 25px;
            right: 25px;
        }

        /* Icon Styling */
        .noti-icon {
            width: 55px;
            height: 55px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .bg-success-light { background: #dcfce7; color: #16a34a; }
        .bg-blue-light { background: #e0e7ff; color: #4f46e5; }
        .bg-orange-light { background: #ffedd5; color: #ea580c; }

        .noti-content h6 {
            font-weight: 700;
            color: #334155;
            margin-bottom: 4px;
        }

        .noti-text {
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .noti-time {
            font-size: 0.8rem;
            color: #94a3b8;
            font-weight: 500;
        }

        /* Action Buttons */
        .btn-clear {
            background: #fff;
            border: 1px solid #e2e8f0;
            color: #64748b;
            font-weight: 600;
            padding: 8px 20px;
            border-radius: 12px;
            transition: 0.2s;
        }

        .btn-clear:hover {
            background: #f1f5f9;
            color: #0f172a;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="noti-container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="page-title">Notifications</h1>
                <p class="text-muted">You have 2 unread messages today</p>
            </div>
            <button class="btn btn-clear shadow-sm">Mark all as read</button>
        </div>

        <div class="notification-wrapper">
            
            <div class="noti-card shadow-sm">
                <div class="noti-icon bg-success-light">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="noti-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Complaint Resolved!</h6>
                    </div>
                    <p class="noti-text mb-2">Technician fixed the <b>Projector</b> in Room 204. Please check and confirm.</p>
                    <span class="noti-time"><i class="fa-regular fa-clock me-1"></i>15 mins ago</span>
                </div>
                <div class="unread-dot"></div>
            </div>

            <div class="noti-card shadow-sm">
                <div class="noti-icon bg-blue-light">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <div class="noti-content">
                    <h6>Status Updated</h6>
                    <p class="noti-text mb-2">HOD is currently reviewing your <b>Fees Scholarship</b> query.</p>
                    <span class="noti-time"><i class="fa-regular fa-clock me-1"></i>2 hours ago</span>
                </div>
                <div class="unread-dot"></div>
            </div>

            <div class="noti-card shadow-sm">
                <div class="noti-icon bg-orange-light">
                    <i class="fa-solid fa-file-invoice"></i>
                </div>
                <div class="noti-content">
                    <h6>Action Required</h6>
                    <p class="noti-text mb-2">Please upload your <b>ID Proof</b> to complete the hostel registration.</p>
                    <span class="noti-time"><i class="fa-regular fa-clock me-1"></i>Yesterday</span>
                </div>
              
            </div>
  <div class="noti-card shadow-sm">
                    <h6> helo boss</h6>


                </div>
                <div class="noti-card shadow-sm">
                    <h6> Owner of this website (Mayur,Ritik,Pradeep,Gourav)</h6>
        </div>
    </div>
</div>

</body>
</html>