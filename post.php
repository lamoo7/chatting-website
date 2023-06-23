<?php
session_start();

if (isset($_SESSION['username'])) {
    $text = $_POST['text'];

    $roomId = isset($_GET['id']) ? $_GET['id'] : '';
    $lastDateFile = "dates/last-date-" . $roomId . ".txt";

    $lastDate = file_exists($lastDateFile) ? file_get_contents($lastDateFile) : '';

    $currentDate = date('Y-m-d');
    if ($lastDate !== $currentDate) {
        $lastDate = $currentDate;
        file_put_contents($lastDateFile, $lastDate);
        $text_message = "<hr><center><h5>" . date('l, F j, Y') . "</h5></center>\n
";
    } else {
        $text_message = '';
    }

    if ($roomId) {
        $text_message .= "<div class='msgln'><span class='chat-time'>" . date("G:i") . "</span> <img id='" . $_SESSION['username'] . "-pic' class='small-pp' src='images/" . $_SESSION['username'] . ".jpg'> <b class='user-name' id='" . $_SESSION['username'] . "'>" . $_SESSION['username'] . "</b> " . stripslashes(htmlspecialchars($text)) . "</div>\n";

        if ($text != "") {
            $log_filename = "logs/log-" . $roomId . ".html";
            file_put_contents($log_filename, $text_message, FILE_APPEND | LOCK_EX);
        }
    }
}
?>
