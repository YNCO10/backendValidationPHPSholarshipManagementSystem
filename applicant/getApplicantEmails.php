<?php

//show Hidden errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// force json output
header('Content-Type: application/json');


require_once "C:/XAMPP/htdocs/BackEnd/scholarshipManagement/dbHandler.php";
require_once "C:/XAMPP/htdocs/BackEnd/scholarshipManagement/notifications/sendNotification.php";


$db = new database();
$conn = $db->connectToDatabase();


try{
    $sql = "SELECT email FROM applicant";
    $result = $conn->query($sql);

    $emails = [];
    while ($row = $result->fetch_assoc()) {
        $emails[] = $row['email'];
    }

    echo json_encode([
        "status" => "success",
        "data"=> $emails
    ]);
}
catch(Exception $e){
    echo json_encode([
        "status" => "error",
        "message"=> "Exception Error: " . $e->getMessage()
    ]);
}

?>