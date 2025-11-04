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
    $query ="SELECT scheme_type, COUNT(*) AS count
    FROM scholarships
    GROUP BY scheme_type";

    $result = $conn->query($query);

    $scheme = [];     // array to hold all rows
    while ($row = $result->fetch_assoc()) {
        $scheme[$row['scheme_type']] = (int)$row['count'];
    }
    echo json_encode(["data"=>$scheme]);
}
catch(Exception $e){
    echo json_encode([
        "status"=>"error",
        "message"=>"Exception: " . $e->getMessage()
    ]);
}

?>