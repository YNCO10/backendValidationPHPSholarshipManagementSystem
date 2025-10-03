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
        $email = $_POST["email"] ?? "";
        $query = "SELECT id FROM applicant WHERE email = ?";
        $result = $db->select($query, [$email]);
        
        if(count($result) > 0){
            $uid = $result[0]["id"];

            $query ="SELECT * FROM documents WHERE user_id = ?";

            $docs = $db->select($query, [$uid]);

            if(count($result) > 0){
                echo json_encode([
                    "status" => "success", 
                    "data" => $docs
                ]);
            }
            else{
                echo json_encode([
                    "status" => "error", 
                    "message" => "User hasn't Uploaded any documents yet"
                ]);
            }
        }
        else{
            echo json_encode([
                "status" => "error", 
                "message" => "User Id not found. Check if email given was correct"
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