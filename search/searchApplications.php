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
    $keyword = $_POST["keyword"] ?? "";
    // $keyword = "reviwed";
    $searchKeyword = "%$keyword%";

    $query = "SELECT 
    app.*,
    sc.name AS scholarship_name,
    a.name AS applicant_name
    FROM applications app
    LEFT JOIN scholarships sc ON app.scholarship_id = sc.id
    LEFT JOIN applicant a ON app.user_id = a.id
    WHERE a.name LIKE ?
    OR sc.name LIKE ?
    OR app.application_status LIKE ? 
    OR app.date_submitted LIKE ?
    OR app.reason_for_applying LIKE ?
    OR app.careerGoals LIKE ?";

    $result = $db->select($query, [
        $searchKeyword, 
        $searchKeyword,
        $searchKeyword,
        $searchKeyword,
        $searchKeyword,
        $searchKeyword
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