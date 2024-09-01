<?php
session_start();
include('includes/dbconnection.php');

if (isset($_POST['student_id']) && isset($_POST['student_name']) && isset($_POST['section_id'])) {
    $student_id = $_POST['student_id'];
    $student_name = $_POST['student_name'];
    $section_id = $_POST['section_id'];

    $query = "UPDATE students SET student_name = ?, section_id = ? WHERE student_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sii", $student_name, $section_id, $student_id);

    if ($stmt->execute()) {
        echo "Student updated successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid input";
}
$conn->close();
?>
