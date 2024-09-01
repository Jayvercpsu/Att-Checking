<?php
session_start();
include('includes/dbconnection.php');

// Array of attendance table names
$attendance_tables = [
    'monday_attendance',
    'tuesday_attendance',
    'wednesday_attendance',
    'thursday_attendance',
    'friday_attendance'
];

// Loop through each table and truncate (delete all records)
foreach ($attendance_tables as $table) {
    $query = "TRUNCATE TABLE $table";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
}

// Redirect back to attendance page with a success message
header("Location: attendance.php?message=attendance_reset");
exit();
?>
