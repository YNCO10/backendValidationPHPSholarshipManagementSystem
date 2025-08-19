<?php

header('Content-Type: application/json');

require_once "C:/XAMPP/htdocs/BackEnd/scholarshipManagement/dbHandler.php";

$db = new database();
$conn = $db->connectToDatabase();

if($_SERVER["REQUEST_METHOD"]==="POST"){
    $email = $_POST["email"] ?? "";

    $searchAdminTbl = "SELECT `name` FROM `admin` WHERE email = ?";
    
    $adminUser = $db->select($searchAdminTbl, [$email]);

    if(count($adminUser) > 0){
        echo json_encode([
            "status" => "admin",
            "adminName" => $adminUser[0]["name"]
        ]);
    }
    $searchAppTbl = "SELECT `name` FROM `applicant` WHERE email = ?";

    $applicantUser = $db->select($query, [$email]);

    if(count($applicantUser) > 0){
        echo json_encode([
            "status" => "applicant", 
            "applicantName" => $applicantUser[0]["name"]
        ]);
    }
    // no else statement because that's already been handled in login validation
}
else{
    echo json_encode([
        "status" => "error", 
        "message" => "Invalid request method"
    ]);
}

?>