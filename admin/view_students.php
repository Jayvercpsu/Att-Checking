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

// Get section ID from query string
$section_id = $_GET['section_id'];

$stmt = $dbh->prepare("SELECT * FROM students WHERE section_id = :section_id");
$stmt->bindParam(':section_id', $section_id);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Students</h1>
        <form method="POST" action="attendance.php">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Present</th>
                        <th>Absent</th>
                        <th>Excused</th>
                        <th>Late</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= $student['id'] ?></td>
                            <td><?= $student['name'] ?></td>
                            <td><input type="checkbox" name="attendance[<?= $student['id'] ?>]" value="present"></td>
                            <td><input type="checkbox" name="attendance[<?= $student['id'] ?>]" value="absent"></td>
                            <td><input type="checkbox" name="attendance[<?= $student['id'] ?>]" value="excused"></td>
                            <td><input type="checkbox" name="attendance[<?= $student['id'] ?>]" value="late"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Submit Attendance</button>
        </form>
        <a href="add_student.php?section_id=<?= $section_id ?>" class="btn btn-success">Add Student</a>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
