<?php
  session_start();

  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
      header("location: login.php");
      exit;
  }
  
  require_once "config.php";

  $user_id = $_SESSION['id'];

  mysqli_query($link, "DELETE FROM users WHERE id = $user_id");

  if (file_exists('images/' . $_SESSION['username'] . '.jpg')) {
      $destination_file = 'images/' . $_SESSION['username'] . '.jpg';
      if (copy('deleted.jpg', $destination_file)) {
        //echo "image has been changed";
      } else {
        //echo "image wasnt changed";
      }
  }

  $text_message = "#".$_SESSION['username']."{ background: #2d2d2d }
  ";
          
  file_put_contents("username.css", $text_message, FILE_APPEND | LOCK_EX);
  
  
  session_destroy();

  header("location: login.php");
?>