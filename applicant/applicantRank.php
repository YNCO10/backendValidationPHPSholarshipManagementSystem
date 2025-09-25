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
    $query = "SELECT `name`, score FROM applicant ORDER BY score DESC";

    // $result = $conn->query($query);

    // $applicants = [];
    // while ($row = $result->fetch_assoc()) {
    //     $applicants[$row['name']] = (float)$row['score'];
    // }

    // echo json_encode([
    //     "status" => "success",
    //     "data"=> $applicants
    // ]);

    $data = $db->select($query, []);

    if(count($data) > 0){
        echo json_encode([
            "status" => "status",
            "data"=> $data
        ]);
    }
    else{
        echo json_encode([
            "status" => "error",
            "message"=> "Select Failed."
        ]);
    }
}
catch(Exception $e){
    echo json_encode([
        "status" => "error",
        "message"=> "Exception Error: " . $e->getMessage()
    ]);
}

?>