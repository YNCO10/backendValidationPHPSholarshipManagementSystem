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
        $id = $_POST["id"] ?? "";
        $status = "seen";
        $query = "UPDATE notifications SET noti_status = ?, date_seen = CURRENT_DATE WHERE id = ?";

        $result = $db->execute($query, [$status, $id]);

        if($result > 0){
            echo json_encode([
                "status"=>"success",
                "message"=> "Notification Updated successfully"
            ]);
        }
        else{
            echo json_encode([
                "status"=>"error",
                "message"=>"Failed to Update Notification. Was the ID you gave accurate?"
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
}
else{
    echo json_encode([
        "status"=>"error",
        "message"=>"Invalid Request method"
    ]);
}
?>