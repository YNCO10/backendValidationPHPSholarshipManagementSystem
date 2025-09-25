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
    $query = "SELECT gender, COUNT(*) AS count FROM applicant GROUP BY gender;";
    $result = $db->select($query, []);

    $result = $conn->query($query);

    $gender = [];     // array to hold all rows
    while ($row = $result->fetch_assoc()) {
        $gender[$row['gender']] = (int)$row['count'];
    }
    echo json_encode($gender);
}
catch(Exception $e){
    json_encode([
        "status" => "error",
        "message" => "Exception: " . $e->getMessage()
    ]);
}

?>