<?php 

    require 'config.php';

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <div class="main-section">
       <div class="tasks-section">
        <h2>To-do list</h2>
            <form action="" method="POST">
                <input type="text" name="title" placeholder="Enter task title..."><br>
                <textarea type="text" name="description" id="description" class="no-bootstrap-styles" placeholder="Enter task description..."></textarea><br>
                <button type="submit" name="submit" value="Submit">Add +</button>
            </form>
</body>
</html>

<?php
// CREATE 

    if(isset($_POST['submit'])) {
        $title = $_POST['title'];
        $description = $_POST ['description'];
    
        try {
            $stmt = $pdo->prepare('INSERT INTO tasks(title, description) VALUES(:title, :description)');
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->execute();

        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    if (isset($_POST['checkbox'])) {
        try {
            $checked = $pdo->prepare(
                                "UPDATE tasks
                                 SET completion={$_POST['checkbox']}
                                 WHERE id={$_POST['id']}"
                              );
            
            $checked->execute();
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }
?>

<?php

// READ

    $stmt = $pdo->prepare("SELECT * FROM tasks");
    $stmt->execute();
 
    $results = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>
    <body>
        <div class="container">
            <table class="table">
                <tr>
                
                <?php 
                    $select = $pdo->prepare("SELECT id, completion FROM tasks");
                    $select->execute();

                    $result = $select->fetchAll();

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
                                    />
                                </form>
                            ";
                        }
                    
                        echo   "<td class=\"todo-title\">" . $result['title'] . "</td>
                                <td class=\"todo-description\">" . $result['description'] . "</td>";
                        echo "<td class=\"todo-X\"><a href=\"delete.php?id=" . $result['id'] . "\">X</a></td>"; 
                        echo "<td class=\"todo-X\"><a href=\"update.php?id=" . $result['id'] . "\">edit</a></td>";        
                        echo  "</tr>";
                    }  
                ?>
            </table>
        </div>
        </div>
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