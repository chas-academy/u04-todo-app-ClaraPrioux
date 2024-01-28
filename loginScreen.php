<?php 

    include 'databaseAccessObject.php';

    $taskCRUD = new TaskDAO();

    // Calls the registerUser() methods if a inputUsername has been added 
    if(isset($_POST["inputUsername"])) {
        $taskCRUD -> registerUser($_POST['inputUsername'],$_POST['inputPassword']);
    }

    // Check if the 'userdoesntexist' query parameter is present in the URL (see loginprocess.php).
    if(isset($_GET["userdoesntexist"])) {
        print ('<p>This user doesn\'t exist.</p>');
    }

    // Check if the 'wrongpassword' query parameter is present in the URL (see loginprocess.php).
    if(isset($_GET["wrongpassword"])) {
        print ('<p>Wrong Password.</p>');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log in</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Raleway:wght@300;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>
<body>
    <!-- Forms to log in or register -->
    <div class="tasks-section">
        <h2>Log in</h2>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <form action="loginprocess.php" method="POST" class="loginForm">
            <label for="loginUsername"><b></b></label>
            <input type="text" placeholder="Enter Username" name="loginUsername" required><br>
            <label for="loginPassword"><b></b></label>
            <input type="password" placeholder="Enter Password" name="loginPassword" required><br>
            <button type="submit" name="submit" value="Submit">Log in</button>
        </form>
        <h2>Register</h2>
        <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST" class="loginForm">
            <label for="inputUsername"><b></b></label>
            <input type="text" placeholder="Enter Username" name="inputUsername" required><br>
            <label for="inputPassword"><b></b></label>
            <input type="password" placeholder="Enter Password" name="inputPassword" required><br>
            <button type="submit" name="submit" value="Submit">Submit</button>
        </form>
    </div>  
</body>
</html>


