<?php

// I created a class that contains all the methods needed to make this to-do app works
class TaskDAO {
    private $conn;

    // Database connection using pdo
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

    // Inserts a new task into the database.
    public function insertTask($title, $description, $userID, $listId) {
        try {
            // Prepare a SQL statement to insert a new task into the 'tasks' table.
            $stmt = $this->conn->prepare('INSERT INTO tasks(title, description, userID, listId) VALUES(:title, :description, :userID, :listId)');
            
            // Bind parameters to the prepared statement.
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
            $stmt->bindParam(':listId', $listId, PDO::PARAM_STR);
            $stmt->execute();

            echo "<div style=\"text-align: center; font-size:20px;\">Task added successfully!</div>";
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Retrieves tasks associated with a specific user from the database.
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

    // Update a specific task by using task's id into the database.
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

    // Retrieves a single task from the database, using task's id. Used after updateTasks().
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

    // Delete a specific task by using task's id into the database.
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

    // Update a specific checkbox by using task's id into the database.
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

    // Sanitizes input by removing HTML tags and special characters to prevent potential security vulnerabilities.
    private function secureInput($input){
        return htmlspecialchars(strip_tags($input));
    }

    // Create a new user and insert it in the database
    public function registerUser($username, $password){
        $username = $this->secureInput($username);
        $password = $this->secureInput($password);
        
        // Hash the password using the bcrypt algorithm for secure storage.
        $password = password_hash($password,PASSWORD_BCRYPT);

        try {
            $stmt = $this->conn->prepare('INSERT INTO Users(Username, Password) VALUES (:username, :password)');
            $stmt->bindParam("username",$username);
            $stmt->bindParam("password",$password);
            $stmt->execute();

            echo "<div style=\"text-align: center; font-size:20px;\">User registration successful! Welcome aboard!</div>";
        } catch (PDOException $error) {
            echo "Connection failed: " . $error->getMessage();
        }
    }

    // Log in the user by verifying the username, then the password. 
    public function loginUser($username, $password){
        
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

    // Retrieves lists from the database using userID
    public function getLists($userID){
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

    // Filters tasks by list using userID and listID
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

    // Create a new list using userID and listID
    public function insertNewList($listName, $userID) {
        try {
            $stmt = $this->conn->prepare('INSERT INTO lists(listName, userID) VALUES(:listName, :userID)');
            $stmt->bindParam(':listName', $listName, PDO::PARAM_STR);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->execute();

            echo "<div style=\"text-align: center; font-size:20px;\">New list added successfully!</div>";
            
            return $this->conn->lastInsertId();
    
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    
    // Checks which checkboxes has been checked (1) inside the userID's tasks and delete all
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

    // Changes all the completion to 1, but only for user's tasks
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

