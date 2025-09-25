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
    $query ="SELECT nationality, COUNT(*) AS count
    FROM applicant
    GROUP BY nationality;";

    $result = $conn->query($query);

    $nationalityCounts = [];     // array to hold all rows
    while ($row = $result->fetch_assoc()) {
        $nationalityCounts[$row['nationality']] = (int)$row['count'];
    }
    echo json_encode($nationalityCounts);
}
catch(Exception $e){
    echo json_encode([
        "status"=>"error",
        "message"=>"Exception: " . $e->getMessage()
    ]);
}

?>