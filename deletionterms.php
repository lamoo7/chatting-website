<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Delete Your Account</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="style.css">
        <style>
            button:disabled{
                cursor: not-allowed;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <h2>You want to delete your account...</h2>
            <br>
            <p>If you want to delete your account you must know that your messages will remain in our servers and databases.</p>
            <p>Your messages will still be visible to other users but they will not be able to find you in the participants tab or by using the search bar.</p>
            <p>Your profile picture will be deleted and replaced with a default picture. </p>
            <p>You must agree with these terms to be able to delete your account</p>
            <input type="checkbox" id="check" style="height:20px; width:20px;">
            <label style="transform: translateY(-5px)">I agree to the above terms</label>
            <br>
            <br>
            <div style="display: flex; justify-content: end;">
                <a class="btn btn-link ml-2" href="settings.php">Cancel</a>
                <a href="deleteuser.php"><button class="btn btn-danger" id="delete" style="color: white" disabled>Delete Your Account</button></a>
            </div>
            <script>
                const checkbox = document.getElementById('check');
                const button = document.getElementById('delete');
        
                checkbox.addEventListener('change', function() {
                    button.disabled = !this.checked;
                });
            </script>
        </div>
    </body>
</html>