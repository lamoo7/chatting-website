<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $objectFitStyle = $_POST["objectFit"];

    $username = $_SESSION["username"];

    $cssRule = "#{$username}-pic { object-fit: {$objectFitStyle}; }
";

    $filename = "objectfit.css";
    file_put_contents($filename, $cssRule, FILE_APPEND);

    $filename = $_FILES['file']['name'];
    $location = "images/" . $filename;
    $temp = explode(".", $_FILES["file"]["name"]);
    $newfilename = $username . '.jpg';
    move_uploaded_file($_FILES["file"]["tmp_name"], "images/" . $newfilename);

    header("location: settings.php");
    exit;
}
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Change Your Profile Picture</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="objectfit.css?<?php echo time(); ?>">
        <style>     
            input[type=file] {
              display: none;
            }
            .button-group {
              display: flex;
              justify-content: center;
              margin-bottom: 10px;
            }
            .button-group label {
              margin: 5px;
              padding: 5px 10px;
              background-color: #ccc;
              border-radius: 5px;
              cursor: pointer;
              display: flex;
            }
            input[type="radio"] {
                display: none;
            }
            input[type="radio"]:checked + label {
              background-color: #19222c;
              color: #fff;
            }
            input[type="radio"]:checked + label::after {
              content: '\2713';
              margin-left: 5px;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <h2>Change Your Profile Picture</h2>
            <br>
            <center>
                <div class="button-group">
                    <input type="radio" name="objectFit" value="contain" id="objectFit1" onclick="changeObjectFit('contain')">
                    <label for="objectFit1">Contain</label>

                    <input type="radio" name="objectFit" value="cover" id="objectFit2" onclick="changeObjectFit('cover')">
                    <label for="objectFit2">Cover</label>

                    <input type="radio" name="objectFit" value="fill" id="objectFit3" onclick="changeObjectFit('fill')">
                    <label for="objectFit3">Fill</label>

                    <input type="radio" name="objectFit" value="none" id="objectFit4" onclick="changeObjectFit('none')">
                    <label for="objectFit4">None</label>
                </div>
                <br>
                <img src="images/<?php echo htmlspecialchars($_SESSION["username"]); ?>.jpg" class="pp">
            </center>
            <br>
            <form action="imgupload.php" method="post" enctype="multipart/form-data" class="form-reset" style="width:100%">
                <center>
                    <label for="file-upload" class="btn btn-warning">
                        Change Your Profile Picture
                    </label>
                    <input id="file-upload" type="file" name="file" accept="image/*">
                    <br>
                    <input type="submit" value="Upload" class="btn btn-primarry">
                    <a class="btn btn-link ml-2" href="settings.php">Cancel</a>
                    <input type="hidden" name="objectFit" id="objectFitInput">
                </center>
            </form>
        </div>
        <script>
            const inputElement = document.getElementById('file-upload');
            const imgElement = document.querySelector('.pp');
            const objectFitInput = document.getElementById('objectFitInput');

            inputElement.addEventListener('change', (event) => {
                const file = event.target.files[0];
                const reader = new FileReader();
                reader.onload = (event) => {
                    imgElement.src = event.target.result;
                    showButtons();
                };
                reader.readAsDataURL(file);
            });

            function changeObjectFit(objectFitStyle) {
                imgElement.style.objectFit = objectFitStyle;
                objectFitInput.value = objectFitStyle;
            }
        </script>
    </body>
</html>
