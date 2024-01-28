<?php

    require_once 'databaseAccessObject.php';
    // When the user logged in, open a session. 
    session_start();
    
    // Check if a user is logged in, then retrieve the username and user ID from the session.
    if(isset($_SESSION['Username'])) {
        $loggedInUser = $_SESSION['Username'];
        $loggedUserId = $_SESSION['UserID'];

    } else {
        header("Location: loginScreen.php");
    }

    // CREATE 

    $taskDAO = new TaskDAO();

    // Check if the form is submitted by checking the presence of the 'submit' key in the POST data.
    if (isset($_POST['submit'])) {
        // To check if a new list is being created
        if (isset($_POST['newListName'])) {
            $newListName = $_POST['newListName'];
            
            // To check if the user wrote a new list name, if yes insertNewList() then insertTask()
            if (!empty($newListName)) {
                $newListId = $taskDAO->insertNewList($newListName, $loggedUserId);

                $title = $_POST['title'];
                $description = $_POST['description'];

                $taskDAO->insertTask($title, $description, $loggedUserId, $newListId);
            
            // The user didn't write a new list name, so check if he selected a listName, if yes insertTask()
            } else {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $listId = $_POST['taskLists'];
            
            // The user didn't write a new list name, and didn't select a listName
            if (empty($listId) || $listId === "all") {
                echo "Please select a valid list before adding a task.";
            } else {
                $taskDAO->insertTask($title, $description, $loggedUserId, $listId);
            }}
        }
    }

    // Check if a checkbox has been clicked by checking the presence of the 'checkbox' key in the POST data and call the completionUpdate().
    if (isset($_POST['checkbox'])) {
        $completion=$_POST['checkbox'];
        $id=$_POST['id'];
        
        $taskDAO->completionUpdate($id, $completion);
    }

    // Check if the logout button has been clicked by checking the presence of the 'logout' key in the POST data and then close the session.
    if(isset($_POST['logout'])) {
        session_destroy();
        header("Location: loginScreen.php");
        exit();
    }

    // Check if the deleteAllChecked button has been clicked, if yes then call the function corresponding.
    if(isset($_POST['deleteAllChecked'])) {
        $taskDAO->deleteAllChecked($loggedUserId);
    }

    // Check if the markAllTasks button has been clicked, if yes then call the function corresponding.
    if(isset($_POST['markAllTasks'])) {
        $taskDAO->markAllTasks($loggedUserId);
    }

    // Check if a list has been selected in the dropdown to filter the tasks, if yes then call the function corresponding.
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
        <!-- Form to create a new task with a corresponding list -->
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
        
        <!-- This is the part where tasks are displayed, modified or deleted. -->
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
    // Function to manage checkbox status in the frontend.
    function changeHandler(id) {
        // Check if the checkbox with the given id is checked, then if checked disable it and vice versa.
        if(document.getElementById(`checkbox${id}`).checked) {
            document.getElementById(`checkboxHidden${id}`).disabled = true;
        } else {
            document.getElementById(`checkboxHidden${id}`).disabled = false;
        }
        const form = document.getElementById(`form${id}`);
        form.submit();
    }

    // Eventlistener so if the user chose a list in "Filter by list", submit it. 
    document.getElementById('lists').addEventListener('change', function() {
        var form = document.getElementById('listsForm');
        
        form.submit();
    });
</script>
