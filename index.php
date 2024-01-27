<?php

    require_once 'databaseAccessObject.php';
    
    session_start();
    
    if(isset($_SESSION['Username'])) {
        $loggedInUser = $_SESSION['Username'];
        $loggedUserId = $_SESSION['UserID'];

    } else {
        header("Location: loginScreen.php");
    }

    // CREATE 

    $taskDAO = new TaskDAO();

    if (isset($_POST['submit'])) {
        // To check if a new list is being created
        if (isset($_POST['newListName'])) {
            $newListName = $_POST['newListName'];
            

            if (!empty($newListName)) {
                $newListId = $taskDAO->insertNewList($newListName, $loggedUserId);

                $title = $_POST['title'];
                $description = $_POST['description'];

                $taskDAO->insertTask($title, $description, $loggedUserId, $newListId);

            } else {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $listId = $_POST['taskLists'];
    
            // Check if a list is selected
            if (empty($listId) || $listId === "all") {
                echo "Please select a valid list before adding a task.";
            } else {
                $taskDAO->insertTask($title, $description, $loggedUserId, $listId);
            }}
        }
    }

    if (isset($_POST['checkbox'])) {
        $completion=$_POST['checkbox'];
        $id=$_POST['id'];
        
        $taskDAO->completionUpdate($id, $completion);
    }

    if(isset($_POST['logout'])) {
        session_destroy();
        header("Location: loginScreen.php");
        exit();
    }

    if(isset($_POST['deleteAllChecked'])) {
        $taskDAO->deleteAllChecked($loggedUserId);
    }
    
    if(isset($_POST['markAllTasks'])) {
        $taskDAO->markAllTasks($loggedUserId);
    }

    if(isset($_POST['lists'])) {
        if($_POST['lists'] == "all") {
            $results = $taskDAO->readTasks($loggedUserId);
        } else {
            $results = $taskDAO->readTasksByList($loggedUserId, $_POST['lists']);
        }
    } else {
        $results = $taskDAO->readTasks($loggedUserId);
    }

    // READ 
    $resultsLists = $taskDAO->getLists($loggedUserId);
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
    <form action="" method="POST" id="logout">
        <button type="submit" name="logout" class="btn btn-dark" style="background-color: #700325;">Log out</button>
    </form>
    <div class="main-section">
       <div class="tasks-section">
        <h2>To-do list</h2>
        <?php if(!empty($loggedInUser)): ?>
            <strong><p>Welcome, <?php echo $loggedInUser; ?>!</strong> <br> Stay organized, boost productivity, and achieve your goals with our simple and efficient task management platform.</p>
        <?php endif; ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
            <form action="" method="POST">
                <input type="text" name="title" placeholder="Enter task title..."><br>
                <textarea type="text" name="description" id="description" class="no-bootstrap-styles" placeholder="Enter task description..."></textarea>
                <label for="taskLists">Choose task list:</label><br>
                    <select id="taskLists" name="taskLists">
                    <option value="all">All</option>
                    <?php
                    $usersLists = $taskDAO->getLists($loggedUserId);

                    foreach ($usersLists as $list) {
                        echo "<option value='{$list['listId']}'>{$list['listName']}</option>";
                    }
                    ?>
                    </select><br>
                    <label for="newListName">or Create a new list:</label>
                    <input type="text" id="newListName" name="newListName" placeholder="Enter new list name">
                <button type="submit" name="submit" value="Submit">Add +</button>
            </form>
        </div>
            
        <div class="container">
            <table class="table">
                <tr>
                <form action="" method="POST">
                    <button type="submit" name="deleteAllChecked" class="btn btn-dark" style="background-color: #700325;">Delete all checked</button>
                    <button type="submit" name="markAllTasks" class="btn btn-dark" style="background-color: #700325;">Mark all as checked</button>
                </form>
                <form id="listsForm" action="" method="POST">
                <label for="lists"></label>
                <select id="lists" name="lists">
                    <option value="all">Filter by List:</option>
                    <?php 
                    foreach($resultsLists as $list) {
                        $selected = "";
                        if(isset($_POST['lists'])) {
                            $listValue = $_POST['lists'];
                            if($listValue == $list['listId']) {
                                $selected = "selected";
                            }
                        }
                        echo "<option value=\"" . $list['listId'] . "\" ".$selected ." >" . $list['listName'] . "</option>";
                    }
                    ?>
                </select>
        </form>
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

    document.getElementById('lists').addEventListener('change', function() {
        var form = document.getElementById('listsForm');
        
        form.submit();
    });
</script>
