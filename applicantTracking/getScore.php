<?php

//show Hidden errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// force json output
header('Content-Type: application/json');


require_once "C:/XAMPP/htdocs/BackEnd/scholarshipManagement/dbHandler.php";


$db = new database();
$conn = $db->connectToDatabase();

try{
    $query ="SELECT score FROM applicant";

    $result = $conn->query($query);

    $scores = [];     // array to hold all scores
    while ($row = $result->fetch_assoc()) {
        $scores[] = $row['score'];
    }
    echo json_encode([
        "scores" => $scores
    ]);
}
catch(Exception $e){
    echo json_encode([
        "status"=>"error",
        "message"=>"Exception: " . $e->getMessage()
    ]);
}

?>