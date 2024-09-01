<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/dbconnection.php');

// Check if the instructor's ID is stored in session
if (isset($_SESSION['instructor_id'])) {
    $instructor_id = $_SESSION['instructor_id'];

    $subjects_query = $dbh->prepare("SELECT * FROM subjects WHERE instructor_id = :instructor_id");
    $subjects_query->bindParam(':instructor_id', $instructor_id, PDO::PARAM_INT);
    $subjects_query->execute();
    $subject_list = $subjects_query->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Handle the case where the instructor_id is not in session
    $subject_list = [];
    echo "";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Instructor Dashboard</title>
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

    <?php include_once('includes/sidebar.php'); ?>

    <div id="right-panel" class="right-panel">
        <?php include_once('includes/header.php'); ?>

        <div class="content">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Instructor Dashboard</strong>
                            </div>



                            <div class="col-sm-6 col-lg-12">
                                <div class="card text-white bg-flat-color-4">
                                    <div class="card-body pb-0">
                                        <div class="dropdown float-right">


                                        </div>
                                        <?php
                                        $eid = $_SESSION['trmsuid'];
                                        $query = $dbh->prepare("SELECT * from  tblteacher where ID=$eid");
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        foreach ($results as $row)
                                        ?>
                                            <h3 class="mb-0">
                                            <span>Welcome <?php echo htmlentities($row->Name) . '!'; ?></span>

                                            </h3>
                                            <hr />
                                           
                                        <div class="chart-wrapper px-3" style="height:70px;" height="70">
                                            <canvas id="widgetChart4"></canvas>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!--/.col-->











                            
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