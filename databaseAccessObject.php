<?php

class TaskDAO {
    private $conn;

    function __construct() {
        $DB_HOST = "db";
        $DB_USER = "root";
        $DB_PASS = "mariadb";
        $DB_NAME = "to_do_list";
        
        try {
            $this->conn = new PDO("mysql:host=" . $DB_HOST . ";dbname=" . $DB_NAME, $DB_USER, $DB_PASS);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function insertTask($title, $description, $userID, $listId) {
        try {
            $stmt = $this->conn->prepare('INSERT INTO tasks(title, description, userID, listId) VALUES(:title, :description, :userID, :listId)');
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
            $stmt->bindParam(':listId', $listId, PDO::PARAM_STR);
            $stmt->execute();

        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function readTasks($userID){
        try {
            $stmt = $this->conn->prepare("SELECT * FROM tasks WHERE userID=:userID");
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->execute();
 
            $results = $stmt->fetchAll();
            return $results;

        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function updateTasks($title, $description, $id){
        try {
            $stmt = $this->conn->prepare('UPDATE tasks SET title = :title, description = :description WHERE id = :id');
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

    public function readSingleTask($id){
        try {
            $stmt = $this->conn->prepare('SELECT * FROM tasks WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
    
            $results = $stmt->fetchAll();
            return $results[0];
    
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function deleteSingleTask($id){
        try {
            $stmt = $this->conn->prepare('DELETE FROM tasks WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
    
            return $stmt->rowCount();
    
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function completionUpdate($id, $completion) {
        try {

            $stmt = $this->conn->prepare('UPDATE tasks SET completion=:completion WHERE id=:id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':completion', $completion, PDO::PARAM_INT);
            $stmt->execute();

        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    private function secureInput($input){
        return htmlspecialchars(strip_tags($input));
    }

    function registerUser($username, $password){
        $username = $this->secureInput($username);
        $password = $this->secureInput($password);
    
        $password = password_hash($password,PASSWORD_BCRYPT);

        try {
            $stmt = $this->conn->prepare('INSERT INTO Users(Username, Password) VALUES (:username, :password)');
            $stmt->bindParam("username",$username);
            $stmt->bindParam("password",$password);
            $stmt->execute();
        } catch (PDOException $error) {
            echo "Connection failed: " . $error->getMessage();
        }
    }

    function loginUser($username, $password){
        
        try {
            $stmt = $this->conn->prepare('SELECT Password, userID FROM Users WHERE Username=:username');
            $stmt->bindParam("username",$username);
            $stmt->execute();
    
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            
            // The user doesn't exist, return -1 (because this value will never exist)
            if(empty($result)){
                return -1;
            }
    
            if(password_verify($password, $result[0]['Password'])) {
                return $result[0]['userID']; 
            }
    
            // Wrong password but user exists, return -2 (another value that will never exist in the db and different fromt the other false)
            return -2;
    
            // this means that password_verify will compare the $password with the first one in the fetchAll result (which is an array of an array, we only want the userPass array in the whole array).
    
        } catch (PDOException $error) {
            echo "Connection failed: " . $error->getMessage();
        }
    }

    function getLists($userID){
        try {
            $stmt = $this->conn->prepare("SELECT * FROM lists WHERE userID=:userID");
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->execute();
 
            $results = $stmt->fetchAll();
            return $results;

        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function readTasksByList($userID, $listId){
        try {
            $stmt = $this->conn->prepare("SELECT * FROM tasks WHERE userID=:userID AND listId=:listId");
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->bindParam(':listId', $listId, PDO::PARAM_INT);
            $stmt->execute();
 
            $results = $stmt->fetchAll();
            return $results;

        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function insertNewList($listName, $userID) {
        try {
            $stmt = $this->conn->prepare('INSERT INTO lists(listName, userID) VALUES(:listName, :userID)');
            $stmt->bindParam(':listName', $listName, PDO::PARAM_STR);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->execute();
            
            return $this->conn->lastInsertId();
    
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    
    public function deleteAllChecked($userID){
        try {
            $stmt = $this->conn->prepare("DELETE FROM tasks WHERE userId=:userID AND completion = 1;");
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->execute();
 
            $results = $stmt->fetchAll();
            return $results;

        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function markAllTasks($userID){
        try {
            $stmt = $this->conn->prepare("UPDATE tasks SET completion=1 WHERE userID=:userID;");
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->execute();
 
            $results = $stmt->fetchAll();
            return $results;

        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
