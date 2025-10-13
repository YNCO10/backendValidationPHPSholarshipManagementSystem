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
        $filter = $_POST["filter"] ?? "";

        if($filter == "All"){
            $query = "SELECT * FROM `notifications` WHERE recipient_email = ?";

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
        else if($filter == "unseen"){
            $query = "SELECT * FROM `notifications` WHERE recipient_email = ? AND noti_status = ?";

            $notificationData = $db->select($query, [$email, $filter]);

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
        else if($filter == "seen"){
            $query = "SELECT * FROM `notifications` WHERE recipient_email = ? AND noti_status = ?";

            $notificationData = $db->select($query, [$email, $filter]);

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