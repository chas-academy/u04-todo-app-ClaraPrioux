<?php

define('DB_HOST', 'db');
define('DB_USER', 'root');
define('DB_PASS', 'mariadb');
define('DB_NAME', 'to_do_list');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>