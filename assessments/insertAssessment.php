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
        $email = $_POST["email"] ?? "";
        $score = $_POST["score"] ?? "";
        $totalQuest = $_POST["totalQuest"] ?? "";

        if(empty($email) || empty($score)){
            echo json_encode([
                "status" => "error", 
                "message" => "Empty post message"
            ]);
            exit;
        }

        // $demoEmail = "jeff@gmail.com";
        // $demoEmail = "test@gmail.com";
        // $demoEmail = "test1@gmail.com";
        $query = "SELECT id FROM applicant WHERE email = ?";
        $result = $db->select($query, [$email]);
        if(count($result) > 0 ){
            $uid = $result[0]["id"];
        }
        else{
            echo json_encode([
                "status" => "error", 
                "message" => "UID not found"
            ]);
            exit;
        }

        // check if user already took an assessment
        $isDoneQuery = "SELECT * FROM assessment WHERE user_id = ?";
        $isDone = $db->select($isDoneQuery, [$uid]);

        if(count($isDone) > 0){

            $one = 1;
            $updateUserQuery = "UPDATE applicant SET  assessment_completed = ? WHERE id = ?";
            $updateUser = $db->execute($updateUserQuery, [$one, $uid]);
            if($updateUser == 0){
                echo json_encode([
                    "status" => "error", 
                    "message" => "Failed to Update assessment"
                ]);
                exit;
            }

            $updateQuery = "UPDATE assessment SET score = ?, totalQuest = ? WHERE user_id = ?";
            $isUpdated = $db->execute($updateQuery, params: [$score, $totalQuest, $uid]);

            if($isUpdated > 0){
                echo json_encode([
                    "status" => "success", 
                    "message" => "Assessment Recorded."
                ]);
                exit;
            }
            else{
                echo json_encode([
                    "status" => "error", 
                    "message" => "Failed to record assessment"
                ]);
                exit;
            }
        }
        else{
            $query = "INSERT INTO assessment (user_id, score, totalQuest) VALUES (?,?,?)";
            $result = $db->execute($query, [$uid, $score, $totalQuest]);
            

            if($result > 0){
                $one = 1;
                $updateUserQuery = "UPDATE applicant SET  assessment_completed = ?WHERE id = ?";
            $updateUser = $db->execute($updateUserQuery, [$one, $uid]);

                if($updateUser > 0){
                    echo json_encode([
                        "status" => "success", 
                        "message" => "Your Assessment has been Recorded."
                    ]);
                    exit;
                }
                
            }
            else{
                echo json_encode([
                    "status" => "error", 
                    "message" => "Failed to record assessment"
                ]);
                exit;
            }
        }
    }
    catch(Exception $e){
        echo json_encode([
            "status" => "error", 
            "message" => "Exception Error: ". $e->getMessage()
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