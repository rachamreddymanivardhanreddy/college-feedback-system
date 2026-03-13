<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

$message = "";

if(isset($_POST['upload'])){

    if(isset($_FILES['csv_file']['tmp_name'])){

        $file = fopen($_FILES['csv_file']['tmp_name'], "r");

        fgetcsv($file); // Skip header row

        while(($row = fgetcsv($file)) !== FALSE){

            $name = $row[0];
            $roll = $row[1];
            $email = $row[2];
            $dept = $row[3];
            $year = $row[4];
            $semester = $row[5];

            // Prevent duplicate roll number
            $check = $conn->prepare("SELECT id FROM students WHERE roll_no = ?");
            $check->bind_param("s", $roll);
            $check->execute();
            $result = $check->get_result();

            if($result->num_rows == 0){

                $stmt = $conn->prepare("
                INSERT INTO students (student_name, roll_no, email, department_id, year, semester)
                VALUES (?, ?, ?, ?, ?, ?)
                ");

                $stmt->bind_param("sssiii", $name, $roll, $email, $dept, $year, $semester);
                $stmt->execute();
            }
        }

        fclose($file);

        $message = "Students uploaded successfully!";
    }
}

include 'layout_header.php';
?>

<h2 class="fw-bold mb-4">Upload Students (CSV)</h2>

<?php if($message) echo "<div class='alert alert-success'>$message</div>"; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label class="form-label">Select CSV File</label>
        <input type="file" name="csv_file" accept=".csv" class="form-control" required>
    </div>
    <button type="submit" name="upload" class="btn btn-primary">Upload</button>
</form>

<?php include 'layout_footer.php'; ?>