<?php

//show Hidden errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// force json output
header('Content-Type: application/json');


require_once "C:/XAMPP/htdocs/BackEnd/scholarshipManagement/dbHandler.php";

$db = new database();
$conn = $db->connectToDatabase();

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $category = $_POST["category"] ?? "";
    $questionLimit = 5;

    $query = "SELECT * FROM `questions` WHERE `category` = ? ORDER BY RAND() LIMIT ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $category, $questionLimit);
    $stmt->execute();

    $result = $stmt->get_result();

    $questions = [];
    
    while($row = $result->fetch_assoc()){
        $questions[] = $row;
    }

    echo json_encode([
        "status"=>"success",
        "message"=>"Data retrieved Successfully",
        "data"=>$questions
    ], JSON_UNESCAPED_SLASHES);

    $stmt->close();

}
else{
    echo json_encode([
        "status"=>"error",
        "message"=>"Invalid Request Method"
    ]);
}
?>