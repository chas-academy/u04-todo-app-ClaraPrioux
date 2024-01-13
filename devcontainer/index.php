<?php
    include "config.php";
    
    if(isset($_POST['submit'])) {
        $title = $_POST['title'];
        $description = $_POST ['description'];
    

        $sql = "INSERT INTO tasks (title, description) VALUES ('$title', '$description')";
        $result = $pdo->query($sql);

        if($result === TRUE){
            echo "new record created successfully!";
        } else {
            echo "Error:" . $sql . "<br>" . $pdo->errorInfo();
        }

        $conn->null;
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