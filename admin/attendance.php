<?php
// Start session
session_start();

// Check if the user is logged in and is instructor
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'instructor') {
    header('Location: login.php');
    exit();
}

// Include database connection file
require_once 'dbconnection.php';

// Process attendance
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST['attendance'] as $student_id => $status) {
        $stmt = $dbh->prepare("INSERT INTO attendance (student_id, status, date) VALUES (:student_id, :status, NOW())");
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
    }

    header('Location: dashboard.php');
}
?>
