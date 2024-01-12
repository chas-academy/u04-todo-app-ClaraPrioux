<?php
    include "config.php";

    if(isset($_POST['update'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];

        $sql = "UPDATE 'tasks' SET 'title' = '$title', 'description' = '$description'";
        $result = $pdo->query($sql);
        if($result === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error:" . $sql . "<br>" . $pdo->error;
        }
    }

    if(isset($_GET['id'])){
        $user_id = $_GET['id'];
        $sql = "SELECT * FROM 'tasks' WHERE 'id' = '$user_id'";
        $result = $pdo->query($sql);

        if($result->rowCount()>0) {
            while($row = $result->fetch_assoc()) {
                $title = $row['title'];
                $description = $row['description'];
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <body>
        <h2>To-do</h2>
        <form action="" method="POST">
            <fieldset>
            Title: <input type="text" name="title" value="<?php echo $title; ?>"><br>
            Description: <input type="text" name="description" value="<?php echo $description; ?>"><br>
            <input type="submit" name="submit" value="Submit">
            </fieldset>
        </form>
    </body>
</html>