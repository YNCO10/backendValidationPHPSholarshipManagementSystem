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

    $userID = $_POST["id"] ?? "";

    if($userID == ""){
        
        echo json_encode([
            "status" => "error",
            "message" => "No iD was given"
        ]);
        exit;
    }

    $query = "DELETE FROM scholarships WHERE id = ?";
    $isDeleted = $db->execute($query, [$userID]);

    if($isDeleted > 0){
        echo json_encode([
            "status" => "success", 
            "message" => "Delete Successful"
        ]);
    }
    else{
        
        echo json_encode([
            "status" => "error", 
            "message" => "Delete Failed"
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