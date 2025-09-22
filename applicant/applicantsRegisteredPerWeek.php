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
    // $query = "SELECT
    // DATE(date_registered) AS reg_day,
    // COUNT(*) AS num_applicants
    // FROM applicant
    // WHERE YEARWEEK(date_registered, 1) = YEARWEEK(CURDATE(), 1)
    // GROUP BY reg_day
    // ORDER BY reg_day;
    // ";

    $query = "SELECT
    DATE_FORMAT(date_registered, '%a') AS `weekday`,
    COUNT(*) AS num_applicants
    FROM applicant
    WHERE YEARWEEK(date_registered, 1) = YEARWEEK(CURDATE(), 1)
    GROUP BY `weekday`
    ORDER BY FIELD(`weekday`,'Mon','Tue','Wed','Thu','Fri','Sat','Sun');
    ";

    $result = $conn->query($query);

    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[$row['weekday']] = (int)$row['num_applicants'];
    }
    echo json_encode($data);

}
catch(Exception $e){
    echo json_encode([
        "status"=>"error",
        "message"=>"Exception: " . $e->getMessage()
    ]);
}

?>