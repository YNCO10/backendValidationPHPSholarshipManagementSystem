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
        $email = $_POST["email"] ?? "";
        // $email = "yancokampha@gmail.com";
        

        $query = "SELECT * FROM applicant WHERE email = ?";
        $result = $db->select($query, [$email]);

        if(count($result) <= 0){
            echo json_encode([
                "status"=>"error",
                "message"=> "User Id not found. Check if email is valid."
            ]);
            exit;
        }
        $uid = $result[0]["id"];

        $query = "SELECT 
        app.*,
        a.email as applicant_email,
        a.status
        FROM applications app
        JOIN applicant a ON app.user_id = a.id
        WHERE app.user_id = ?";
        $result = $db->select($query, [$uid]);

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
}
else{
    echo json_encode([
        "status" => "error",
        "message"=> "Invalid request method"
    ]);
}

?>