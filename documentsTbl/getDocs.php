<?php

//show Hidden errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// force json output
header('Content-Type: application/json');


require_once "C:/XAMPP/htdocs/BackEnd/scholarshipManagement/dbHandler.php";


$db = new database();
$conn = $db->connectToDatabase();

if($_SERVER["REQUEST_METHOD"] === "POST"){
    try{
        $userID = $_POST["userID"] ?? "";
        $applicationID = $_POST["applicationID"] ?? "";
        // $userID = 20;
        // $applicationID = 17;

        $query ="SELECT * FROM documents WHERE user_id = ? AND application_id = ?";

        $docs = $db->select($query, [$userID, $applicationID]);

        if(count($docs) > 0){
            echo json_encode([
                "status" => "success", 
                "data" => $docs
            ]);
        }
        else{
            echo json_encode([
                "status" => "error", 
                "message" => "Error: check if user has uploaded any documents"
            ]);
        }
    }
    catch(Exception $e){
        echo json_encode([
            "status" => "Error",
            "message" => "Exception: " . $e->getMessage()
        ]);
        exit;
    }
}
else{
    echo json_encode([
        "status" => "error", 
        "message" => "Invalid request method"
    ]);
}

?>