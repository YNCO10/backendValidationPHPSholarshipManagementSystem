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
        $id = $_POST["id"] ?? "";

        $query = "SELECT * FROM schemes WHERE id = ?";

        $schemes = $db->select($query, [$id]);

        echo json_encode([
            "data"=>$schemes
        ]);
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
        "status"=>"error",
        "message"=>"Invalid request method"
    ]);
}
?>