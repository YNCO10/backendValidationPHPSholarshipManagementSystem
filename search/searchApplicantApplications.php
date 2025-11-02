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
    $email = $_POST["email"] ?? "";
    $keyword = $_POST["keyword"] ?? "";
    // $keyword = "blah";
    // $email = "yancoKampha@gmail.com";
    $searchKeyword = "%$keyword%";


    $query = "SELECT * FROM applicant WHERE email = ?";
    $result = $db->select($query, [$email]);

    if(count($result) <= 0){
        echo json_encode([
            "status"=>"error",
            "message"=> "User Id not found. Check if email is valid."
        ]);
        exit;
    }
    $uid = $result[0]["id"];

    $query = "SELECT 
    app.*,
    sc.name AS scholarship_name,
    a.name AS applicant_name
    FROM applications app
    LEFT JOIN scholarships sc ON app.scholarship_id = sc.id
    LEFT JOIN applicant a ON app.user_id = a.id
    WHERE (a.name LIKE ?
    OR sc.name LIKE ?
    OR app.application_status LIKE ? 
    OR app.date_submitted LIKE ?
    OR app.reason_for_applying LIKE ?
    OR app.careerGoals LIKE ?)
    AND app.user_id = ?";

    $result = $db->select($query, [
        $searchKeyword, 
        $searchKeyword,
        $searchKeyword,
        $searchKeyword,
        $searchKeyword,
        $searchKeyword,
        $uid
    ]);

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
            "message" => "No items matched your search. Try using another keyword."
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