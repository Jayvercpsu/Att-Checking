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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];

    $stmt = $dbh->prepare("INSERT INTO students (section_id, name) VALUES (:section_id, :name)");
    $stmt->bindParam(':section_id', $section_id);
    $stmt->bindParam(':name', $name);

    if ($stmt->execute()) {
        header('Location: view_students.php?section_id=' . $section_id);
    } else {
        $error = "Failed to add student.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Add Student</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="name">Student Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <button type="submit" class="btn btn-success">Add Student</button>
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
