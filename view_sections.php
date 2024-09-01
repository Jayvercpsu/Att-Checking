<?php
session_start();
include('includes/dbconnection.php');

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'instructor') {
    header('location:index.php');
} else {
    $subject_id = $_GET['subjectid'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Sections</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Sections for Subject ID: <?php echo htmlentities($subject_id); ?></h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Section</th>
                    <th>Students</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM tblsection WHERE SubjectID=:subject_id";
                $query = $dbh->prepare($sql);
                $query->bindParam(':subject_id', $subject_id, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $cnt = 1;
                if ($query->rowCount() > 0) {
                    foreach ($results as $result) {
                ?>
                <tr>
                    <td><?php echo htmlentities($cnt); ?></td>
                    <td><?php echo htmlentities($result->SectionName); ?></td>
                    <td><a href="view_students.php?sectionid=<?php echo htmlentities($result->ID); ?>">View Students</a></td>
                </tr>
                <?php $cnt = $cnt + 1;
                    }
                } ?>
            </tbody>
        </table>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>
