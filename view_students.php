<?php
session_start();
include('includes/dbconnection.php');

// Get section_id from the query string
$section_id = isset($_GET['section_id']) ? $_GET['section_id'] : 0;

// Fetch section name
$section_query = "SELECT section_name FROM sections WHERE section_id = :section_id";
$section_stmt = $dbh->prepare($section_query);
$section_stmt->bindParam(':section_id', $section_id, PDO::PARAM_INT);
$section_stmt->execute();
$section_name = $section_stmt->fetchColumn();

// Fetch students
$query = "SELECT * FROM students WHERE section_id = :section_id ORDER BY student_id ASC";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':section_id', $section_id, PDO::PARAM_INT);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Instructor View Students</title>
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
                        <h1>View Students</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li><a href="sections.php">Sections</a></li>
                            <li class="active">View Students</li>
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
        Students From: <?php echo htmlspecialchars($section_name); ?>
    </div>
    <?php
    if (count($students) > 0) {
        echo '<form method="post" action="submit_attendance.php">';
        echo '<input type="hidden" name="section_id" value="' . htmlspecialchars($section_id) . '">';
        echo ' <label for="attendance_date">Attendance Date:</label>';
        echo ' <input type="date" id="attendance_date" name="attendance_date" required>     ';
        $counter = 1; // Initialize counter
        foreach ($students as $student) {
            echo '<div class="form-group">';
            echo '<label class="font-weight-bold">' . $counter . '. ' . htmlspecialchars($student['student_name']) . '</label>';
            echo '<div>';
            echo '<div class="form-check form-check-inline">';
            echo '<input class="form-check-input" type="radio" name="attendance[' . $student['student_id'] . ']" id="present_' . $student['student_id'] . '" value="present" required>';
            echo '<label class="form-check-label" for="present_' . $student['student_id'] . '">Present</label>';
            echo '</div>';
            echo '<div class="form-check form-check-inline">';
            echo '<input class="form-check-input" type="radio" name="attendance[' . $student['student_id'] . ']" id="absent_' . $student['student_id'] . '" value="absent" required>';
            echo '<label class="form-check-label" for="absent_' . $student['student_id'] . '">Absent</label>';
            echo '</div>';
            echo '<div class="form-check form-check-inline">';
            echo '<input class="form-check-input" type="radio" name="attendance[' . $student['student_id'] . ']" id="excused_' . $student['student_id'] . '" value="excused" required>';
            echo '<label class="form-check-label" for="excused_' . $student['student_id'] . '">Excused</label>';
            echo '</div>';
            echo '<div class="form-check form-check-inline">';
            echo '<input class="form-check-input" type="radio" name="attendance[' . $student['student_id'] . ']" id="late_' . $student['student_id'] . '" value="late" required>';
            echo '<label class="form-check-label" for="late_' . $student['student_id'] . '">Late</label>';
            echo '</div>';
            echo '<button type="button" class="btn btn-danger btn-sm delete-button" data-id="' . $student['student_id'] . '" style="padding: 10px;">Delete</button>';
            echo '</div>'; // Close form-group
            echo '</div>';
            $counter++; // Increment counter
        }
        echo '<div class="form-group text-center">';
        echo '<button type="submit" class="btn btn-primary">Submit Attendance</button>';
        echo '</div>';
        echo '</form>';
    } else {
        echo '<div class="alert alert-warning text-center" role="alert">No students found in this section.</div>';
    }
    ?>
</div>

<?php
if (isset($_GET['message']) && $_GET['message'] == 'success') {
    echo "<div class='alert alert-success'>Student added successfully ✅</div>";
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.delete-button').on('click', function() {
            var studentId = $(this).data('id');
            var sectionId = <?php echo $section_id; ?>;

            if (confirm('Are you sure you want to delete this student?')) {
                $.ajax({
                    url: 'delete_student.php',
                    type: 'POST',
                    data: {
                        student_id: studentId
                    },
                    success: function(response) {
                        if (response == 'success') {
                            alert('Student deleted successfully');
                            window.location.href = 'view_students.php?section_id=' + sectionId;
                        } else {
                            alert('Failed to delete student');
                        }
                    }
                });
            }
        });
    });
</script>


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
    </div>

</body>

</html>