<?php 

include "config.php";

if(isset($_GET["id"])) {
    $id = $_GET['id'];
    try {
        $stmt = $pdo->prepare('DELETE FROM tasks WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if($stmt->rowCount()){
            header("Location: index.php");
        }

    } catch(PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo "No id in URL";
}
?>