<?php
session_start();
include('includes/dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $section_id = $_POST['section_id'];
    $attendance_date = $_POST['attendance_date'];
    $attendance_data = $_POST['attendance'];

    // Determine the day of the week for the given attendance date
    $day_of_week = date('l', strtotime($attendance_date)); // 'l' gives the full textual representation of the day

    // Check if the day is Saturday or Sunday
    if ($day_of_week == 'Saturday' || $day_of_week == 'Sunday') {
        header("Location: attendance.php?section_id=$section_id&message=attendance_not_available");
        exit();
    }

    // Map day of the week to table names
    $day_to_table = [
        'Monday' => 'monday_attendance',
        'Tuesday' => 'tuesday_attendance',
        'Wednesday' => 'wednesday_attendance',
        'Thursday' => 'thursday_attendance',
        'Friday' => 'friday_attendance'
    ];

    // Get the corresponding table name
    $table_name = $day_to_table[$day_of_week];

    foreach ($attendance_data as $student_id => $status) {
        $query = "INSERT INTO $table_name (section_id, student_id, attendance_date, status) 
                  VALUES (:section_id, :student_id, :attendance_date, :status)
                  ON DUPLICATE KEY UPDATE status = :status";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':section_id', $section_id, PDO::PARAM_INT);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->bindParam(':attendance_date', $attendance_date, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->execute();
    }

    header("Location: attendance.php?section_id=$section_id&message=attendance_saved");
    exit();
}
?>
