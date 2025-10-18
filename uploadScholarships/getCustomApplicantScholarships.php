<?php

//show Hidden errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// force json output
header('Content-Type: application/json');

ob_clean();
require_once "C:/XAMPP/htdocs/BackEnd/scholarshipManagement/dbHandler.php";
ob_end_clean();

$db = new database();
$conn = $db->connectToDatabase();

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $email= $_POST["email"] ?? "";
    // $email= "test3@gmail.com";

    $query = "SELECT * FROM applicant WHERE email = ?";
    $result = $db->select($query, [$email]);
    
    if(count($result) <= 0){
        echo json_encode([
            "status" => "error", 
            "message" => "User Id not found. Check if email is accurate."
        ]);
        exit;
    }
    //get user id and program
    $uid = $result[0]["id"];
    $programName = $result[0]["program"];

    $query = "SELECT scheme_name FROM program_scheme WHERE program_name = ?";
    $scheme = $db->select($query, [$programName]);
    if(count($scheme) <= 0){
        echo json_encode([
            "status" => "error", 
            "message" => "Scheme Not found, check if program name is accurate."
        ]);
        exit;
    }
    // get scheme name
    $schemeName = $scheme[0]["scheme_name"];

    //filter scholarship by scheme name
    $query = "SELECT * FROM scholarships WHERE scheme_type = ?";
    ##CONTINUE WITH SELECT STATEMENT AFTER POPULATING DB TABLES ACORRDINGLY##
    $result = $db->select($query, [$schemeName]);

    if(count($result) > 0){
        echo json_encode([
            "status" => "success", 
            "data" => $result
        ]);
        exit;
    }
    else{
        echo json_encode([
            "status" => "error", 
            "message" => "Select failed."
        ]);
        exit;
    }


}
else{
    if (ob_get_length()) { ob_clean(); } 
    echo json_encode([
        "status" => "error", 
        "message" => "Invalid request method"
    ]);
}
?>