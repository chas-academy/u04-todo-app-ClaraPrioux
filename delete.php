<?php 
require_once 'databaseAccessObject.php';

$taskDAO = new TaskDAO();

if(isset($_GET["id"])) {
    $id = $_GET['id'];

    if($taskDAO->deleteSingleTask($id)){
        header("Location: index.php");
    }

} else {
    echo "No id in URL";
}
?>