<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/dbconnection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Instructor Sections</title>
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

    <!-- Left Panel -->
    <?php include_once('includes/sidebar.php'); ?>

    <div id="right-panel" class="right-panel">
        <!-- Header-->
        <?php include_once('includes/header.php'); ?>

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Sections</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li class="active">Sections</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>





































        <?php
        // Establish a database connection
        $dbh = new PDO('mysql:host=localhost;dbname=att_checking', 'root', '');

        // Handle form submissions
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['section_name']) && !isset($_POST['section_id'])) {
                // Add section
                $sectionName = $_POST['section_name'];
                $query = "INSERT INTO sections (section_name) VALUES (:sectionName)";
                $stmt = $dbh->prepare($query);
                $stmt->bindParam(':sectionName', $sectionName);

                if ($stmt->execute()) {
                    echo 'Section added successfully!';
                } else {
                    echo 'Error adding section.';
                }
            } elseif (isset($_POST['delete_section_id'])) {
                // Delete section
                $sectionId = intval($_POST['delete_section_id']);
                $query = "SELECT section_name FROM sections WHERE section_id = :sectionId";
                $stmt = $dbh->prepare($query);
                $stmt->bindParam(':sectionId', $sectionId, PDO::PARAM_INT);
                $stmt->execute();
                $section = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($section) {
                    $query = "DELETE FROM sections WHERE section_id = :sectionId";
                    $stmt = $dbh->prepare($query);
                    $stmt->bindParam(':sectionId', $sectionId, PDO::PARAM_INT);

                    if ($stmt->execute()) {
                        echo "<script>alert('Section \"{$section['section_name']}\" deleted successfully!');</script>";
                    } else {
                        echo "<script>alert('Error deleting section.');</script>";
                    }
                } else {
                    echo "<script>alert('Section not found.');</script>";
                }
            } elseif (isset($_POST['section_id']) && isset($_POST['section_name'])) {
                // Edit section
                $sectionId = intval($_POST['section_id']);
                $sectionName = $_POST['section_name'];
                $query = "UPDATE sections SET section_name = :sectionName WHERE section_id = :sectionId";
                $stmt = $dbh->prepare($query);
                $stmt->bindParam(':sectionName', $sectionName);
                $stmt->bindParam(':sectionId', $sectionId, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    echo "<script>alert('Section updated successfully!');</script>";
                } else {
                    echo "<script>alert('Error updating section.');</script>";
                }
            }
        }
        ?>
        <script>
            // Function to hide the success message after 3 seconds
            function hideSuccessMessage() {
                setTimeout(function() {
                    var message = document.getElementById('success-message');
                    if (message) {
                        message.style.display = 'none';
                    }
                }, 1000); // 3000 milliseconds = 3 seconds
            }
        </script>

        <body onload="hideSuccessMessage()">
            <?php
            if (isset($_SESSION['success_message'])) {
                echo '<p id="success-message" style="color: blue; text-align: center;">' . $_SESSION['success_message'] . '</p>';
                unset($_SESSION['success_message']);
            }
            ?>
        </body>


        <div class="content mt-3">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Sections</strong>
                    </div>
                    <div class="card-body">
                        <!-- Buttons to add section and student -->
                        <div style="margin-bottom: 20px;">
                            <button style="background-color: #007bff; border: none; color: white; padding: 10px 20px; border-radius: 5px; box-shadow: 0 4px 6px rgba(0, 123, 255, 0.4); cursor: pointer; margin-right: 10px;" data-toggle="modal" data-target="#addSectionModal">Add Section</button>
                            <button style="background-color: #6c757d; border: none; color: white; padding: 10px 20px; border-radius: 5px; box-shadow: 0 4px 6px rgba(108, 117, 125, 0.4); cursor: pointer;" data-toggle="modal" data-target="#addStudentModal">Add Student</button>
                            <a href="attendance.php" style="background-color: green; border: none; color: white; padding: 10px 20px; border-radius: 5px; box-shadow: 0 4px 6px rgba(108, 117, 125, 0.4); cursor: pointer; text-decoration: none;">Attendance</a>

                        </div>
                        <div id="sections-container">
                            <?php
                            $query = "SELECT * FROM sections";
                            $stmt = $dbh->prepare($query);
                            $stmt->execute();
                            $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if (count($sections) > 0) {
                                foreach ($sections as $row) {
                                    echo '<div id="section-' . $row['section_id'] . '" style="border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin-bottom: 20px; padding: 20px; background: linear-gradient(145deg, #f0f0f0, #ffffff);">';
                                    echo '<h4 style="color: #333; font-weight: bold; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">' . htmlspecialchars($row['section_name']) . '</h4>';
                                    echo '<div style="display: flex; gap: 10px; margin-top: 10px;">';
                                    echo '<a href="view_students.php?section_id=' . $row['section_id'] . '" style="background-color: #007bff; border: none; color: white; padding: 10px 20px; border-radius: 5px; box-shadow: 0 4px 6px rgba(0, 123, 255, 0.4); cursor: pointer; text-decoration: none;">View Students</a>';
                                    echo '<button onclick="editSection(' . $row['section_id'] . ', \'' . htmlspecialchars($row['section_name']) . '\')" data-toggle="modal" data-target="#editSectionModal" style="background-color: gray; border: none; color: white; padding: 10px 20px; border-radius: 5px; box-shadow: 0 4px 6px rgba(255, 193, 7, 0.4); cursor: pointer;">Edit Section</button>';
                                    echo '<form method="post" style="display:inline;" onsubmit="return confirmDelete(this);">
                                            <input type="hidden" name="delete_section_id" value="' . $row['section_id'] . '">
                                            <button type="submit" style="background-color: #dc3545; border: none; color: white; padding: 10px 20px; border-radius: 5px; box-shadow: 0 4px 6px rgba(220, 53, 69, 0.4); cursor: pointer;">Delete Section</button>
                                        </form>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<div class="alert alert-warning" role="alert" style="margin-top: 20px;">No sections found.</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



        <!-- Add Section Modal -->
        <div class="modal fade" id="addSectionModal" tabindex="-1" role="dialog" aria-labelledby="addSectionModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="post">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addSectionModalLabel">Add Section</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="section_name">Section Name</label>
                                <input type="text" class="form-control" id="section_name" name="section_name" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Section Modal -->
        <div class="modal fade" id="editSectionModal" tabindex="-1" role="dialog" aria-labelledby="editSectionModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="post">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editSectionModalLabel">Edit Section</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="edit_section_name">Section Name</label>
                                <input type="text" class="form-control" id="edit_section_name" name="section_name" required>
                                <input type="hidden" id="edit_section_id" name="section_id">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>





























        <?php
        if (isset($_GET['message']) && $_GET['message'] == 'success') {
            echo "<div class='alert alert-success'>Student added successfully ✅</div>";
        }

        ?>

        <!-- View Students Modal -->
        <!-- <div class="modal fade" id="viewStudentsModal" tabindex="-1" role="dialog" aria-labelledby="viewStudentsModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewStudentsModalLabel">Students in Section: <span id="sectionName"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div> -->
                    <!-- <div class="modal-body" id="studentsList"> -->
                        <!-- Student list will be loaded here -->
                        <!-- <form id="attendanceForm"> -->
                            <!-- Dynamically generated student checkboxes will be inserted here -->
                        <!-- </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal">Save Attendance</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div> -->


        <script>
            function viewStudents(sectionId, sectionName) {
                document.getElementById('sectionName').textContent = sectionName; // Update the modal title with the section name
                fetch(`view_students.php?section_id=${sectionId}`)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('studentsList').innerHTML = data; // Update the student list content
                    })
                    .catch(error => {
                        console.error('Error fetching student data:', error);
                    });
            }
        </script>













        <script>
            function confirmDelete(form) {
                return confirm('Are you sure you want to delete this section?');
            }

            function editSection(sectionId, sectionName) {
                document.getElementById('edit_section_id').value = sectionId;
                document.getElementById('edit_section_name').value = sectionName;
            }

            function viewStudents(sectionId) {
                fetch(`view_students.php?section_id=${sectionId}`)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('studentsList').innerHTML = data;
                    });
            }
        </script>


















        <style>
            .modal-header {
                background-color: #007bff;
                color: white;
            }

            .modal-footer button {
                border-radius: 0;
            }

            .list-group-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .btn {
                border-radius: 0;
            }
        </style>


        <!-- Modal for Editing Sections -->
        <div class="modal" id="editSectionModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Section</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editSectionForm" action="edit_section.php" method="POST">
                            <input type="hidden" id="editSectionId" name="section_id">
                            <div class="form-group">
                                <label for="editSectionName">Section Name:</label>
                                <input type="text" class="form-control" id="editSectionName" name="section_name">
                            </div>
                            <button type="submit" class="btn btn-primary">Edit Section</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Add Student Modal -->
        <div class="modal fade" id="addStudentModal" tabindex="-1" role="dialog" aria-labelledby="addStudentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="add_student.php" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addStudentModalLabel">Add Student</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="student_name">Student Name</label>
                                <input type="text" class="form-control" id="student_name" name="student_name" required>
                            </div>
                            <div class="form-group">
                                <label for="section_id">Section</label>
                                <select class="form-control" id="section_id" name="section_id" required>
                                    <option value="" disabled selected>Select Section</option>
                                    <?php
                                    $query = "SELECT * FROM sections";
                                    $stmt = $dbh->prepare($query);
                                    $stmt->execute();
                                    $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    foreach ($sections as $section) {
                                        echo '<option value="' . $section['section_id'] . '">' . htmlspecialchars($section['section_name']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Student</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Modal for Editing Students -->
        <div class="modal" id="editStudentModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Student</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editStudentForm" action="edit_student.php" method="POST">
                            <input type="hidden" id="editStudentId" name="student_id">
                            <div class="form-group">
                                <label for="editStudentName">Student Name:</label>
                                <input type="text" class="form-control" id="editStudentName" name="student_name">
                            </div>
                            <div class="form-group">
                                <label for="editSectionId">Select Section:</label>
                                <select class="form-control" id="editSectionId" name="section_id">
                                    <?php
                                    // Fetch sections for the dropdown
                                    $query = "SELECT * FROM sections";
                                    $stmt = $dbh->prepare($query);
                                    $stmt->execute();
                                    $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    if (count($sections) > 0) {
                                        foreach ($sections as $row) {
                                            echo '<option value="' . $row['section_id'] . '">' . htmlspecialchars($row['section_name']) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Edit Student</button>
                        </form>
                    </div>
                </div>
            </div>
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
    </div>

</body>

</html>