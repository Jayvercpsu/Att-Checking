<?php
include('includes/dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];

    $query = "DELETE FROM students WHERE student_id = :student_id";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
