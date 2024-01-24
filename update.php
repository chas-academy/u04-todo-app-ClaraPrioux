<?php 
require_once 'databaseAccessObject.php';

$taskDAO = new TaskDAO();

if(isset($_GET["id"])) {
    $id = $_GET['id'];

    if(isset($_POST['submit'])) {
        $title = $_POST['title'];
        $description = $_POST ['description'];
        
        $taskDAO->updateTasks($title, $description, $id);
    }
    
    $specific_result = $taskDAO->readSingleTask($id);

    $results = $taskDAO->readTasks();

} else {
    echo "No id in URL";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>To-Do List</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Raleway:wght@300;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="main-section">
       <div class="tasks-section">
        <h2>To-do list</h2>
        <?php if(!empty($loggedInUser)): ?>
            <p>Welcome, <?php echo $loggedInUser; ?>!</p>
        <?php endif; ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
            <form action="" method="POST">
            <?php
            echo "<input type=\"text\" name=\"title\" value=\"" . $specific_result['title'] . "\"><br>";
            echo "<textarea type=\"text\" name=\"description\" id=\"description\" class=\"no-bootstrap-styles\">" . $specific_result['description'] . "</textarea><br>"
            ?>
                <button type="submit" name="submit" value="Submit">Edit</button>
            </form>
        </div>
        <div class="container">
            <table class="table">
                <tr>
                
                <?php 

                    foreach($results as $result) {
                        echo "<td>";

                        if ($result['completion']) {
                            // Checkbox is checked
                            echo "
                                <form method='post' action='' id='form{$result['id']}'> 
                                    <input
                                        type='hidden'
                                        name='id'
                                        value={$result['id']}
                                    />
                                    <input
                                        type='hidden'
                                        name='checkbox'
                                        id='checkboxHidden{$result['id']}'
                                        value=0
                                    />
                                    <input
                                        type='checkbox'
                                        name='checkbox'
                                        id='checkbox{$result['id']}'
                                        value=1
                                        onchange='changeHandler({$result["id"]})'
                                        checked
                                        class='form-check-input custom-checkbox'
                                    />
                                </form>
                            ";
                        } else {
                            // checkbox not checked
                            echo "
                                <form method='post' action='' id='form{$result['id']}'>
                                    <input
                                        type='hidden'
                                        name='id'
                                        value={$result['id']}
                                    />
                                    <input
                                        type='hidden'
                                        name='checkbox'
                                        id='checkboxHidden{$result['id']}'
                                        value=0
                                    />
                                    <input
                                        type='checkbox'
                                        name='checkbox'
                                        id='checkbox{$result['id']}'
                                        value=1
                                        onchange='changeHandler({$result["id"]})'
                                        class='form-check-input custom-checkbox'
                                    />
                                </form>
                            ";
                        }
                    
                        echo   "<td class=\"todo-title\">" . $result['title'] . "</td>
                                <td class=\"todo-description\">" . $result['description'] . "</td>";
                        echo "<td class=\"todo-X\"><a href=\"delete.php?id=" . $result['id'] . "\"><i class=\"bi bi-trash\" style=\"font-size: 20px;\"></i></a></td>"; 
                        echo "<td class=\"todo-X\"><a href=\"update.php?id=" . $result['id'] . "\"><i class=\"bi bi-pencil\" style=\"font-size: 20px;\"></i></a></td>";        
                        echo  "</tr>";
                    }  
                ?>
            </table>
        </div>
</body>
</html>
<script>
    function changeHandler(id) {
        if(document.getElementById(`checkbox${id}`).checked) {
            document.getElementById(`checkboxHidden${id}`).disabled = true;
        } else {
            document.getElementById(`checkboxHidden${id}`).disabled = false;
        }
        const form = document.getElementById(`form${id}`);
        form.submit();
    }
</script>
