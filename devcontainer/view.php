<?php
    include "config.php";

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
                    <th>Id</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Completion</th>
                    <th>Action</th>
                </tr>
                
                <?php 
                    foreach($results as $result) {
                        echo "<tr>
                                <td>" . $result['id'] . "</td>
                                <td>" . $result['title'] . "</td>
                                <td>" . $result['description'] . "</td>";
                        if($result['completion'] == FALSE) {
                            echo "<td><input type=\"checkbox\"></td>";
                        } else {
                            echo "<td><input type=\"checkbox\" checked></td>";
                        }
                        echo "<td><a href=\"delete.php?id=" . $result['id'] . "\">x</a><a href=\"update.php?id=" . $result['id'] . "\"> edit</a></td>";        
                        echo  "</tr>";
                    }  
                ?>
            </table>
        </div>
    </body>
</html>