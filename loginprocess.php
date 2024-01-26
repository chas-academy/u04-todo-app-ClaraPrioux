<?php

include 'databaseAccessObject.php';
$taskCRUD = new taskDAO();

if(isset($_POST['loginUsername'])){
    $idReturned = $taskCRUD->loginUser($_POST['loginUsername'], $_POST['loginPassword']);

    if($idReturned == -1) {
        header("Location: loginScreen.php?userdoesntexist");
    } elseif($idReturned == -2) {
        header("Location: loginScreen.php?wrongpassword");
    } else {
        session_start();
        $_SESSION['Username'] = $_POST['loginUsername'];
        $_SESSION['UserID'] = $idReturned;

        header("Location: index.php?successfullogin");
    }

    exit();
}

?>