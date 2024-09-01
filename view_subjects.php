<?php
session_start();
include('includes/dbconnection.php');

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'instructor') {
    header('location:index.php');
} else {
    $instructor_id = $_SESSION['trmsuid'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Subjects</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>My Subjects</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subject</th>
                    <th>Sections</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM tblsubject WHERE InstructorID=:instructor_id";
                $query = $dbh->prepare($sql);
                $query->bindParam(':instructor_id', $instructor_id, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $cnt = 1;
                if ($query->rowCount() > 0) {
                    foreach ($results as $result) {
                ?>
                <tr>
                    <td><?php echo htmlentities($cnt); ?></td>
                    <td><?php echo htmlentities($result->SubjectName); ?></td>
                    <td><a href="view_sections.php?subjectid=<?php echo htmlentities($result->ID); ?>">View Sections</a></td>
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
