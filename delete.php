<?php 
require_once 'databaseAccessObject.php';

// Create a new instance
$taskDAO = new TaskDAO();

// Take the task's id selected, then call the method in taskDAO to delete it. 
if(isset($_GET["id"])) {
    $id = $_GET['id'];

    if($taskDAO->deleteSingleTask($id)){
        header("Location: index.php");
    }

} else {
    echo "No id in URL";
}
?>