<?php
session_start();
include('includes/dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_name = $_POST['student_name'];
    $section_id = $_POST['section_id'];

    if (!empty($student_name) && !empty($section_id)) {
        $query = "INSERT INTO students (student_name, section_id) VALUES (:student_name, :section_id)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':student_name', $student_name, PDO::PARAM_STR);
        $stmt->bindParam(':section_id', $section_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Student added successfully ✅';
            header('Location: sections.php');
            exit();
        } else {
            echo 'Failed to add student.';
        }
    } else {
        echo 'All fields are required.';
    }
}
