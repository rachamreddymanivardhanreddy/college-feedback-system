<?php
$conn = new mysqli("localhost", "root", "", "college_feedback");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>