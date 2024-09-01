<?php
session_start();
include('includes/dbconnection.php');

// Get parameters from the query string
$day = isset($_GET['day']) ? $_GET['day'] : '';

// Map day to table names
$day_to_table = [
    'Monday' => 'monday_attendance',
    'Tuesday' => 'tuesday_attendance',
    'Wednesday' => 'wednesday_attendance',
    'Thursday' => 'thursday_attendance',
    'Friday' => 'friday_attendance'
];

// Get the corresponding table name
$table_name = isset($day_to_table[$day]) ? $day_to_table[$day] : '';

if ($table_name) {
    // Display the message for the selected day
    echo '<div class="alert alert-info text-center" role="alert">Attendance for ' . htmlspecialchars($day) . '.</div>';

    // Fetch all sections
    $section_query = "SELECT section_id, section_name FROM sections";
    $section_stmt = $dbh->prepare($section_query);
    $section_stmt->execute();
    $sections = $section_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($sections as $section) {
        $section_id = $section['section_id'];
        $section_name = $section['section_name'];

        // Fetch attendance records for the selected day and section
        $query = "SELECT s.student_id, s.student_name, COALESCE(a.status, '') AS status
                  FROM students s
                  LEFT JOIN $table_name a ON s.student_id = a.student_id AND a.section_id = :section_id
                  WHERE s.section_id = :section_id
                  ORDER BY s.student_id ASC";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':section_id', $section_id, PDO::PARAM_INT);
        $stmt->execute();
        $attendance_records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Display the section name
        echo '<h4>' . htmlspecialchars($section_name) . '</h4>';

        // Display the attendance records
        if ($attendance_records) {
            echo '<table class="table table-bordered">';
            echo '<thead><tr><th>Student ID</th><th>Student Name</th><th>Status</th></tr></thead><tbody>';
            foreach ($attendance_records as $record) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($record['student_id']) . '</td>';
                echo '<td>' . htmlspecialchars($record['student_name']) . '</td>';
                echo '<td>' . htmlspecialchars($record['status']) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '<div class="alert alert-warning text-center" role="alert">No attendance records found for ' . htmlspecialchars($day) . '.</div>';
        }
    }
} else {
    echo '<div class="alert alert-danger text-center" role="alert">Invalid day selected.</div>';
}
?>
