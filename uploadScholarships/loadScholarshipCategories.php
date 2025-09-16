<?php

//show Hidden errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// force json output
header('Content-Type: application/json');

ob_clean();
require_once "C:/XAMPP/htdocs/BackEnd/scholarshipManagement/dbHandler.php";
ob_end_clean();

$db = new database();
$conn = $db->connectToDatabase();

try{
    $query ="SELECT financial_amount, COUNT(*) AS occurrences FROM scholarships GROUP BY financial_amount";

    $result = $conn->query($query);

    $countryCounts = [];     // array to hold all rows
    while ($row = $result->fetch_assoc()) {
        $countryCounts[$row['financial_amount']] = (int)$row['occurrences'];
    }
    echo json_encode($countryCounts);
}
catch(Exception $e){
    echo json_encode([
        "status"=>"error",
        "message"=>"Exception" . $e->getMessage()
    ]);
}


?>