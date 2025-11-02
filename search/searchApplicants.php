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
        $keyword = $_POST["keyword"] ?? "";
        // $keyword = "ENGINE";
        $searchKeyword = "%$keyword%";

        $query = "SELECT * FROM applicant 
        WHERE `name` LIKE ? 
        OR email LIKE ?
        OR `status` LIKE ?
        OR program LIKE ?";

        $result = $db->select($query, [
            $searchKeyword, 
            $searchKeyword,
            $searchKeyword,
            $searchKeyword
        ]);

        if(count($result) > 0){
                echo json_encode([
                    "status" => "success",
                    "data" => $result
                ]);
                exit;
            }
        else{
            echo json_encode([
                "status" => "error", 
                "message" => "No items matched your search. Try using another keyword."
            ]);
            exit;
        }
    }
    catch(Exception $e){
        echo json_encode([
            "status" => "error",
            "message"=> "Exception Error: " . $e->getMessage()
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