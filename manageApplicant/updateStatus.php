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
        $email = $_POST["email"] ?? "";

        $query = "SELECT * FROM applicant WHERE email = ?";
        $result = $db->select($query, [$email]);

        if(count($result) <= 0){
            echo json_encode([
                "status"=>"error",
                "message"=>"UID not found."
            ]);
            exit;
        }

        $uid = $result[0]["id"];

        if($status == "ACCEPTED"){
            //check is applicant is already accepted
            $query = "SELECT `status` FROM applicant WHERE id = ?";
            $result = $db->select($query, [$uid]);
            if(count($result) > 0){
                if($result[0]["status"] == "ACCEPTED"){
                    echo json_encode([
                        "status"=>"error",
                        "message"=>"Applicant has already been Accepted"
                    ]);
                    exit;
                }
            }
            //update status
            $query = "UPDATE applicant SET `status` = ? WHERE id = ?";
            $result = $db->execute($query, [$status, $uid]);

            if($result > 0){
                echo json_encode([
                    "status"=>"success",
                    "message"=>"Applicant has been Accepted. Please inform them by sending them a email."
                ]);
                exit;
            }
        }
        else if($status == "REJECTED"){
            //check is applicant is already rejected
            $query = "SELECT `status` FROM applicant WHERE id = ?";
            $result = $db->select($query, [$uid]);
            if(count($result) > 0){
                if($result[0]["status"] == "REJECTED"){
                    echo json_encode([
                        "status"=>"error",
                        "message"=>"Applicant has already been Rejected"
                    ]);
                    exit;
                }
            }

            //update status
            $query = "UPDATE applicant SET `status` = ? WHERE id = ?";
            $result = $db->execute($query, [$status, $uid]);

            if($result > 0){
                echo json_encode([
                    "status"=>"success",
                    "message"=>"Applicant has been Rejected. Please inform them by sending them a email."
                ]);
                exit;
            }
        }
        else if($status == "Reviewed"){
            //check is applicant is already reviewd
            $query = "SELECT `status` FROM applicant WHERE id = ?";
            $result = $db->select($query, [$uid]);
            if(count($result) > 0){
                if($result[0]["status"] == "Reviewed"){
                    echo json_encode([
                        "status"=>"error",
                        "message"=>"Applicant has already been Reviewed"
                    ]);
                    exit;
                }
            }

            //update status
            $query = "UPDATE applicant SET `status` = ? WHERE id = ?";
            $result = $db->execute($query, [$status, $uid]);

            if($result > 0){
                echo json_encode([
                    "status"=>"success",
                    "message"=>"Applicant has been Reviewed. Please inform them by sending them a email."
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

?>