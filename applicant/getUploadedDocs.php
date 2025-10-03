<?php

//show Hidden errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// force json output
header('Content-Type: application/json');

ob_clean();
require_once "C:/XAMPP/htdocs/BackEnd/scholarshipManagement/dbHandler.php";
ob_end_clean();

$db = new database();
$conn = $db->connectToDatabase();

if($_SERVER["REQUEST_METHOD"] === "POST"){
    
    try{
        $email = $_POST["email"] ?? "";

        $query = "SELECT id FROM applicant WHERE email = ?";
        $result = $db->select($query, [$email]);

        if(count($result) > 0){
            $uid = $result[0]["id"];

            $query = "SELECT * FROM documents WHERE user_id = ?";
            $docs = $db->select($query, [$uid]);

            if(count($docs) > 0){
                echo json_encode([
                    "status" => "success", 
                    "data" => $docs
                ]);
            }
            else{
                echo json_encode([
                    "status" => "error", 
                    "message" => "Docs Not found"
                ]);
            }

        }
        else{
            echo json_encode([
                "status" => "error", 
                "message" => "User Id not found"
            ]);
        }
    }
    catch(Exception $e){
         echo json_encode([
        "status" => "error", 
        "message" => "Exception: " . $e->getMessage()
    ]);
    }
    
}
else{
    echo json_encode([
        "status" => "error", 
        "message" => "Invalid request method"
    ]);
}

?>