<?php

namespace Api;


class Router{
    private $task;

    public function __construct($task)
    {
        $this-> task = $task;
    }

    //Checking Request type.
    public function handelRequest(){
        $method = $_SERVER['REQUEST_METHOD'];
        $path = isset($_GET['id']) ? intval($_GET['id']) : null;

        switch($method){
            case "GET": 
                $this -> getRequest($path);
                break;
            case "POST":
                $this -> postRequest();
                break; 
            case "PUT": 
                $this -> putRequest($path);
                break;
             case "PUT": 
                $this -> putRequest($path);
                break;
            case "DELETE": 
                $this -> deleteRequest($path);
                break;
            default : 
            http_response_code(405);
            echo json_encode(["error"=> "Method Not Allowed"]);
            }

    }

    //Handel GET request.
    private function getRequest($id){
        if($id){
            //for single task.
            $task = $this ->task ->getTask($id);
            if ($task){
                echo json_encode($task);
            }else {
                http_response_code(404);
                echo json_encode(["error"=> "Task not found"]);
            }
        
        }else{
            //for all task
            $tasks= $this ->task ->getAllTasks();
            if(empty($tasks)){
                http_response_code(404);
                echo json_encode(["error"=> "Please Create One first"]);
            }
            else{
                echo json_encode($tasks);
            }
        }
    }

    //Handel POST request.
    private function postRequest(){
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate Title
        if(!isset($data['title']) || trim($data['title']) === ""){
            http_response_code(400);
            echo json_encode(["error" => "Title is required."]);
            return;
        }

        // Priority Validation
        $validPriorities = ["low", "medium", "high"];
        if(isset($data['priority']) && !in_array($data['priority'], $validPriorities)){
            http_response_code(400);
            echo json_encode(["error" => "Invalid priority. Valid priorities are: low, medium, high."]);
            return;
        }
        // Create Task
        $response = $this->task->createTask($data);
        echo json_encode($response);
    }

    //Handel PUT Request.
    private function putRequest($id){
        if (!$id){
            echo json_encode(["error"=> "Task id is require."]);
            http_response_code(400);
            return;
        }
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this -> task -> updateTask($id, $data));
    }

    // Handel DELETE request.
    private function deleteRequest($id){
        if (!$id){
            echo json_encode(["error"=> "Task id is require."]);
            http_response_code(400);
            return;
        }
        echo json_encode($this ->task -> deleteTask ($id));
    }
}
