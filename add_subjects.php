<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/dbconnection.php');

// Check if the instructor's ID is stored in session
if (isset($_SESSION['instructor_id'])) {
    $instructor_id = $_SESSION['instructor_id'];
    
    if (isset($_POST['subject_name'])) {
        $subject_name = $_POST['subject_name'];

        // Prepare and execute the query to add a new subject
        $sql = "INSERT INTO subjects (instructor_id, subject_name) VALUES (:instructor_id, :subject_name)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':instructor_id', $instructor_id, PDO::PARAM_INT);
        $query->bindParam(':subject_name', $subject_name, PDO::PARAM_STR);
        
        if ($query->execute()) {
            // Redirect to dashboard.php after successful addition
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error: Subject could not be added";
        }
    } else {
        echo "Subject name is not set";
    }
} else {
    echo "Instructor ID is not set in the session.";
}
?>

 