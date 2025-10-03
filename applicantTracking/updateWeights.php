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
    try{
        $academic = $_POST["academic"] ?? "";
        $assessment = $_POST["assessment"] ?? "";
        $doc = $_POST["doc"] ?? "";
        $financial = $_POST["financial"] ?? "";

        $query = "UPDATE weights SET academic = ?, assessment = ?, doc = ?, financial = ?";

        $weights = $db->execute($query, [$academic, $assessment, $doc, $financial]);

        if($weights > 0){
            echo json_encode([
                "status" => "success",
                "message"   => "Weights Updated Successfully"
            ]);
        }
        else{
            echo json_encode([
                "status" => "error",
                "message"   => "Failed to update weights"
            ]);
        }
    }
    catch(Exception $e){
        echo json_encode([
            "status" => "error",
            "message"=> "Exception Error: " . $e->getMessage()
        ]);
    }
}
else{
    echo json_encode([
        "status" => "error", 
        "message" => "Invalid request method"
    ]);
}

?>