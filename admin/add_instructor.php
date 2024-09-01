<?php
session_start();
include('includes/dbconnection.php');

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header('location:index.php');
} else {
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $password = md5($_POST['password']);

        $sql = "INSERT INTO tblteacher (Name, Email, MobileNumber, Password) VALUES (:name, :email, :mobile, :password)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();

        echo "<script>alert('Instructor added successfully');</script>";
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Instructor</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Add Instructor</h2>
        <form method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="mobile">Mobile Number</label>
                <input type="text" class="form-control" id="mobile" name="mobile" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add">Add Instructor</button>
        </form>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>
