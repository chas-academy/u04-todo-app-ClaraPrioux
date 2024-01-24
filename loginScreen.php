<?php 

    include 'databaseAccessObject.php';

    $taskCRUD = new TaskDAO();

    if(isset($_POST["inputUsername"])) {
        $taskCRUD -> registerUser($_POST['inputUsername'],$_POST['inputPassword']);
    }

    if(isset($_GET["unsuccessfullogin"])) {
        print ('<p>Wrong login, check your credentials.</p>');
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
        <h2>Log in</h2>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <form action="loginprocess.php" method="POST">
            <label for="loginUsername"><b></b></label>
            <input type="text" placeholder="Enter Username" name="loginUsername" required><br>
            <label for="loginPassword"><b></b></label>
            <input type="text" placeholder="Enter Password" name="loginPassword" required><br>
            <button type="submit" name="submit" value="Submit">Log in</button>
        </form>
        <h2>Register</h2>
        <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
            <label for="inputUsername"><b></b></label>
            <input type="text" placeholder="Enter Username" name="inputUsername" required><br>
            <label for="inputPassword"><b></b></label>
            <input type="text" placeholder="Enter Password" name="inputPassword" required><br>
            <button type="submit" name="submit" value="Submit">Submit</button>
        </form>
        
</body>
</html>

