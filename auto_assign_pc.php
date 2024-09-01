<?php
require_once 'includes/dbconnection.php';

if (isset($_GET['section_id'])) {
    $section_id = $_GET['section_id'];

    // Fetch all students in the section
    $students_query = $dbh->prepare("SELECT * FROM students WHERE section_id = :section_id");
    $students_query->bindParam(':section_id', $section_id, PDO::PARAM_INT);
    $students_query->execute();
    $student_list = $students_query->fetchAll(PDO::FETCH_ASSOC);

    // Here you can implement your logic to auto-assign PCs to students
    foreach ($student_list as $student) {
        // For example, you could update the student record with a PC number
        $pc_number = rand(1, 50); // Random PC number for illustration
        $update_query = $dbh->prepare("UPDATE students SET pc_number = :pc_number WHERE student_id = :student_id");
        $update_query->bindParam(':pc_number', $pc_number, PDO::PARAM_INT);
        $update_query->bindParam(':student_id', $student['student_id'], PDO::PARAM_INT);
        $update_query->execute();
    }

    echo "Students have been auto-assigned to PCs successfully";
}
?>
