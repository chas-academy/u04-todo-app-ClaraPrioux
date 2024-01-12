<?php
    include "config.php";

    $sql = "SELECT * FROM tasks";
    $result = $pdo->query($sql);
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
                <head></head>
                <th></th>
                <tr></tr>
                <tbody>
                    <?php
                        if($result->rowCount()>0){
                            while($row=$result->fetch_assoc()){ ?>
                                <tr>
                                    <td><?php echo $row['title'];?></td>
                                    <td><?php echo $row['description'];?></td>
                                    <td><a class="btn btn-info" href="update.php?id=<?php echo $row['id'];?>">Edit</a>&nbsp;<a class="btn btn-danger" href="delete.php?id=<?php echo $row['id'];?>">Delete</a></td>
                                </tr>
                            <?php
                            }
                        }
                        ?>
                </tbody>
            </table>
        </div>
    </body>
</html>