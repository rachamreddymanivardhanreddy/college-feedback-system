<?php
include 'config.php';

if(isset($_POST['upload'])){

    $file = $_FILES['csv']['tmp_name'];
    $handle = fopen($file, "r");

    $header = fgetcsv($handle);

    while(($row = fgetcsv($handle)) !== FALSE){

        $data = array_combine($header, $row);

        // 1️⃣ Insert into feedback_master
        $dept = $data['deparment'];

        $stmt = $conn->prepare("INSERT INTO feedback_master (department) VALUES (?)");
        $stmt->bind_param("s", $dept);
        $stmt->execute();

        $feedback_id = $stmt->insert_id;

        // 2️⃣ Loop through columns and detect course blocks
        foreach($data as $column => $value){

            if(strpos($column, "Course Content") !== false){

                $course_part = explode(" - ", $column)[0];
                $course_name = str_replace("Course Name: ", "", $course_part);

                // Collect ratings safely
                $course_content = convertRating($value);

                $conceptual = convertRating($data[$course_name." - Breadth of conceptual clarity"] ?? 0);
                $evaluation = convertRating($data[$course_name." - Evaluation Scheme"] ?? 0);
                $faculty_inv = convertRating($data[$course_name." - Involvement of Faculty"] ?? 0);
                $overall = convertRating($data[$course_name." - Overall effectiveness"] ?? 0);
                $prepared = convertRating($data[$course_name." - Preparedness"] ?? 0);
                $interaction = convertRating($data[$course_name." - Quality of Interaction"] ?? 0);
                $rigor = convertRating($data[$course_name." - Quality of Rigor"] ?? 0);
                $sequencing = convertRating($data[$course_name." - Sequencing"] ?? 0);
                $delivery = convertRating($data[$course_name." - Style of delivery"] ?? 0);
                $time = convertRating($data[$course_name." - Time allotted"] ?? 0);
                $value_add = convertRating($data[$course_name." - Value addition"] ?? 0);
                $assessment = convertRating($data[$course_name." - Standards of Assessment"] ?? 0);
                $teaching = convertRating($data[$course_name." - Quality of teaching"] ?? 0);
                $suggestion = $data[$course_name." - Suggestions"] ?? '';

                $insert = $conn->prepare("
                INSERT INTO course_feedback
                (feedback_id, course_name, course_content, conceptual_clarity,
                evaluation_scheme, faculty_involvement, overall_effectiveness,
                preparedness, quality_interaction, quality_rigor, sequencing,
                delivery_style, time_allotted, value_addition,
                assessment_standard, quality_teaching, suggestion)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
                ");

                $insert->bind_param("isiiiiiiiiiiiiiis",
                $feedback_id, $course_name, $course_content, $conceptual,
                $evaluation, $faculty_inv, $overall,
                $prepared, $interaction, $rigor,
                $sequencing, $delivery, $time, $value_add,
                $assessment, $teaching, $suggestion
                );

                $insert->execute();
            }
        }
    }

    echo "Import Successful!";
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

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="csv" required>
    <button type="submit" name="upload">Upload</button>
</form>