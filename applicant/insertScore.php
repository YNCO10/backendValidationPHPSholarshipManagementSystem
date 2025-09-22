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

        $query = "SELECT * FROM applicant WHERE email = ?";

        $uid = $db->select($query, [$email]);

        if(count($uid) > 0){
            $query = "UPDATE applicant SET score = ? WHERE id = ?";
            $result = $db->execute($query, [$score, $uid[0]["id"]]);

            if($result > 0){
                echo json_encode([
                    "status"=>"success",
                    "message"=>"Applicant is now eligible for ranking"
                ]);
            }
            else{
                echo json_encode([
                    "status" => "error", 
                    "message" => "Failed to update score"
                ]);
            }
        }
        else{
            echo json_encode([
                "status" => "error", 
                "message" => "Applicant not found"
            ]);
        }

    }
    catch(Exception $e){
        echo json_encode([
            "status" => "error", 
            "message" => "Exception: " . $e->getMessage()
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