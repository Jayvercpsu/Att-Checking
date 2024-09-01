<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

//code for Signp
if (isset($_POST['submit'])) {
    $fname = $_POST['fname'];
    $emailid = $_POST['emailid'];
    $phoneno = $_POST['mobileno'];
    $password = md5($_POST['password']);
    //Checking if emailor mobile already registered
    $query = $dbh->prepare("SELECT ID FROM tblteacher WHERE Email=:emailid and MobileNumber=:phoneno");
    $query->bindParam(':emailid', $emailid, PDO::PARAM_STR);
    $query->bindParam(':phoneno', $phoneno, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        echo "<script>alert('Email id or Mobile no already registered with another account.');</script>";
        echo "<script type='text/javascript'> document.location ='signup.php'; </script>";
    } else {

        $sql = "insert into tblteacher(Name,Email,MobileNumber,password)values(:fname,:emailid,:phoneno,:password)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':fname', $fname, PDO::PARAM_STR);
        $query->bindParam(':emailid', $emailid, PDO::PARAM_STR);
        $query->bindParam(':phoneno', $phoneno, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();
        $LastInsertId = $dbh->lastInsertId();
        if ($LastInsertId > 0) {
            echo '<script>alert("Registered succesfully")</script>';
            echo "<script>window.location.href ='index.php'</script>";
        } else {
            echo '<script>alert("Something Went Wrong. Please try again")</script>';
        }
    }
}

?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Registration</title>
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
    <link rel="stylesheet" href="assets/css/style.css">
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
            background-image: url('images/cool-background.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: 'Open Sans', sans-serif;
        }

        .login-container {
            max-width: 400px;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .login-logo h3 {
            color: #333;
            /* Adjust the color to match your design */
            font-weight: bold;
            /* Optionally adjust the font weight */
        }

        .login-logo hr {
            border-color: red;
        }

        .login-form a {
            color: #007bff;
            /* Adjust link color */
        }

        .login-logo img {
            width: 100px;
            height: auto;
        }

        .btn-success {
            background-color: green;
            /* Adjust button background color */
            border-color: green;
            /* Adjust button border color */
        }

        .btn-success:hover {
            background-color: darkgreen;
            /* Adjust button hover background color */
            border-color: darkgreen;
            /* Adjust button hover border color */
        }

        .form-group label {
            color: black;
        }

        .login-logo h3 {
            font-size: 24px;
            color: black;
        }
    </style>
</head>

<body class="bg-dark">
    <div class="container">
        <div class="login-container wow fadeInUp" data-wow-delay="0.3s">
            <div class="login-logo text-center mb-4">
                <img src="images/logo.png" alt="Logo">
                <h3>Instructor Registration</h3>
                <hr>
            </div>
            <div class="login-form">
                <form action="" method="post" name="login">
                    <div class="form-group">
                        <label for="fname">Instructor Full Name</label>
                        <input type="text" class="form-control" id="fname" placeholder="Full Name" required name="fname">
                    </div>
                    <div class="form-group">
                        <label for="emailid">Email Id</label>
                        <input type="email" class="form-control" id="emailid" placeholder="Email id" required name="emailid">
                    </div>
                    <div class="form-group">
                        <label for="mobileno">Mobile Number</label>
                        <input type="text" class="form-control" id="mobileno" placeholder="Mobile Number" maxlength="10" pattern="[0-9]{10}" title="10 numeric characters only" required name="mobileno">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Password" required name="password">
                    </div>
                    <button type="submit" class="btn btn-success btn-block" name="submit">Sign Up</button>
                    <div class="form-group mt-3">
                        <label class="d-block text-center">
                            <a href="index.php">Already Registered? Login Here</a>
                        </label>
                    </div>
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