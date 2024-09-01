<?php
session_start();
include('includes/dbconnection.php');

// Ensure the instructor is logged in
if (!isset($_SESSION['trmsuid'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

$instructor_id = $_SESSION['trmsuid'];

// Check if form is submitted
if (isset($_POST['add_subject_load'])) {
    $subject_name = trim($_POST['subject_name']);
    
    if (empty($subject_name)) {
        $message = "Subject name cannot be empty.";
    } else {
        // Check if the instructor ID exists
        $check_query = $dbh->prepare("SELECT * FROM instructors WHERE instructor_id = ?");
        $check_query->execute([$instructor_id]);

        if ($check_query->rowCount() > 0) {
            // If instructor ID exists, insert the subject load
            $query = $dbh->prepare("INSERT INTO subjects (subject_name, instructor_id) VALUES (?, ?)");
            if ($query->execute([$subject_name, $instructor_id])) {
                $message = "Subject load added successfully!";
            } else {
                $message = "Failed to add subject load.";
            }
        } else {
            $message = "Invalid instructor ID.";
        }
    }
}
?>

<!doctype html>
<html class="no-js" lang="en">
<head>
    <title>Instructor Dashboard</title>
    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/selectFX/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="vendors/jqvmap/dist/jqvmap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
</head>
<body>

<?php include_once('includes/sidebar.php');?>

<div id="right-panel" class="right-panel">

    <?php include_once('includes/header.php');?>

    <div class="breadcrumbs">
        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>Dashboard</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li class="active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <h1 class="mb-4">Add Subject Load</h1>
        <?php if (isset($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="subject_name">Subject Name</label>
                <input type="text" class="form-control" id="subject_name" name="subject_name" required>
            </div>
            <button type="submit" name="add_subject_load" class="btn btn-primary">Add Subject Load</button>
        </form>
    </div>
    
    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="vendors/chart.js/dist/Chart.bundle.min.js"></script>
    <script src="assets/js/dashboard.js"></script>
    <script src="assets/js/widgets.js"></script>
    <script src="vendors/jqvmap/dist/jquery.vmap.min.js"></script>
    <script src="vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <script src="vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>

</body>
</html>
