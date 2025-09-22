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
        $uid = $_POST["id"] ?? "";

        $query = "SELECT * FROM applicant WHERE id = ?";
        $applicantData = $db->select($query, [$uid]);

        if(count($applicantData) > 0){
            echo json_encode([
                "status" => "success",
                "data" => $applicantData
            ]);
            exit;
        }
        else{
            echo json_encode([
                "status" => "Error",
                "message" => "Data selection failed"
            ]);
            exit;
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
        "status" => "Error",
        "message" => "Invalid request Method"
    ]);
}

?>