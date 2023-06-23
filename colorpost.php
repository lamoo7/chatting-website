<?php
    session_start();
    if(isset($_SESSION['username'])){
        $color = $_POST['color'];
         
        $text_message = "#".$_SESSION['username']."{ background:".stripslashes(htmlspecialchars($color))." }
";
        
        file_put_contents("username.css", $text_message, FILE_APPEND | LOCK_EX);
    
    }
    header("location: login.php");
?>