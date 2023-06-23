<?php
session_start();

require_once 'config.php';

if (isset($_GET['id'])) {
    $room_id = $_GET['id'];

    $username = $_SESSION['username'];
    $stmt = $link->prepare("SELECT room_id FROM participants WHERE room_id = ? AND participant = ?");
    $stmt->bind_param("ss", $room_id, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $_SESSION['room_id'] = $room_id;
        
        header("Location: welcome.php");
        exit;
    }
}

header("Location: error.php");
exit;
?>
