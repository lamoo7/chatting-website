<?php

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit your social links</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <style>
        .contain{
            padding: 20px;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 16px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(8.8px);
            -webkit-backdrop-filter: blur(8.8px);
            border: 1px solid rgb(255, 255, 255);
            left: 50%;
            transform: translateX(-50%);
            position: absolute;
            margin: 40px 0;
        }
        .contain a{
            color: black;
        }
        .form-master {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(5, 1fr);
            gap: 10px;
        }
        @media only screen and (max-width: 1000px) {
            .contain{
                width: 90vw;
            }
        }
    </style>
</head>
<body>
    <div class="contain">
        <h2>Edit your social links</h2>
        <p>Please fill out this form to edit your social links.</p>
        <p>Leave blank if you want to remove the link from your profile.</p>
        <?php

            require_once "config.php";
            $id = $_SESSION['id'];
            if ($link->connect_error) {
            die("Connection failed: " . $link->connect_error);
            }
            $sql = "SELECT * FROM users WHERE id='$id'";
            $result = $link->query($sql);
            $row = $result->fetch_assoc();

        echo "<form action='linksupload.php' method='post'>
        <div class='form-master'>
            <div class='form-group'>
                <label><img src='fb.png' class='icon'>Facebook</label>
                <input type='text' name='fb' class='form-control' pattern='[A-Za-z\s]*' placeholder='Enter Your Facebook Name' value='" . $row['fb'] . "'>
                <span><p>Names cannot contain numbers.<br><br></p></span>
            </div>
            <div class='form-group'>
                <label><img src='insta.png' class='icon'>Instagram</label>
                <input type='text' name='insta' class='form-control' pattern='^@[\w\.]+$' placeholder='Enter Your Instagram @username' value='" . $row['insta'] . "'>
                <span><p>Instagram username must start with a '@' and can only contain letters, numbers, underscores and periods.</p></span>
            </div>
            <div class='form-group'>
                <label><img src='discord.png' class='icon'>Discord</label>
                <input type='text' name='discord' class='form-control' pattern='^[a-zA-Z0-9]+#\d{4}$' placeholder='Enter Your Discord username#tag' value='" . $row['discord'] . "'>
                <span><p>Discord username must be followed by '#' and your tag of 4 numbers.</p></span>
            </div>
            <div class='form-group'>
               <label><img src='tiktok.png' class='icon'>TikTok</label>
               <input type='text' name='tiktok' class='form-control' pattern='^@[\w\.]+$' placeholder='Enter Your Tiktok @username' value='" . $row['tiktok'] . "'>
               <span><p>TikTok username must start with a '@' and can only contain letters, numbers, underscores and periods.</p></span>
            </div>
            <div class='form-group'>
                <label><img src='twitter.png' class='icon'>Twitter</label>
                <input type='text' name='twitter' class='form-control' pattern='^@[A-Za-z0-9_]+$' placeholder='Enter Your Twitter @username' value='" . $row['twitter'] . "'>
                <span><p>Twitter username must start with a '@' and can only contain letters, numbers and underscores.</p></span>
            </div>
            <div class='form-group'>
                <label><img src='reddit.png' class='icon'>Reddit</label>
                <input type='text' name='reddit' class='form-control' pattern='^u/[A-Za-z0-9_.-]+$' placeholder='Enter Your Reddit u/username' value='" . $row['reddit'] . "'>
                <span><p>Reddit username must start with 'u/' and can only contain letters, numbers, underscores, periods and hyphens.</p></span>
            </div>
            <div class='form-group'>
                <label><img src='github.png' class='icon' style='background-color: white'>Github</label>
                <input type='text' name='github' class='form-control' pattern='^(?!-)[A-Za-z0-9-]+(?<!-)$' placeholder='Enter Your Github username' value='" . $row['github'] . "'>
                <span><p>Github username may contain only letters, numbers and hyphens.</p></span>
            </div>
            <div class='form-group'>
                <label><img src='twitch.png' class='icon' style='background-color: white'>Twitch</label>
                <input type='text' name='twitch' class='form-control' pattern='^[A-Za-z0-9_]+$' placeholder='Enter Your Twitch username' value='" . $row['twitch'] . "'>
                <span><p>Twitch username may contain only letters, numbers and underscores.</p></span>
            </div>
            <div class='form-group'>
                <label><img src='steam.png' class='icon'>Steam</label>
                <input type='text' name='steam' class='form-control' placeholder='Enter Your Steam username' value='" . $row['steam'] . "'>
                <span><p>Steam username may contain only letters, numbers and underscores.</p></span>
            </div>
            <div class='form-group'>
                <label><img src='web.png' class='icon' style='background-color: white'>Website</label>
                <input type='text' name='web' class='form-control' placeholder='Enter Your Website address' value='" . $row['web'] . "'>
                <span><p></p></span>
            </div>
        </div>
            <div class='form-group'>
                <input type='submit' class='btn btn-primarry' value='Submit'>
                <a class='btn btn-link ml-2' href='settings.php'>Cancel</a>
            </div>
        </form>";
        ?>
    </div>    
</body>
</html>