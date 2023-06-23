<?php

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";

$fb = $_POST['fb'];
$insta = $_POST['insta'];
$discord = $_POST['discord'];
$tiktok = $_POST['tiktok'];
$twitter = $_POST['twitter'];
$reddit = $_POST['reddit'];
$github = $_POST['github'];
$twitch = $_POST['twitch'];
$steam = $_POST['steam'];
$web = $_POST['web'];

$sql = "UPDATE users
        SET fb='" . $fb . "', insta='" . $insta . "', discord='" . $discord ."', tiktok='" . $tiktok ."', twitter='" . $twitter ."', reddit='" . $reddit ."', github='" . $github ."', twitch='" . $twitch ."', steam='" . $steam ."', web='" . $web ."'
        WHERE id=" . $_SESSION['id'] . ";";

$result = $link->query($sql);
if ($result){
    header('Location: chatroom.php');
} else {
    echo $result->error;
}