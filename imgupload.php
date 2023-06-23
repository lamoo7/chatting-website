<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $objectFitStyle = $_POST["objectFit"];

    $username = $_SESSION["username"];

    $cssRule = "#{$username}-pic { object-fit: {$objectFitStyle}; }
";

    $filename = "objectfit.css";
    file_put_contents($filename, $cssRule, FILE_APPEND);

    $filename = $_FILES['file']['name'];
    $location = "images/" . $filename;
    $temp = explode(".", $_FILES["file"]["name"]);
    $newfilename = $username . '.jpg';
    move_uploaded_file($_FILES["file"]["tmp_name"], "images/" . $newfilename);

    header("location: settings.php");
    exit;
}
?>
