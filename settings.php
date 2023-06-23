<?php

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Change Your Account Settings</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="wrapper">
            <h2>Change Your Account Settings</h2>
            <br>
            <br>
            <center>
            <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
            <br>
            <br>
            <a href="socialmedia.php" class="btn btn-warning">Edit Your Social Links</a>
            <br>
            <br>
            <a href="reset-pic.php" class="btn btn-warning">Change Your Profile Picture</a>
            <br>
            <br>
            <a href="reset-color.php" class="btn btn-warning">Change Your Name Color</a>
            <br>
            <br>
            <a href="deletionterms.php" class="btn btn-danger" style="color: white">Delete Your Account</a>
            <br>
            <br>
            <a class="btn btn-link ml-2" href="chatroom.php">Cancel</a>
            </center>
        </div>
    </body>
</html>