<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// force json output
header('Content-Type: application/json');

ob_clean();
require_once "C:/XAMPP/htdocs/BackEnd/scholarshipManagement/dbHandler.php";
ob_end_clean();

$db = new database();
$conn = $db->connectToDatabase();

if($_SERVER["REQUEST_METHOD"]==="POST"){

    try{

        $uid = $_POST["id"] ?? "";

        $query = "DELETE FROM applications WHERE id = ?";
        $result = $db->execute($query, [$uid]);

        if($result > 0){
            echo json_encode([
                "status"=>"success",
                "message"=>"Application deleted successfully"
            ]);
        }
        else{
            echo json_encode([
                "status"=>"error",
                "message"=>"Failed to delete application. Check if ID was correct."
            ]);
        }

    }
    catch(Exception $e){
        echo json_encode([
            "status"=>"error",
            "message"=>"Exception: " . $e->getMessage()
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