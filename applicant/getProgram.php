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
    $query = "SELECT DISTINCT `program` FROM applicant ORDER BY `program`";
    
    $result = $conn->query($query);

    $programs = [];
    while ($row = $result->fetch_assoc()) {
        $programs[] = $row['program'];
    }

    echo json_encode([
        "status" => "success",
        "data"   => $programs
    ]);
}
catch(Exception $e){
    echo json_encode([
        "status" => "error",
        "message"=> "Exception Error: " . $e->getMessage()
    ]);
}

?>