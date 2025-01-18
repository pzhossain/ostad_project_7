<?php

use Api\Router;
use Api\Task;
use Config\Database;

require_once "./vendor/autoload.php";
header("Content-Type: application/json");


// Database Initialization.
$db = new Database();
$conn = $db -> getConnection();
$task = new Task($conn);
$router = new  Router($task);

//handel Requests.
$router -> handelRequest();
$conn -> close();