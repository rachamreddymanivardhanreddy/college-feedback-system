<?php
include 'config.php';

$roll_no = $_POST['roll_no'];
$email = $_POST['email'];
$department = $_POST['department'];
$semester = $_POST['semester'];
$section = $_POST['section'];

$stmt = $conn->prepare("
SELECT id, has_submitted FROM students
WHERE roll_no = ?
AND email = ?
AND department = ?
AND semester = ?
AND section = ?
");

$stmt->bind_param("sssss", $roll_no, $email, $department, $semester, $section);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Student not found.");
}

$student = $result->fetch_assoc();

if ($student['has_submitted'] == 1) {
    die("You already submitted feedback.");
}

$student_id = $student['id'];

$stmt2 = $conn->prepare("INSERT INTO feedback (student_id) VALUES (?)");
$stmt2->bind_param("i", $student_id);
$stmt2->execute();

$feedback_id = $stmt2->insert_id;

foreach ($_POST['teacher_rating'] as $teacher_id => $rating) {
    $stmt3 = $conn->prepare("
    INSERT INTO teacher_ratings (feedback_id, teacher_id, rating)
    VALUES (?, ?, ?)
    ");
    $stmt3->bind_param("iii", $feedback_id, $teacher_id, $rating);
    $stmt3->execute();
}

$update = $conn->prepare("UPDATE students SET has_submitted = 1 WHERE id = ?");
$update->bind_param("i", $student_id);
$update->execute();

echo "Feedback submitted successfully!";
?>