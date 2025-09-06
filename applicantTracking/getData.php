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
    // $email = $_POST["email"] ?? "";
    $email = "jeff@gmail.com";
    // get UID, that's what we're going to use in the queryies

    $query = "SELECT id FROM applicant WHERE email = ?";
    $result = $db->select($query, [$email]);

    if(count($result) > 0){
        $uid = $result[0]["id"];

        $applicantTblQuery = "SELECT gpa, fin_assistance, income_bracket FROM applications WHERE user_id = ?";
        $applicationsTbl = $db->select($applicantTblQuery, [$uid]);

        if(count($applicationsTbl) > 0){
            $gpa = $applicationsTbl[0]["gpa"];
            $need = $applicationsTbl[0]["fin_assistance"];
            $incomeBracket = $applicationsTbl[0]["income_bracket"];

            $docQuery = "SELECT doc_type FROM documents WHERE user_id = ?";
            $documentTbl = $db->select($docQuery, [$uid]);

            if(count($documentTbl) > 0){

                $uploadedDocs = array_column($documentTbl, "doc_type");

                $financailProof = in_array("Proof_Of_Need", $documentTbl);
                $transcript = in_array("Transcript", $documentTbl);

                echo json_encode([
                    "status" => "success", 
                    "message" => "Applicant data Retrieved successfully",
                    "gpa" => $gpa,
                    "incomeBracket" => $incomeBracket,
                    "need" => $need,
                    "transcript" => $transcript,
                    "financialProof" => $financailProof,
                    "uploadedDocs" => $uploadedDocs
                ]);
            }
            else{
                echo json_encode([
                    "status" => "error", 
                    "message" => "Applicant Tracking Error!\nSomething went wrong in the backend."
                ]);
            }
        }

    }
    else{
        echo json_encode([
            "status" => "error", 
            "message" => "User ID not found"
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