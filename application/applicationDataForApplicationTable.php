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
    $query = "SELECT * FROM applications";
    $result = $db->select($query, []);

    if(count($result) > 0){
        echo json_encode([
            "status" => "success",
            "data"=> $result
        ]);
    }
    else{
        echo json_encode([
            "status" => "error",
            "message"=>"Failed to select application data"
        ]);
    }

    
}
catch(Exception $e){
    echo json_encode([
        "status" => "error",
        "message"=> "Exception Error: " . $e->getMessage()
    ]);
}

?>