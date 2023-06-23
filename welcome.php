<?php
    require_once "config.php";
    //require_once "check_access.php";

    session_start();
    
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }
    
    if (!file_exists('images/' . $_SESSION['username'] . '.jpg')) {
        $destination_file = 'images/' . $_SESSION['username'] . '.jpg';
        if (copy('default.jpg', $destination_file)) {
          //echo "image has been changed";
        } else {
          //echo "image wasnt changed";
        }
      }

      $sql = "UPDATE users SET status='online' WHERE id=" . $_SESSION['id'] . ";";
      $result = $link->query($sql);
?>

 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="username.css?<?php echo time(); ?>">
        <link rel="stylesheet" href="objectfit.css?<?php echo time(); ?>">
        <style>
            body{ background: #2c2f34 }
            input[type=file] { display: none }
        </style>
    </head>
    <body>
        <div class="side-bar" id="side">
            <a href="chatroom.php">Back to menu</a>
            <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h1>
            <img class="pp"id="<?php echo htmlspecialchars($_SESSION["username"]); ?>-pic" src="images/<?php echo htmlspecialchars($_SESSION["username"]); ?>.jpg" onerror="this.onerror=null; this.src='default.jpg'">
            <br>
            <br>
            <br>
            <br>
            <a href="settings.php" class="btn btn-warning">Account Settings</a>
            <br>
            <br>
            <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
        </div>

        <div class="center-content" id="cc" >
            <div id="chat-title">
                <?php 
                    require_once "config.php";
                    
                    if ($link->connect_error) {
                        die("Connection failed: " . $link->connect_error);
                    }
                    if (isset($_GET['id'])) {
                        $room_id = $_GET['id'];
                    
                        $sql = "SELECT *
                                FROM rooms
                                WHERE room_id = ?";
                        $stmt = $link->prepare($sql);
                        $stmt->bind_param("s", $room_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                    
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                        
                        echo '<p>Room <b>#' . $row['title'] . '</b> by <b>' . $row['creator_username'] . '</b></p>'; 
                    }
                    }}
                ?> 
            </div>
            <div id="chatbox">
            <?php
                $room_id = $_GET['id'];

                $log_filename = "log-" . $room_id . ".html";
                $log_file_path = "logs/" . $log_filename;
                
                if (file_exists($log_file_path) && filesize($log_file_path) > 0) {
                    $contents = file_get_contents($log_file_path);
                    echo $contents;
                }
                ?>
            </div>
            <form name="message" action="" class="sendmsg" id="change1">
                <label for="image-upload" class="btn btn-primarry" style="height: 100%; width: 8%;">
                    <img src="upload.png" style="width:80%">
                </label>
                <input id="image-upload" type="file" name="file" accept="image/*">

                <input type="hidden" name="room_id" id="room_id" value="<?php echo $room_id; ?>">
                
                <input name="usermsg" type="text" id="usermsg" placeholder="Type a message..." />
                <input name="submitmsg" type="submit" id="submitmsg" value="Send" class="btn btn-primarry" />
            </form>
        </div>

        <div class="users" id="users">
            <table id="UL">
                <tr>
                <th><input type="text" id="user-search" onkeyup="search()" placeholder="Search users..." ></th>
                </tr>

                <?php
                    require_once "config.php";

                    if ($link->connect_error) {
                    die("Connection failed: " . $link->connect_error);
                    }
                    if (isset($_GET['id'])) {
                        $room_id = $_GET['id'];
                    
                        $sql = "SELECT u.username, u.created_at, u.fb, u.insta, u.discord, u.tiktok, u.twitter, u.reddit, u.github, u.twitch, u.steam, u.web, u.status
                                FROM users u
                                JOIN participants p ON u.username = p.participant
                                WHERE p.room_id = ?";
                        $stmt = $link->prepare($sql);
                        $stmt->bind_param("s", $room_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                    
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr><td><h5 class='username hov' tabindex=0><img id='" . $row["username"] . "-pic' class='small-pp' src='images/" . $row["username"] . ".jpg'>
                                <span id='" . $row["status"] . "'></span> &nbsp" . $row["username"] . "</h5><div class='hide'>
                                <h6 class=username id='" . $row["username"] . "'>" . $row["username"] . "</h6><img class='pp' id='" . $row["username"] . "-pic' src='images/" . $row["username"] . ".jpg'>
                                <h6 class=username id='" . $row["username"] . "'> User joined at <br> " . $row["created_at"] . "</h6>
                                <h6 class=username id='" . $row["username"] . "' style='text-align: left'>";
                                if ($row["fb"] != "") {
                                    echo "<div style='display:flex; align-items: center;'><img src='fb.png' class='icon'><p style='text-transform: capitalize; margin: 0;'>" . $row["fb"] . "</p></div>";
                                }
                                if ($row["insta"] != "") {
                                    echo "<img src='insta.png' class='icon'>" . $row["insta"] . "<br>";
                                }
                                if ($row["discord"] != "") {
                                    echo "<img src='discord.png' class='icon'>" . $row["discord"] . "<br>";
                                }
                                if ($row["tiktok"] != "") {
                                    echo "<img src='tiktok.png' class='icon'>" . $row["tiktok"] . "<br>";
                                }
                                if ($row["twitter"] != "") {
                                    echo "<img src='twitter.png' class='icon'>" . $row["twitter"] . "<br>";
                                }
                                if ($row["reddit"] != "") {
                                    echo "<img src='reddit.png' class='icon'>" . $row["reddit"] . "<br>";
                                }
                                if ($row["github"] != "") {
                                    echo "<img src='github.png' class='icon' style='background-color: white'>" . $row["github"] . "<br>";
                                }
                                if ($row["twitch"] != "") {
                                    echo "<img src='twitch.png' class='icon' style='background-color: white'>" . $row["twitch"] . "<br>";
                                }
                                if ($row["steam"] != "") {
                                    echo "<img src='steam.png' class='icon'>" . $row["steam"] . "<br>";
                                }
                                if ($row["web"] != "") {
                                    echo "<img src='web.png' class='icon' style='background-color: white'>" . $row["web"] . "</h6></div></td></tr>";
                                } else {
                                    echo "</h6></div></td></tr>";
                                }
                            }
                            } else {
                                echo "No participants found in the current chat room.";
                            }
                        } else {
                            echo "No room ID specified.";
                        }
                        $link->close();
                ?>
            </table>
        </div>

        <div class="hamburger" tabindex='0'>
            <span class="top-bun"></span>
            <span class="stuffing"></span>
            <span class="bottom-bun"></span>
        </div>

        <script type="text/javascript" src="scripts.js"></script>
    </body>
</html>