<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'hod') {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

$department = $_SESSION['department_id'];
$year = $_GET['year'] ?? '';
$semester = $_GET['semester'] ?? '';

// SAME QUERIES AS ADMIN (copy same logic)
// (Use same query code from admin_dashboard above)

include 'layout_header.php';
?>

<h2 class="fw-bold mb-4">HOD Dashboard</h2>

<form method="GET" class="row g-3 mb-4">
<div class="col-md-4">
<label>Year</label>
<select name="year" class="form-select">
<option value="">All</option>
<option value="1" <?= $year==1?'selected':''; ?>>1</option>
<option value="2" <?= $year==2?'selected':''; ?>>2</option>
<option value="3" <?= $year==3?'selected':''; ?>>3</option>
<option value="4" <?= $year==4?'selected':''; ?>>4</option>
</select>
</div>

<div class="col-md-4">
<label>Semester</label>
<select name="semester" class="form-select">
<option value="">All</option>
<option value="1" <?= $semester==1?'selected':''; ?>>1</option>
<option value="2" <?= $semester==2?'selected':''; ?>>2</option>
</select>
</div>

<div class="col-md-4 d-flex align-items-end">
<button type="submit" class="btn btn-primary w-100">Filter</button>
</div>
</form>

<!-- Same Premium Cards + Chart -->

<?php include 'layout_footer.php'; ?>