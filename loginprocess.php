<?php

include 'databaseAccessObject.php';
$taskCRUD = new taskDAO();

if(isset($_POST['loginUsername'])){
    if($taskCRUD->loginUser($_POST['loginUsername'],$_POST['loginPassword'])) {
        session_start();
        $_SESSION['Username'] = $_POST['loginUsername'];
        header("Location: index.php?successfullogin");
    } else {
        header("Location: loginScreen.php?unsuccessfullogin");
    }
    exit();
}

?>