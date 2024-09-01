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

// Get subject load ID from query string
$subject_load_id = $_GET['subject_load_id'];

$stmt = $dbh->prepare("SELECT * FROM sections WHERE subject_load_id = :subject_load_id");
$stmt->bindParam(':subject_load_id', $subject_load_id);
$stmt->execute();
$sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Sections</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Sections</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Section Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sections as $section): ?>
                    <tr>
                        <td><?= $section['id'] ?></td>
                        <td><?= $section['section_name'] ?></td>
                        <td>
                            <a href="view_students.php?section_id=<?= $section['id'] ?>" class="btn btn-info btn-sm">View Students</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="add_section.php?subject_load_id=<?= $subject_load_id ?>" class="btn btn-success">Add Section</a>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
