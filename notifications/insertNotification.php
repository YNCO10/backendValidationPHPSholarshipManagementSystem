<?php

//show Hidden errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// force json output
header('Content-Type: application/json');


require_once "C:/XAMPP/htdocs/BackEnd/scholarshipManagement/dbHandler.php";
require_once "C:/XAMPP/htdocs/BackEnd/scholarshipManagement/notifications/sendNotification.php";


$db = new database();
$conn = $db->connectToDatabase();

if($_SERVER["REQUEST_METHOD"] === "POST"){
    try{
        $adminEmail = $_POST["email"] ?? "";
        $title = $_POST["title"] ?? "";
        $msg = $_POST["msg"] ?? "";
        $recipientEmail = $_POST["recipientEmail"] ?? "";

        // get admin id
        $query = "SELECT * FROM `admin` WHERE email = ?";
        $adminDetails = $db->select($query, [$adminEmail]);

        if(count($adminDetails) > 0){
            $adminID = $adminDetails[0]["id"];
        }
        else{
            echo json_encode([
                "status" => "error", 
                "message" => "Admin ID not found."
            ]);
            exit;
        }

        // get applicant id
        $query = "SELECT * FROM applicant WHERE email = ?";
        $applicantDetails = $db->select($query, [$recipientEmail]);

        if(count($applicantDetails) > 0){

            $query = "INSERT INTO notifications(
            title, 
            msg, 
            sender_id, 
            sender_name, 
            recipient_name, 
            recipient_email
            )
            VALUES (?,?,?,?,?,?)";

            $result = $db->execute(
                $query, 
                [
                    $title,
                    $msg,
                    $adminID,
                    $adminDetails[0]["name"],
                    $applicantDetails[0]["name"],
                    $recipientEmail
                ]);

            if($result > 0){
                $applicantID = $applicantDetails[0]["id"];
                // check if applicant is verified
                if($applicantDetails[0]["verified"] == 0){
                    echo json_encode([
                        "status" => "notVerified", 
                        "message" => "That applicant is not Verified. Only verified accounts can recieve emails.\nA regular message Will be sent which can be viewed in the notifications page."
                    ]);
                    exit;

                }
                else if($applicantDetails[0]["verified"] == 1){
                    if(sendNotification($recipientEmail, $title, $msg)){
                        echo json_encode(value: [
                            "status" => "success", 
                            "message" => "Notification has been sent to " . $applicantDetails[0]["name"]
                        ]);
                        exit;
                    }
                    else{
                        echo json_encode([
                            "status" => "error", 
                            "message" => "Email failed to send"
                        ]);
                        exit;
                    }
                }
            }

        }
        else{
            echo json_encode([
                "status" => "error", 
                "message" => "Applicant ID not found"
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