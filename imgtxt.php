<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

$roomId = isset($_POST['room_id']) ? $_POST['room_id'] : '';
$image = $_FILES['file'];
$success = move_uploaded_file($image['tmp_name'], 'texts/' . $image['name']);

if ($success) {
    echo 'The file was uploaded successfully';

    $text = '';

    $current_date = date('Y-m-d');
    $last_date = file_exists("dates/last-date-" . $roomId . ".txt") ? file_get_contents("dates/last-date-" . $roomId . ".txt") : '';

    if ($last_date !== $current_date) {
        $last_date = $current_date;
        file_put_contents("dates/last-date-" . $roomId . ".txt", $last_date);
        $text .= "<hr><center><h5>" . date('l, F j, Y') . "</h5></center>
";
    }

    $text .= "<div class='msgln'><span class='chat-time'>" . date("G:i") . "</span> <img class='small-pp' id='" . $_SESSION['username'] . "-pic' src='images/" . $_SESSION['username'] . ".jpg'> <b class='user-name' id='" . $_SESSION['username'] . "'>" . $_SESSION['username'] . "</b> <img id='txtimg' src='texts/" . $image['name'] . "'></div>";

    if ($image['name'] != "" && $roomId != "") {
        $log_filename = "logs/log-" . $roomId . ".html";

        file_put_contents($log_filename, $text, FILE_APPEND | LOCK_EX);
    }
} else {
    echo 'There was an error uploading the file';
}
?>
