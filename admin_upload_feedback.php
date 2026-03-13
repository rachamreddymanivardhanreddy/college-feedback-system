<?php
session_start();
include 'config.php';
include 'layout_header.php';   // ✅ IMPORTANT

if(isset($_POST['upload'])){

    $file = $_FILES['csv']['tmp_name'];
    $handle = fopen($file, "r");

    $header = fgetcsv($handle);

    while(($row = fgetcsv($handle)) !== FALSE){

        $data = array_combine($header, $row);

        // Insert into feedback_master
        $dept = $data['Department'] ?? 'Unknown';

        $stmt = $conn->prepare("INSERT INTO feedback_master (department) VALUES (?)");
        $stmt->bind_param("s", $dept);
        $stmt->execute();

        $feedback_id = $stmt->insert_id;

        foreach($data as $column => $value){

            if(strpos($column, "Course Content") !== false){

                $course_part = explode(" - ", $column)[0];
                $course_name = str_replace("Course Name: ", "", $course_part);

                $course_content = convertRating($value);

                $insert = $conn->prepare("
                INSERT INTO course_feedback
                (feedback_id, course_name, course_content)
                VALUES (?,?,?)
                ");

                $insert->bind_param("isi", $feedback_id, $course_name, $course_content);
                $insert->execute();
            }
        }
    }

    echo "<div class='alert alert-success'>Google Feedback Imported Successfully!</div>";
}

function convertRating($rating){
    switch(trim($rating)){
        case "Excellent": return 5;
        case "Very Good": return 4;
        case "Good": return 3;
        case "Average": return 2;
        case "Poor": return 1;
        default: return 0;
    }
}
?>

<h3 class="mb-4">Upload Google Form Feedback CSV</h3>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="csv" class="form-control mb-3" required>
    <button type="submit" name="upload" class="btn btn-primary">Upload CSV</button>
</form>

<?php include 'layout_footer.php'; ?>   <!-- ✅ IMPORTANT -->