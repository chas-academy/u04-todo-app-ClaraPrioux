<?php

include 'databaseAccessObject.php';
$taskCRUD = new taskDAO();

// Check if the login form is submitted.
if(isset($_POST['loginUsername'])){
    // Attempt to authenticate the user using the loginUser method.
    $idReturned = $taskCRUD->loginUser($_POST['loginUsername'], $_POST['loginPassword']);

    // If the user doesn't exist (-1) or if the password is wrong (-2)
    if($idReturned == -1) {
        header("Location: loginScreen.php?userdoesntexist");
    } elseif($idReturned == -2) {
        header("Location: loginScreen.php?wrongpassword");
    } else {
         // If user exists and right password
        session_start();
        $_SESSION['Username'] = $_POST['loginUsername'];
        $_SESSION['UserID'] = $idReturned;

        header("Location: index.php?successfullogin");
    }

    exit();
}

?>