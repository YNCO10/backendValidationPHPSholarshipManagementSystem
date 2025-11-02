<?php

//show Hidden errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// force json output
header('Content-Type: application/json');


require_once "C:/XAMPP/htdocs/BackEnd/scholarshipManagement/dbHandler.php";


$db = new database();
$conn = $db->connectToDatabase();

if($_SERVER["REQUEST_METHOD"]==="POST"){
    try{
        $userID = $_POST["userID"] ?? "";
        $applicationID = $_POST["applicationID"] ?? "";
        // $email = $_POST["email"] ?? "";

        // $query = "SELECT id FROM  applicant WHERE email = ?";
        // $result = $db->select($query, [$email]);

        // if(count($result) <= 0){
        //     echo json_encode([
        //         "status" => "error", 
        //         "message" => "Applicant ID not found. Check if email was correct"
        //     ]);
        // }
        // $applicationID = $result[0]["id"];

        // $applicationID = 133;
        // $userID = 4;

        $query ="SELECT
        a.id as applicantID,
        a.`name` as applicant_name,
        a.email,
        a.income_bracket,
        app.id as applicationID,
        app.application_status,
        app.date_submitted,
        app.reason_for_applying,
        sc.`name` as scholarship_name,
        sc.deadline
        FROM applicant a
        LEFT JOIN applications app ON a.id = app.user_id
        LEFT JOIN scholarships sc ON app.scholarship_id = sc.id
        WHERE a.id = ?
        AND app.id = ?";

        $data = $db->select($query, [$userID, $applicationID]);

        if(count($data) > 0){
            echo json_encode([
                "status" => "success", 
                "data" => $data
            ]);
        }
        else{
            echo json_encode([
                "status" => "error", 
                "message" => "Error: Check if User has Uploaded any documents"
            ]);
        }
    }
    catch(Exception $e){
        echo json_encode([
            "status" => "Error",
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