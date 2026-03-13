<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

$department = $_GET['department'] ?? '';

if (!$department) {
    die("Department not specified.");
}

// Total Responses
$totalQuery = $conn->prepare("
SELECT COUNT(*) as total 
FROM feedback f
JOIN students s ON f.student_id = s.id
WHERE s.department = ?
");

$totalQuery->bind_param("s", $department);
$totalQuery->execute();
$totalResult = $totalQuery->get_result()->fetch_assoc();
$total_responses = $totalResult['total'];

// Teacher Average Ratings
$query = $conn->prepare("
SELECT t.teacher_name, t.subject, AVG(tr.rating) as avg_rating
FROM teacher_ratings tr
JOIN teachers t ON tr.teacher_id = t.id
JOIN feedback f ON tr.feedback_id = f.id
JOIN students s ON f.student_id = s.id
WHERE s.department = ?
GROUP BY t.id
");

$query->bind_param("s", $department);
$query->execute();
$result = $query->get_result();
?>

<h2>Department Report: <?php echo $department; ?></h2>
<p><strong>Total Students Responded:</strong> <?php echo $total_responses; ?></p>

<table border="1" cellpadding="10">
<tr>
    <th>Teacher</th>
    <th>Subject</th>
    <th>Average Rating</th>
</tr>

<?php 
$overall_total = 0;
$count = 0;

while($row = $result->fetch_assoc()) { 
    $overall_total += $row['avg_rating'];
    $count++;
?>
<tr>
    <td><?php echo $row['teacher_name']; ?></td>
    <td><?php echo $row['subject']; ?></td>
    <td><?php echo round($row['avg_rating'],2); ?></td>
</tr>
<?php } ?>

</table>

<?php
if($count > 0){
    $department_avg = $overall_total / $count;
    echo "<h3>Overall Department Average: " . round($department_avg,2) . "</h3>";
}
?>

<br><br>
<a href="generate_pdf.php?department=<?php echo $department; ?>">Download PDF</a>