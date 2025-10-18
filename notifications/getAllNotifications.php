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


    try{
        // $id = $_POST["id"] ?? "";
        // $id = 6;

        $query = "SELECT * FROM `notifications`";

        $data = $db->select($query, []);

        if(count($data) > 0){
            echo json_encode([
                "status"=>"success",
                "data"=> $data
            ]);
        }
        else{
            echo json_encode([
                "status"=>"error",
                "message"=>"Failed to select Notification. Was the ID you gave accurate?"
            ]);
        }
    }
    catch(Exception $e){
        echo json_encode([
            "status" => "error",
            "message" => "Exception: " . $e->getMessage()
        ]);
        exit;
    }
// }
// else{
//     echo json_encode([
//         "status"=>"error",
//         "message"=>"Invalid Request method"
//     ]);
// }

?>