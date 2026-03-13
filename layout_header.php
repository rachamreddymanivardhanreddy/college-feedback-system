<?php
if(!isset($_SESSION)) session_start();
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html>
<head>
<title>Feedback System</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body {
    background: #f1f5f9;
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
    overflow-x: hidden;
}

/* Sidebar */
.sidebar {
    min-height: 100vh;
    background: linear-gradient(180deg, #111827, #1f2937);
    transition: all 0.3s;
}

.sidebar a {
    color: #cbd5e1;
    display: block;
    padding: 12px;
    text-decoration: none;
    border-radius: 8px;
    margin-bottom: 6px;
    transition: 0.3s;
}

.sidebar a.active,
.sidebar a:hover {
    background: linear-gradient(45deg,#2563eb,#4f46e5);
    color: white;
    transform: translateX(5px);
}

.sidebar.collapsed {
    width: 70px !important;
}

.sidebar.collapsed a span,
.sidebar.collapsed h5 span {
    display: none;
}

/* Topbar */
.topbar {
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(10px);
    padding: 12px 20px;
    border-bottom: 1px solid #e5e7eb;
}

/* Content */
.content {
    padding: 25px;
}

/* Premium Cards */
.stat-card {
    border: none;
    border-radius: 16px;
    color: white;
    padding: 20px;
    transition: 0.3s;
}

.stat-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 25px rgba(0,0,0,0.1);
}

.gradient-blue { background: linear-gradient(45deg,#2563eb,#3b82f6); }
.gradient-green { background: linear-gradient(45deg,#16a34a,#22c55e); }
.gradient-purple { background: linear-gradient(45deg,#7c3aed,#9333ea); }
.gradient-red { background: linear-gradient(45deg,#dc2626,#ef4444); }

</style>
</head>

<body>

<div class="container-fluid">
<div class="row">

    <!-- Sidebar -->
    <div class="col-md-2 sidebar p-3" id="sidebar">

        <h5 class="text-white mb-4">
            <i class="bi bi-bar-chart"></i> <span>Feedback</span>
        </h5>

        <?php if($_SESSION['role'] == 'admin'){ ?>

        <a href="admin_dashboard.php" class="<?= $current_page=='admin_dashboard.php'?'active':'' ?>">
            <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
        </a>

        <a href="admin_upload_students.php" class="<?= $current_page=='admin_upload_students.php'?'active':'' ?>">
            <i class="bi bi-upload"></i> <span>Upload Students</span>
        </a>

        <a href="admin_upload_teachers.php" class="<?= $current_page=='admin_upload_teachers.php'?'active':'' ?>">
            <i class="bi bi-person-plus"></i> <span>Upload Teachers</span>
        </a>

        

        <a href="report.php" class="<?= $current_page=='report.php'?'active':'' ?>">
            <i class="bi bi-file-earmark-text"></i> <span>Reports</span>
        </a>

        
        <a href="about.php" class="<?= $current_page=='about.php'?'active':'' ?>">
            <i class="bi bi-info-circle"></i> <span>About</span>
        </a>

        <?php } ?>

        <?php if($_SESSION['role'] == 'hod'){ ?>

        <a href="hod_dashboard.php" class="<?= $current_page=='hod_dashboard.php'?'active':'' ?>">
            <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
        </a>

        <?php } ?>

        <hr class="text-secondary">

        <a href="logout.php">
            <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
        </a>

    </div>

    <!-- Main Content -->
    <div class="col-md-10">

        <!-- Top Navbar -->
        <div class="topbar d-flex justify-content-between align-items-center">

            <button class="btn btn-outline-secondary btn-sm" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>

            <div>
                <span class="badge bg-primary me-2">
                    <?= strtoupper($_SESSION['role']); ?>
                </span>
                <strong>User ID:</strong> <?= $_SESSION['user_id']; ?>
            </div>

        </div>

        <div class="content">