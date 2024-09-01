<?php
// Start session
session_start();

// Check if the user is logged in and is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Include database connection file
require_once 'dbconnection.php';

// Get instructor ID from query string
$id = $_GET['id'];

$stmt = $dbh->prepare("DELETE FROM instructors WHERE id = :id");
$stmt->bindParam(':id', $id);

if ($stmt->execute()) {
    header('Location: dashboard.php');
} else {
    echo "Failed to delete instructor.";
}
?>
