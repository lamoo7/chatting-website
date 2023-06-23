<?php
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Change Your Name Color</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="username.css?<?php echo time(); ?>">
        <link rel="stylesheet" href="objectfit.css?<?php echo time(); ?>">
    </head>
    <body>
        <div class="wrapper" style="width:auto">
            <h2>Change Your Name Color</h2>
            <br>
            <br>
            <form action="colorpost.php" method="post" enctype="multipart/form-data" class="form-reset" style="width:100%">
                <center>
                    <input type="color" name="color" id="color-picker">
                    <br>
                    <br>
                    <div id="chatbox">
                        <div class='msgln'><img class='small-pp' src='images/<?php echo htmlspecialchars($_SESSION["username"]); ?>.jpg'> <b class='user-name' id='user'><?php echo htmlspecialchars($_SESSION["username"]); ?></b>Hi! My name is <?php echo htmlspecialchars($_SESSION["username"]); ?>. <br></div>
                    </div>
                </center>
                <br>
                <br>
                <input type="submit" value="Submit" class="btn btn-primarry">
                <a class="btn btn-link ml-2" href="settings.php">Cancel</a>
            </form>
        </div>
    </body>
    <script>
        const colorPicker = document.getElementById('color-picker');
        const colorBox = document.getElementById('user'); 

        const updateTextColor = (color) => {
            if (color === '#FFFFFF') {
                colorBox.style.color = 'black';
            } else {
                colorBox.style.color = 'white';
            }
        }
        
        colorPicker.addEventListener('input', function() {
            colorBox.style.backgroundColor = this.value;
            updateTextColor(this.value);
        });
    </script>
</html>