<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

$message = "";
$inserted = 0;
$skipped = 0;

if(isset($_POST['upload'])){

    if(isset($_FILES['csv_file']['tmp_name'])){

        $file = fopen($_FILES['csv_file']['tmp_name'], "r");

        fgetcsv($file); // skip header

        while(($row = fgetcsv($file)) !== FALSE){

            $teacher_name = $row[0];
            $dept = $row[1];

            // Prevent duplicate teacher name in same department
            $check = $conn->prepare("
           SELECT id FROM teachers 
            WHERE teacher_name = ? AND department = ?
            ");
            $check->bind_param("si", $teacher_name, $dept);
            $check->execute();
            $result = $check->get_result();

            if($result->num_rows == 0){

                $stmt = $conn->prepare("
                INSERT INTO teachers (teacher_name, department)
                VALUES (?, ?)
                ");
                $stmt->bind_param("si", $teacher_name, $dept);
                $stmt->execute();

                $inserted++;
            } else {
                $skipped++;
            }
        }

        fclose($file);

        $message = "Upload Complete! Inserted: $inserted | Skipped: $skipped";
    }
}

include 'layout_header.php';
?>

<h2 class="fw-bold mb-4">Upload Teachers (CSV)</h2>

<?php if($message) echo "<div class='alert alert-success'>$message</div>"; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label class="form-label">Select CSV File</label>
        <input type="file" name="csv_file" accept=".csv" class="form-control" required>
    </div>
    <button type="submit" name="upload" class="btn btn-primary">Upload</button>
</form>

<?php include 'layout_footer.php'; ?>