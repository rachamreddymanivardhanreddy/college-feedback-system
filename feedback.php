<?php
include 'config.php';

// Check Deadline
$deadlineQuery = $conn->query("SELECT feedback_deadline FROM settings LIMIT 1");
$deadlineData = $deadlineQuery->fetch_assoc();
$deadline = $deadlineData['feedback_deadline'];

$current_date = date('Y-m-d');

if ($current_date > $deadline) {
    die("<h2>Feedback Submission Closed</h2><p>The deadline has passed.</p>");
}
?>

<h2>Student Feedback Form</h2>

<form action="submit_feedback.php" method="POST">

<h3>Student Details</h3>

<input type="text" name="student_name" placeholder="Name" required><br><br>
<input type="text" name="roll_no" placeholder="Roll No" required><br><br>
<input type="email" name="email" placeholder="Email" required><br><br>
<input type="text" name="department" placeholder="Department" required><br><br>
<input type="text" name="semester" placeholder="Semester" required><br><br>
<input type="text" name="year" placeholder="Year" required><br><br>
<input type="text" name="section" placeholder="Section" required><br><br>

<h3>Teacher Ratings</h3>

<?php
$teachers = $conn->query("SELECT * FROM teachers");
while($row = $teachers->fetch_assoc()) {
?>

<label>
<?php echo $row['teacher_name']; ?> (<?php echo $row['subject']; ?>)
</label>

<select name="teacher_rating[<?php echo $row['id']; ?>]" required>
<option value="1">Poor</option>
<option value="2">Average</option>
<option value="3">Good</option>
<option value="4">Very Good</option>
<option value="5">Excellent</option>
</select>

<br><br>

<?php } ?>

<button type="submit">Submit Feedback</button>

</form>