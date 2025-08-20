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


    $query = "SELECT `id`, `name`, `type`, `file_path`, `deadline` FROM scholarships";

    $tblData = $db->select($query, []);

    if(count($tblData) > 0){
        
        echo json_encode([
            "status" => "success",
            "data" => $tblData
        ]);
        exit;
    }
    else{
        
        echo json_encode([
            "status"=>"error", 
            "message"=>"No data Found."
        ]);
        exit;
    }


?>