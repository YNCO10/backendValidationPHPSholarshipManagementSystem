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
        $schoolAttended = $_POST["schoolAttended"] ?? "";
        $gpa = $_POST["gpa"] ?? "";
        $fin_assistance = $_POST["fin_assistance"] ?? "";
        $reasonForApplying = $_POST["reasonForApplying"] ?? "";
        $transcript = $_POST["transcript"] ?? "";
        $nationalID = $_POST["nationalID"] ?? "";
        $recommendation_letter = $_POST["recommendation_letter"] ?? "";
        // $email = $_POST["email"] ?? "";
        $careerGoal = $_POST["careerGoal"] ?? "";

        // GET USER ID USING EMAIL
        $demoEmail = "jeff@gmail.com";
        $scholsrshipID = 10;
        $getIdQuery = "SELECT id FROM applicant WHERE email = ?";
        $userIdResult = $db->select($getIdQuery, [$demoEmail]);

        if(count($userIdResult) > 0){
            $userId = $userIdResult[0]["id"];
        }
        else{
            echo json_encode([
                "status" => "error",
                "message" => "User ID not found."
                ]
            );
        }

        $query = "INSERT INTO applications(
        user_id, 
        scholarship_id, 
        school_attended, 
        gpa, 
        fin_assistance, 
        reason_for_applying, 
        transcript, 
        nationalID, 
        recomm_letter,
        careerGoals
        )
        VALUES(?,?,?,?,?,?,?,?,?,?)";

        $result = $db->execute(
            $query, 
            [
                $userId,
                $scholsrshipID,
                $schoolAttended,
                $gpa,
                $fin_assistance,
                $reasonForApplying,
                $transcript,
                $nationalID,
                $recommendation_letter,
                $careerGoal
            ]);

        if($result > 0){
            echo json_encode([
                "status" => "success",
                "message" => "Your application has been sent."
                ]
            );
            exit;
        }
        else{
            echo json_encode([
                "status" => "error", 
                "message" => "Application Process failed."
            ]);
            exit;
        }
    }
    catch(Exception $e){
        echo json_encode([
                "status" => "error", 
                "message" => "Excpetion Error: ". $e->getMessage()
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