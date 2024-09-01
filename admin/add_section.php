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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $section_name = $_POST['section_name'];

    $stmt = $dbh->prepare("INSERT INTO sections (subject_load_id, section_name) VALUES (:subject_load_id, :section_name)");
    $stmt->bindParam(':subject_load_id', $subject_load_id);
    $stmt->bindParam(':section_name', $section_name);

    if ($stmt->execute()) {
        header('Location: view_sections.php?subject_load_id=' . $subject_load_id);
    } else {
        $error = "Failed to add section.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Section</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Add Section</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="section_name">Section Name</label>
                <input type="text" class="form-control" id="section_name" name="section_name" required>
            </div>
            <button type="submit" class="btn btn-success">Add Section</button>
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
