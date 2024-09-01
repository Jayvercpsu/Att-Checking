<?php
session_start();
include('includes/dbconnection.php');

// Fetch all sections
$section_query = "SELECT section_id, section_name FROM sections";
$section_stmt = $dbh->prepare($section_query);
$section_stmt->execute();
$sections = $section_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get message from the query string
$message = isset($_GET['message']) ? $_GET['message'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Instructor Attendance</title>
    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/selectFX/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="vendors/jqvmap/dist/jqvmap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<style>
    .container {
        margin-top: 20px;
    }

    .back-button {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .back-button i {
        font-size: 20px;
        margin-right: 10px;
    }

    .section-title {
        text-align: center;
        margin-bottom: 20px;
        font-size: 24px;
        font-weight: bold;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-check-inline {
        margin-right: 10px;
    }

    .delete-button {
        float: right;
    }
</style>

<body>
    <!-- Left Panel -->
    <?php include_once('includes/sidebar.php'); ?>
    <div id="right-panel" class="right-panel">
        <!-- Header-->
        <?php include_once('includes/header.php'); ?>
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Attendance</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li><a href="sections.php">Sections</a></li>
                            <li class="active">Attendance</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="back-button">
                <a href="sections.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Sections
                </a>
            </div>
            <div class="section-title">
                Attendance for All Sections
            </div>
            <!-- Message Display -->
            <?php
            if ($message == 'attendance_not_available') {
                echo '<div class="alert alert-warning text-center" role="alert" id="attendance-message">Attendance is only available from Monday to Friday.</div>';
            } elseif ($message == 'attendance_saved') {
                echo '<div class="alert alert-success text-center" role="alert" id="attendance-message">Attendance saved successfully.</div>';
            } elseif ($message == 'attendance_reset') {
                echo '<div class="alert alert-success text-center" role="alert" id="attendance-message">All attendance records have been reset.</div>';
            }
            ?>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $(document).ready(function() {
                    // Check if the message exists and fade it out after 1 second
                    if ($('#attendance-message').length) {
                        setTimeout(function() {
                            $('#attendance-message').fadeOut();
                        }, 900);
                    }
                });
            </script>


            <!-- Reset Button -->
            <div class="text-center mb-4">
                <form action="reset_attendance.php" method="post">
                    <button type="submit" class="btn btn-danger">Reset Attendance</button>
                </form>
            </div>

            <!-- Day Buttons -->
            <div class="text-center mb-4">
                <?php
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                foreach ($days as $day) {
                    echo '<button type="button" class="btn btn-primary day-button" data-day="' . $day . '">' . $day . '</button> ';
                }
                ?>
            </div>

            <!-- Attendance Table -->
            <div id="attendance-table">
                <div class="alert alert-info text-center" role="alert">
                    Select a day to view attendance records.
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.day-button').on('click', function() {
                    var day = $(this).data('day');

                    $.ajax({
                        url: 'fetch_attendance.php',
                        type: 'GET',
                        data: {
                            day: day
                        },
                        success: function(response) {
                            $('#attendance-table').html(response);
                        }
                    });
                });
            });
        </script>
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