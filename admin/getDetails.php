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

        $query = "SELECT * FROM `admin` WHERE email = ?";
        $adminData = $db->select($query, [$email]);

        if(count($adminData) > 0){
            echo json_encode([
                "status" => "success",
                "data" => $adminData
            ]);
            exit;
        }
        else{
            echo json_encode([
                "status" => "error",
                "message" => "Admin Data selection failed"
            ]);
            exit;
        }
    }
    catch(Exception $e){
        echo json_encode([
            "status" => "error",
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