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
    $query ="SELECT 
    app.scholarship_id,
    sc.name,
    COUNT(app.scholarship_id) AS count
    FROM applications app
    LEFT JOIN scholarships sc ON app.scholarship_id = sc.id
    GROUP BY app.scholarship_id, sc.name
    ORDER BY count DESC";

    $result = $conn->query($query);

    $scholarships = [];     // array to hold all rows
    while ($row = $result->fetch_assoc()) {
        $scholarships[$row['name']] = (int)$row['count'];
    }
    echo json_encode($scholarships);
}
catch(Exception $e){
    echo json_encode([
        "status"=>"error",
        "message"=>"Exception: " . $e->getMessage()
    ]);
}

?>