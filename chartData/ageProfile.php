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
    $query = "SELECT
    CASE
    WHEN age < 20 THEN 'Under 20'
    WHEN age BETWEEN 20 AND 29 THEN '20-29'
    WHEN age BETWEEN 30 AND 39 THEN '30-39'
    ELSE '40+'
    END AS age_group,
    COUNT(*) AS count
    FROM applicant
    GROUP BY age_group;";

    $result = $conn->query($query);

    $ageProfile = [];     // array to hold all rows
    while ($row = $result->fetch_assoc()) {
        $ageProfile[$row['age_group']] = (int)$row['count'];
    }
    echo json_encode(["data"=>$ageProfile]);
}
catch(Exception $e){
    echo json_encode([
        "status"=>"error",
        "message"=>"Exception: " . $e->getMessage()
    ]);
}

?>