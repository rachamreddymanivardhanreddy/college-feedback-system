<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

require('fpdf.php');
include 'config.php';

$department = $_GET['department'] ?? '';

if (!$department) {
    die("Department not specified.");
}

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);

// Title
$pdf->Cell(0,10,"Department Feedback Report",0,1,'C');
$pdf->Ln(5);

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,"Department: $department",0,1);
$pdf->Ln(5);

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

$pdf->Cell(0,10,"Total Students Responded: $total_responses",0,1);
$pdf->Ln(5);

// Table Header
$pdf->SetFont('Arial','B',12);
$pdf->Cell(60,10,"Teacher",1);
$pdf->Cell(60,10,"Subject",1);
$pdf->Cell(40,10,"Average Rating",1);
$pdf->Ln();

$pdf->SetFont('Arial','',12);

// Teacher Data
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

$overall_total = 0;
$count = 0;

while($row = $result->fetch_assoc()) {
    $pdf->Cell(60,10,$row['teacher_name'],1);
    $pdf->Cell(60,10,$row['subject'],1);
    $pdf->Cell(40,10,round($row['avg_rating'],2),1);
    $pdf->Ln();

    $overall_total += $row['avg_rating'];
    $count++;
}

// Overall Average
if($count > 0){
    $department_avg = $overall_total / $count;
    $pdf->Ln(5);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,"Overall Department Average: " . round($department_avg,2),0,1);
}

$pdf->Output();
?>