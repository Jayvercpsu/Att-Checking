<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $newpassword = md5($_POST['newpassword']);
    $sql = "SELECT Email FROM tbladmin WHERE Email=:email and MobileNumber=:mobile";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        $con = "update tbladmin set Password=:newpassword where Email=:email and MobileNumber=:mobile";
        $chngpwd1 = $dbh->prepare($con);
        $chngpwd1->bindParam(':email', $email, PDO::PARAM_STR);
        $chngpwd1->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
        $chngpwd1->execute();
        echo "<script>alert('Your Password succesfully changed');</script>";
    } else {
        echo "<script>alert('Email id or Mobile no is invalid');</script>";
    }
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructors Forgot Password</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
    <!-- Themify Icons -->
    <link rel="stylesheet" href="vendors/themify-icons/css/themify-icons.css">
    <!-- Flag Icon -->
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <!-- SelectFX -->
    <link rel="stylesheet" href="vendors/selectFX/css/cs-skin-elastic.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- WOW.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>
    <style>
        body {
            background-image: url('../images/cool-background.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .login-container {
            max-width: 400px;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .login-logo img {
            width: 100px;
            height: auto;
        }

        .login-logo h3 {
            font-size: 24px;
            color: black;
        }

        .btn-success {
            background-color: green;
            border-color: green;
        }

        .btn-success:hover {
            background-color: darkgreen;
            border-color: darkgreen;
        }

        .form-group label {
            color: black;
        }
    </style>

    <script type="text/javascript">
        function valid() {
            if (document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
                alert("New Password and Confirm Password Field do not match  !!");
                document.chngpwd.confirmpassword.focus();
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
    <div class="container">
        <div class="login-container wow fadeInUp" data-wow-delay="0.3s">
            <div class="login-logo text-center mb-4">
                <img src="../images/logo.png" alt="Logo">
                <h3>ClassConnect: Attendance Checker</h3>
                <hr style="border-color: red;">
            </div>
            <div class="login-form">
                <form action="" method="post" name="chngpwd" onSubmit="return valid();">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" required name="email">
                    </div>
                    <div class="form-group">
                        <label for="mobile">Mobile Number</label>
                        <input type="text" class="form-control" id="mobile" required name="mobile">
                    </div>
                    <div class="form-group">
                        <label for="newpassword">Password</label>
                        <input type="password" class="form-control" id="newpassword" required name="newpassword">
                    </div>
                    <div class="form-group">
                        <label for="confirmpassword">Confirm Password</label>
                        <input type="password" class="form-control" id="confirmpassword" required name="confirmpassword">
                    </div>
                    <div class="form-group d-flex justify-content-between">
                        <a style="color: blue;" href="index.php">Sign in</a>
                    </div>
                    <button type="submit" class="btn btn-success btn-block" name="submit">Reset</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>