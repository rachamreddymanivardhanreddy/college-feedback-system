<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

$department = $_GET['department'] ?? 1;
$year = $_GET['year'] ?? '';
$semester = $_GET['semester'] ?? '';

/* TOTAL RESPONSES */
$totalQuery = $conn->prepare("
SELECT COUNT(*) as total
FROM feedback f
JOIN students s ON f.student_id = s.id
WHERE s.department = ?
AND (? = '' OR s.year = ?)
AND (? = '' OR s.semester = ?)
");
$totalQuery->bind_param("issss", $department, $year, $year, $semester, $semester);
$totalQuery->execute();
$total_responses = $totalQuery->get_result()->fetch_assoc()['total'];

/* TOTAL STUDENTS */
$totalStudentsQuery = $conn->prepare("
SELECT COUNT(*) as total_students
FROM students
WHERE department = ?
AND (? = '' OR year = ?)
AND (? = '' OR semester = ?)
");
$totalStudentsQuery->bind_param("issss", $department, $year, $year, $semester, $semester);
$totalStudentsQuery->execute();
$total_students = $totalStudentsQuery->get_result()->fetch_assoc()['total_students'];

$submission_percentage = $total_students > 0 ? ($total_responses/$total_students)*100 : 0;

/* TEACHER RATINGS */
$query = $conn->prepare("
SELECT t.teacher_name, AVG(tr.rating) as avg_rating
FROM teacher_ratings tr
JOIN teachers t ON tr.teacher_id = t.id
JOIN feedback f ON tr.feedback_id = f.id
JOIN students s ON f.student_id = s.id
WHERE s.department = ?
AND (? = '' OR s.year = ?)
AND (? = '' OR s.semester = ?)
GROUP BY t.id
");
$query->bind_param("issss", $department, $year, $year, $semester, $semester);
$query->execute();
$result = $query->get_result();

$teachers = [];
$ratings = [];
$bestTeacher = "";
$lowestTeacher = "";
$bestRating = 0;
$lowestRating = 5;

while($row = $result->fetch_assoc()){
    $teachers[] = $row['teacher_name'];
    $ratings[] = round($row['avg_rating'],2);

    if($row['avg_rating'] > $bestRating){
        $bestRating = $row['avg_rating'];
        $bestTeacher = $row['teacher_name'];
    }
    if($row['avg_rating'] < $lowestRating){
        $lowestRating = $row['avg_rating'];
        $lowestTeacher = $row['teacher_name'];
    }
}

include 'layout_header.php';
?>

<h2 class="fw-bold mb-4">Admin Dashboard</h2>

<!-- Filters -->
<form method="GET" class="row g-3 mb-4">
<div class="col-md-3">
<label>Department</label>
<select name="department" class="form-select">
<?php
$deptResult = $conn->query("SELECT * FROM departments");
while($dept = $deptResult->fetch_assoc()){
?>
<option value="<?= $dept['id']; ?>" <?= $dept['id']==$department?'selected':''; ?>>
<?= $dept['department_name']; ?>
</option>
<?php } ?>
</select>
</div>

<div class="col-md-3">
<label>Year</label>
<select name="year" class="form-select">
<option value="">All</option>
<option value="1" <?= $year==1?'selected':''; ?>>1</option>
<option value="2" <?= $year==2?'selected':''; ?>>2</option>
<option value="3" <?= $year==3?'selected':''; ?>>3</option>
<option value="4" <?= $year==4?'selected':''; ?>>4</option>
</select>
</div>

<div class="col-md-3">
<label>Semester</label>
<select name="semester" class="form-select">
<option value="">All</option>
<option value="1" <?= $semester==1?'selected':''; ?>>1</option>
<option value="2" <?= $semester==2?'selected':''; ?>>2</option>
</select>
</div>

<div class="col-md-3 d-flex align-items-end">
<button type="submit" class="btn btn-primary w-100">Filter</button>
</div>
</form>

<!-- Premium Cards -->
<div class="row mb-4">

<div class="col-md-3">
<div class="stat-card gradient-blue">
<h6>Total Responses</h6>
<h2><?= $total_responses ?></h2>
</div>
</div>

<div class="col-md-3">
<div class="stat-card gradient-green">
<h6>Submission %</h6>
<h2><?= round($submission_percentage,2) ?>%</h2>
</div>
</div>

<div class="col-md-3">
<div class="stat-card gradient-purple">
<h6>Best Teacher</h6>
<h5><?= $bestTeacher ?: "No Data" ?></h5>
</div>
</div>

<div class="col-md-3">
<div class="stat-card gradient-red">
<h6>Lowest Teacher</h6>
<h5><?= $lowestTeacher ?: "No Data" ?></h5>
</div>
</div>

</div>

<h4 class="fw-bold mb-3">Teacher Performance</h4>
<canvas id="teacherChart"></canvas>

<script>
new Chart(document.getElementById('teacherChart'), {
type: 'bar',
data: {
labels: <?= json_encode($teachers); ?>,
datasets: [{
label: 'Average Rating',
data: <?= json_encode($ratings); ?>,
borderWidth: 1
}]
},
options: {
responsive: true,
scales: {
y: {
beginAtZero: true,
max: 5
}
}
}
});
</script>

<?php include 'layout_footer.php'; ?>