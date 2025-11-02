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
        $status = $_POST["status"] ?? "";
        $uid = $_POST["id"] ?? "";

        if($status == "ACCEPTED"){
            //check is applications is already accepted
            $query = "SELECT `application_status` FROM applications WHERE id = ?";
            $result = $db->select($query, [$uid]);
            if(count($result) > 0){
                if($result[0]["application_status"] == "ACCEPTED"){
                    echo json_encode([
                        "status"=>"error",
                        "message"=>"application has already been Accepted"
                    ]);
                    exit;
                }
            }
            //update status
            $query = "UPDATE applications SET `application_status` = ? WHERE id = ?";
            $result = $db->execute($query, [$status, $uid]);

            if($result > 0){
                echo json_encode([
                    "status"=>"success",
                    "message"=>"application has been Accepted. Please inform them by sending them a email."
                ]);
                exit;
            }
        }
        else if($status == "REJECTED"){
            //check is applications is already rejected
            $query = "SELECT `application_status` FROM applications WHERE id = ?";
            $result = $db->select($query, [$uid]);
            if(count($result) > 0){
                if($result[0]["application_status"] == "REJECTED"){
                    echo json_encode([
                        "status"=>"error",
                        "message"=>"application has already been Rejected"
                    ]);
                    exit;
                }
            }

            //update status
            $query = "UPDATE applications SET `application_status` = ? WHERE id = ?";
            $result = $db->execute($query, [$status, $uid]);

            if($result > 0){
                echo json_encode([
                    "status"=>"success",
                    "message"=>"application has been Rejected. Please inform them by sending them a email."
                ]);
                exit;
            }
        }
        else if($status == "Reviwed"){
            //check is applications is already reviewd
            $query = "SELECT `application_status` FROM applications WHERE id = ?";
            $result = $db->select($query, [$uid]);
            if(count($result) > 0){
                if($result[0]["application_status"] == "Reviewed"){
                    echo json_encode([
                        "status"=>"error",
                        "message"=>"application has already been Marked as Reviewed"
                    ]);
                    exit;
                }
            }

            //update status
            $query = "UPDATE applications SET `application_status` = ? WHERE id = ?";
            $result = $db->execute($query, [$status, $uid]);

            if($result > 0){
                echo json_encode([
                    "status"=>"success",
                    "message"=>"application has been Reviewed. Please inform them by sending them a email."
                ]);
                exit;
            }
        }
        else{
            echo json_encode([
                "status"=>"error",
                "message"=>"Invalid Status was given."
            ]);
            exit;
        }
    }
    catch(Exception $e){
        echo json_encode([
            "status"=>"error",
            "message"=>"Exception: " . $e->getMessage()
        ]);
        exit;
    }
}
else {
    echo json_encode([
        "status" => "error", 
        "message" => "Invalid request method"
    ]);
}

?>`