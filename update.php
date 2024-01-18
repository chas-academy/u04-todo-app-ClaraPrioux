<?php 

include "config.php";

if(isset($_GET["id"])) {
    $id = $_GET['id'];

    if(isset($_POST['submit'])) {
        $title = $_POST['title'];
        $description = $_POST ['description'];
    
        try {
            $stmt = $pdo->prepare('UPDATE tasks SET title = :title, description = :description WHERE id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->execute();

            if($stmt->rowCount()){
                header("Location: index.php");
                exit();
            }
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    try {
        $stmt = $pdo->prepare('SELECT * FROM tasks WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $results = $stmt->fetchAll();
        $result = $results[0];

    } catch(PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo "No id in URL";
}
?>
<!DOCTYPE html>
<html>
    <body>
        <h2>To-do</h2>
        <form action="" method="POST">
            <?php
            echo "Title: <input type=\"text\" name=\"title\" value=\"" . $result['title'] . "\"><br>";
            echo "Description: <input type=\"text\" name=\"description\" value=\"" . $result['description'] . "\"><br>";
            ?>
            <input type="submit" name="submit" value="Submit">
        </form>
    </body>
</html>

<?php

// READ

    $stmt = $pdo->prepare("SELECT * FROM tasks");
    $stmt->execute();
 
    $results = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>
    <head>
        <title>View page</title>
        <link rel="stylesheet" href="">
    </head>
    <body>
        <div class="container">
            <table class="table">
                <tr>
                </tr>
                
                <?php 
                    foreach($results as $result) {
                        echo "<tr>";
                        if($result['completion'] == FALSE) {
                            echo "<td><input type=\"checkbox\"></td>";
                        } else {
                            echo "<td><input type=\"checkbox\" checked></td>";
                        }
                        echo   "<td>" . $result['title'] . "</td>
                                <td>" . $result['description'] . "</td>";
                        echo "<td><a href=\"delete.php?id=" . $result['id'] . "\">x</a><a href=\"update.php?id=" . $result['id'] . "\"> edit</a></td>";        
                        echo  "</tr>";
                    }  
                ?>
            </table>
        </div>
    </body>
</html>

<?php 