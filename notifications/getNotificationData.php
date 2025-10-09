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

    $query = "SELECT * FROM `notifications`";
    $notificationData = $db->select($query, [$email]);

    if(count($notificationData) > 0){
        echo json_encode([
            "status" => "success",
            "data" => $notificationData
        ]);
        exit;
    }
    else{
        echo json_encode([
            "status" => "error",
            "message" => "Notification Data selection failed"
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

?>