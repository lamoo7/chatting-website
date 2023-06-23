<?php
    
    require_once "config.php";

    session_start();

    $sql = "UPDATE users SET status='offline' WHERE id=" . $_SESSION['id'] . ";";
    $result = $link->query($sql);
    
    $_SESSION = array();
    
    session_destroy();
    
    header("location: login.php");
    exit;

?>