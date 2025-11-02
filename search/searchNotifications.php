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
        $email = $_POST["email"] ?? "";
        // $email = "Kush@gmail.com";
        // $keyword = "dennis";

        $query = "SELECT * FROM `admin` WHERE email = ?";
        $result = $db->select($query, [$email]);
        if(count($result) <= 0){
            echo json_encode([
                "status"=>"error",
                "message"=>"Failed to select Notification. Was the ID you gave accurate?"
            ]);
            exit;
        }

        $uid = $result[0]["id"];
        $searchKeyword = "%$keyword%";

        $query = "SELECT * FROM notifications 
        WHERE `title` LIKE ? 
        OR msg LIKE ?
        OR `sender_name` LIKE ?
        OR recipient_name LIKE ?
        OR recipient_email LIKE ?
        OR noti_status LIKE ?
        OR date_sent LIKE ?
        OR date_seen LIKE ?
        AND sender_id = ?";

        $result = $db->select($query, [
            $searchKeyword, 
            $searchKeyword,
            $searchKeyword,
            $searchKeyword,
            $searchKeyword,
            $searchKeyword,
            $searchKeyword,
            $searchKeyword,
            $uid
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