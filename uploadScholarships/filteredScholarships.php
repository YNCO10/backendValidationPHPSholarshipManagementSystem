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
        $filter = $_POST["filter"] ?? "";
        // $filter = "Computer Science & IT";

        if($filter == "All"){
            $query = "SELECT `name`, `scheme_type` FROM scholarships";

            $data = $db->select($query, []);

            if(count($data) > 0){
                echo json_encode(value: [
                    "status" => "success",
                    "data"=> $data
                ]);
                exit;
            }
            else{
                echo json_encode([
                    "status" => "error",
                    "message"=> "Select Failed."
                ]);
                exit;
            }
        }else{
            $query = "SELECT `name`, `scheme_type` FROM scholarships WHERE `scheme_type` = ?";

            $data = $db->select($query, [$filter]);

            if(count($data) > 0){
                echo json_encode(value: [
                    "status" => "success",
                    "data"=> $data
                ]);
            }
            else{
                echo json_encode([
                    "status" => "error",
                    "message"=> "Failed to select scholarship scheme."
                ]);
            }
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
        "message" => "Invalid request Method"
    ]);
}

?>