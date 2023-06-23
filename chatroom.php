<?php
require_once "config.php";

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

if (!file_exists('images/' . $_SESSION['username'] . '.jpg')) {
    $destination_file = 'images/' . $_SESSION['username'] . '.jpg';
    if (copy('default.jpg', $destination_file)) {
        // echo "image has been changed";
    } else {
        // echo "image wasn't changed";
    }
}

$sql = "UPDATE users SET status='online' WHERE id=" . $_SESSION['id'] . ";";
$result = $link->query($sql);

// Handle room creation
if (isset($_POST['create_room'])) {
    $room_id = generateRoomId(6, $link);
    $room_title = $_POST['room_title'];
    $creator_username = $_SESSION['username'];

    $sql = "SELECT COUNT(*) as room_count FROM rooms WHERE creator_username = ?;";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("s", $creator_username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $roomCount = $row['room_count'];

    if ($roomCount >= 3) {

        $error_message = "You have reached the maximum number of chat rooms.";

    } else {
        $sql = "INSERT INTO rooms (room_id, title, creator_username) VALUES (?, ?, ?);";
        $stmt = $link->prepare($sql);
        $stmt->bind_param("sss", $room_id, $room_title, $creator_username);
        $stmt->execute();
    
        $sql = "INSERT INTO participants (room_id, participant) VALUES (?, ?);";
        $stmt = $link->prepare($sql);
        $stmt->bind_param("ss", $room_id, $creator_username);
        $stmt->execute();
    
        $log_directory = 'logs/';
        $log_filename = "log-" . $room_id . ".html";
        $log_file_path = $log_directory . $log_filename;
    
        if (!file_exists($log_directory)) {
            mkdir($log_directory, 0755, true);
        }
    
        $log_file = fopen($log_file_path, "w");
        fclose($log_file);
    
        $welcomeMessage = "Room <b>" . $room_title . "</b> has been created by <b>" . $creator_username . "</b> !";
        $creation_message = "<div class='msgln msg-center'> <img class='small-pp' id='" . $creator_username . "-pic' src='images/" . $creator_username . ".jpg'><span class='user-name' id='" . $creator_username . "'>" . $welcomeMessage . "</span><br></div>\n";
     
        file_put_contents($log_file_path, "<hr><center><h5>" . date('l, F j, Y') . "</h5></center>\n", FILE_APPEND | LOCK_EX);
        file_put_contents($log_file_path, $creation_message, FILE_APPEND | LOCK_EX);
    
        $last_date_file_path = "dates/last-date-" . $room_id . ".txt";
        file_put_contents($last_date_file_path, date('Y-m-d'));
    
        header("Location: welcome.php?id=$room_id");
        exit;
    }        
}

// Handle room joining
if (isset($_POST['join_room'])) {
    $room_id = $_POST['room_id'];

    $sql = "SELECT * FROM rooms WHERE room_id = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("s", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $sql = "SELECT * FROM participants WHERE room_id = ? AND participant = ?";
        $stmt = $link->prepare($sql);
        $stmt->bind_param("ss", $room_id, $_SESSION['username']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $error_message = "You are already a participant in this room.";
        } else {

            $sql = "INSERT INTO participants (room_id, participant) VALUES (?, ?);";
            $stmt = $link->prepare($sql);
            $stmt->bind_param("ss", $room_id, $_SESSION['username']);
            $stmt->execute();

            $log_file = "logs/log-" . $room_id . ".html";

            session_start();
            $welcomeMessages = array(
                "<b>" . $_SESSION['username'] . "</b> has joined the chat!",
                "The legendary <b>" . $_SESSION['username'] . "</b> has appeared!",
                "Welcome, <b>" . $_SESSION['username'] . "</b>. Enjoy your stay!",
                "This <b>" . $_SESSION['username'] . "</b> is about to explode!",
                "<b>" . $_SESSION['username'] . "</b> came to the party! Have fun!",
                "A strange <b>" . $_SESSION['username'] . "</b> landed in the chat!",
                "<b>" . $_SESSION['username'] . "</b> has been summoned in the chat!",
                "Are you ready for this wild <b>" . $_SESSION['username'] ."</b> ?",
                "And goodbye to anyone standing in <b>" . $_SESSION['username'] . "</b>'s way!",
                "Everybody welcome <b>" . $_SESSION['username'] . "</b> !"
            );
            $randomIndex = array_rand($welcomeMessages);
            $welcomeMessage = $welcomeMessages[$randomIndex];
            $registration_message = "<div class='msgln msg-center'> <img class='small-pp' src='images/" . $_SESSION['username'] . ".jpg'> <span class='user-name' id='" . $_SESSION['username'] . "'>" . $welcomeMessage . "</span><br></div>\n";
            file_put_contents($log_file, $registration_message, FILE_APPEND | LOCK_EX);

            header("Location: welcome.php?id=$room_id");
            exit;
        }
    } else {
        $error_message = "The room ID you entered does not exist.";
    }
}

function generateRoomId($length, $link)
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $roomId = '';
    $charactersLength = strlen($characters);
    for ($i = 0; $i < $length; $i++) {
        $roomId .= $characters[rand(0, $charactersLength - 1)];
    }

    // Check if the generated room ID exists in the database
    $sql = "SELECT COUNT(*) as count FROM rooms WHERE room_id = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("s", $roomId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $roomCount = $row['count'];

    // If the room ID already exists, generate a new one recursively
    if ($roomCount > 0) {
        return generateRoomId($length, $link);
    }

    return $roomId;
}
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create or join a chatroom</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="username.css?<?php echo time(); ?>">
        <link rel="stylesheet" href="objectfit.css?<?php echo time(); ?>">
        <style>
            .contain{
                padding: 20px;
                color: #DCDDDE;
                background:  #36393F;
                backdrop-filter: blur(8.8px);
                -webkit-backdrop-filter: blur(8.8px);
                position: absolute;
                left: 280px;
                width: calc(100vw - 288px);
                min-height: 100vh;
            }
            .contain a{
                color: #DCDDDE;
            }
            form {
                display: flex;
                justify-content: space-between;
            }
            h4{
                margin: 0 10px;
            }
            .msgln{
                background: transparent;
                border: 2px transparent solid;
                padding: 5px;
                border-radius: 15px;
                transition: 100ms ease;
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                place-items: center;
                margin: 2px;
            }
            .msgln:hover{
                border: 2px #2c2f34 solid;
                background: #585b60;
            }
            hr{
                margin: 2px;
            }
            .error-message {
                color: #721c24;
                background-color: #f8d7da;
                padding: 0.75rem 1.25rem;
                margin: 1rem 0;
                border: 1px solid #f5c6cb;
                border-radius: 0.25rem;
            }
            a{
                text-decoration: none !important; 
            }
            h2,h3{ margin-left: 5px }
            @media only screen and (max-width: 1000px) {
                .contain{
                    left: 180px;
                    width: calc(100vw - 188px);
                }

            }
            @media only screen and (max-width: 500px) {
                .contain{
                    left: 90px;
                    width: calc(100vw - 98px);
                }

            }
        </style>
    </head>
    <body>
        <div class="side-bar" id="side">
            <br>
            <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h1>
            <img class="pp" id="<?php echo htmlspecialchars($_SESSION["username"]); ?>-pic" style="width: 100%" src="images/<?php echo htmlspecialchars($_SESSION["username"]); ?>.jpg" onerror="this.onerror=null; this.src='default.jpg'">
            <br>
            <br>
            <br>
            <br>
            <a href="settings.php" class="btn btn-warning">Account Settings</a>
            <br>
            <br>
            <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
        </div>

        <div class="contain">
            <h2>Create or join a room</h2>

            <br>
            <br>

            <h3>Create a Room</h3>
            <form name="createRoom" action="" method="POST">
                <input type="text" name="room_title" class="form-control" placeholder="Enter Room Title" required maxlength="16">
                <input type="submit" name="create_room" class="btn btn-primarry" value="Create Room">
            </form>        
            
            <h3>Join a Room</h3>
            <form name="joinRoom" action="" method="POST">
                <input type="text" name="room_id" class="form-control" placeholder="Enter Room ID" required>
                <input type="submit" name="join_room" class="btn btn-primarry" value="Join Room">
            </form>
            
            <?php
                if (isset($error_message)) {
                    echo "<p class='error-message'>$error_message</p>";
                }
            ?>

            <br>
            <br>

            <h3>Available rooms</h3>
            <?php
                $currentUsername = $_SESSION['username'];
                        
                $sql = "SELECT rooms.room_id, rooms.title, rooms.creator_username
                        FROM rooms
                        JOIN participants ON rooms.room_id = participants.room_id
                        WHERE participants.participant = '$currentUsername'";
            
                $result = $link->query($sql);
                        
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<a href='welcome.php?id=" . $row['room_id'] . "'>";
                        echo "<div class='msgln'>";
                        echo "<div style='display: inline-flex; align-items: center;'>";
                        echo "<img class='small-pp' id='" . $row['creator_username'] . "-pic' src='images/" . $row['creator_username'] . ".jpg'>";
                        echo "<b class='user-name' id='" . $row['creator_username'] . "'>" . $row['creator_username'] . "</b>  </div>";
                        echo "<h4>" . $row['title'] . "</h4>";
                        echo "<b class='user-name'>" . $row['room_id'] . "</b>";
                        echo "</div>";
                        echo "</a>";
                        echo "<hr>";
                    }
                } else {
                    echo "<p style='text-align:center;margin-top:5em;'>No available rooms.</p>";
                }
            ?>

        </div>

        <script type="text/javascript" src="scripts.js"></script>
    </body>
</html>
