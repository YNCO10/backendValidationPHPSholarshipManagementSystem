<?php

//show Hidden errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// force json output
header('Content-Type: application/json');


require_once "C:/XAMPP/htdocs/BackEnd/scholarshipManagement/dbHandler.php";


$db = new database();
$conn = $db->connectToDatabase();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = $_POST["email"] ?? "";
    // $email = "test16@gmail.com";
    // get UID, that's what we're going to use in the queryies

    try{
        $query = "SELECT id FROM applicant WHERE email = ?";
        $result = $db->select($query, [$email]);

        if(count($result) > 0){
            $uid = $result[0]["id"];

            $query = "SELECT 
            a.id,
            a.name,
            a.age,
            a.phone_num,
            a.nationality,
            a.education_level,
            a.date_registered,
            a.score as applicant_score,
            a.gpa,
            a.school_attended,
            a.income_bracket,
            ass.score as assessment_score,
            app.fin_assistance
            FROM applicant a
            LEFT JOIN assessment ass ON a.id = ass.user_id
            LEFT JOIN applications app ON a.id = app.user_id
            WHERE a.id = ?";

            $data = $db->select($query, [$uid]);

            if(count($data) > 0){

                $docQuery = "SELECT doc_type FROM documents WHERE user_id = ?";
                $documentTbl = $db->select($docQuery, [$uid]);
                $uploadedDocs = array_column($documentTbl, "doc_type");
                if(count($documentTbl) <= 0){
                    echo json_encode([
                        "status" => "error", 
                        "message" => "Document Not found"
                    ]);
                }
                
                echo json_encode([
                    "status" => "success", 
                    "data" => $data,
                    "uploadedDocs" => $uploadedDocs

                ]);
            }
            else{
                echo json_encode([
                    "status" => "error", 
                    "message" => "Something went wrong while selecting applicant data."
                ]);
            }

        }
        else{
            echo json_encode([
                "status" => "error", 
                "message" => "User ID not found"
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