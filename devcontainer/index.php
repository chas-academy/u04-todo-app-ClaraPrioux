<?php
    include "config.php";
    
    if(isset($_POST['submit'])) {
        $title = $_POST['title'];
        $description = $_POST ['description'];
    
        try {
            $stmt = $pdo->prepare('INSERT INTO tasks(title, description) VALUES(:title, :description)');
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->execute();

            if($stmt->rowCount()){
                echo $title . " and " . $description . " added!";
            }
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html>
    <body>
        <h2>To-do</h2>
        <form action="" method="POST">
            Title: <input type="text" name="title"><br>
            Description: <input type="text" name="description"><br>
            <input type="submit" name="submit" value="Submit">
        </form>
    </body>
</html>