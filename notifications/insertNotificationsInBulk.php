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

        $recipientEmails = isset($_POST["recipientEmails"]) ? json_decode($_POST["recipientEmails"], true) : [];

        

        // get admin id
        $query = "SELECT * FROM `admin` WHERE email = ?";
        $adminDetails = $db->select($query, [$adminEmail]);

        if(count($adminDetails) <= 0){
            echo json_encode([
                "status" => "error", 
                "message" => "Admin ID not found."
            ]);
            exit;
        }
        $adminID = $adminDetails[0]["id"];
        $results = [];

        foreach($recipientEmails as $email){
            // get applicant id
            $query = "SELECT * FROM applicant WHERE email = ?";
            $applicantDetails = $db->select($query, [$email]);

            if (count($applicantDetails) === 0) {
                $results[] = [
                    "status" => "error",
                    "message" => "Applicant not found"
                ];
                continue;
            }
            $applicant = $applicantDetails[0];
            $recipientName = $applicant["name"];

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
                    $recipientName,
                    $email
                ]);

            if($result > 0){
                $applicantID = $applicantDetails[0]["id"];
                // check if applicant is verified
                if ($applicant["verified"] == 0) {
                    $results[] = [
                        "status" => "notVerified",
                        "message" => "Applicant not verified. Notification stored locally only."
                    ];
                    continue;
                }

                // Send email notification
            if (sendNotification($email, $title, $msg)) {
                    $results[] = [
                        "status" => "success",
                        "message" => "Notification sent to $recipientName"
                    ];
                } else {
                    $results[] = [
                        "status" => "error",
                        "message" => "Email failed to send to $recipientName"
                    ];
                }
            }
        }
        // single clean JSON response
        echo json_encode([
            "status" => "completed",
            "message" => $results
        ]);
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