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

        // $email = "test11@gmail.com";
        $email = $_POST["email"] ?? "";
        $query = "SELECT id FROM applicant WHERE email = ?";

        $result = $db->select($query, [$email]);

        if(count($result) <= 0){
            echo json_encode([
                "status"=>"error", 
                "message"=>"User Id Not found."
            ]);
            exit;
        }

        $uid = $result[0]["id"];

        $query = "SELECT
        a.id,
        a.`name`,
        a.email,
        a.age, 
        a.date_registered,
        a.nationality,
        a.score AS applicant_score,
        a.phone_num,
        a.education_level,
        a.school_attended,
        a.gpa,
        a.income_bracket,
        ass.score AS assessment_score,
        sc.deadline,
        app.date_submitted,
        app.reason_for_applying,
        app.careerGoals
        FROM applicant a
        LEFT JOIN assessment ass ON a.id = ass.user_id
        LEFT JOIN applications app ON a.id = app.user_id
        LEFT JOIN scholarships sc ON app.scholarship_id = sc.id
        WHERE a.id = ?";

        $applicantData = $db->select($query, [$uid]);

        if(count($applicantData) > 0){
            echo json_encode([
                "status" => "success",
                "data" => $applicantData
            ]);
            exit;
        }
        else{
            echo json_encode([
                "status"=>"error", 
                "message"=>"Select Statement Failed"
            ]);
            exit;
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