<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['login'])) 
  {
    $username=$_POST['username'];
    $password=md5($_POST['password']);
    $sql ="SELECT ID,Name FROM tblteacher WHERE (Email=:username || MobileNumber=:username) and password=:password";
    $query=$dbh->prepare($sql);
    $query-> bindParam(':username', $username, PDO::PARAM_STR);
$query-> bindParam(':password', $password, PDO::PARAM_STR);
    $query-> execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    if($query->rowCount() > 0)
{
foreach ($results as $result) {
$_SESSION['trmsuid']=$result->ID;
$_SESSION['trmstname']=$result->Name;
}

echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
} else{
echo "<script>alert('Invalid Details');</script>";
}
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClassConnect: Attendance Checker</title>

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
        }

        .login-container {
            max-width: 400px;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .login-form a {
            color: #007bff;
        }

        .login-logo img {
            width: 100px;
            height: auto;
        }

        .login-logo h3 {
            font-size: 24px;
            color: black;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-container wow fadeInUp" data-wow-delay="0.3s">
            <div class="login-logo text-center mb-4">
                <img src="images/logo.png" alt="Logo">
                <h3>ClassConnect: Attendance Checker</h3> Instructor Login
                <hr style="border-color: red;">
            </div>
            <div class="login-form">
                <form action="" method="post" name="login">
                    <div class="form-group">
                        <label for="username">Email Id / Mobile Number</label>
                        <input type="text" class="form-control" id="username" placeholder="Email id / Mobile Number" required name="username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Password" required name="password">
                    </div>
                    <div class="form-group d-flex justify-content-between">
                        <a href="forgot-password.php">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn btn-success btn-block" name="login">Sign in</button>
                    <hr>
                    <p class="text-center">Not Registered Yet? <a href="signup.php">Signup Here</a></p>
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