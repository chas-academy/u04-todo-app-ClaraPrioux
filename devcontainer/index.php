<?php

// CONFIGURATION

    define('DB_HOST', 'db');
    define('DB_USER', 'clara');
    define('DB_PASS', 'clara');
    define('DB_NAME', 'to_do_list');
    
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

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
