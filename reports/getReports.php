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
        $role = $_POST["role"] ?? "";
        $email = $_POST["email"] ?? "";

        // $role = "admin";
        // $email = "yanco@gmail.com";

        if($role == "admin"){
            #get admin id
            $query = "SELECT * FROM `admin` WHERE email = ?";
            $result = $db->select($query, [$email]);
            if(count($result) <= 0){
                echo json_encode([
                    "status" => "error", 
                    "message" => "Admin Id not found. Is email accurate?"
                ]);
                exit;
            }
            $uid = $result[0]["id"];
            
        }
        else if ($role == "applicant"){
            #get admin id
            $query = "SELECT * FROM `applicant` WHERE email = ?";
            $result = $db->select($query, [$email]);
            if(count($result) <= 0){
                echo json_encode([
                    "status" => "error", 
                    "message" => "Applicant Id not found. Is email accurate?"
                ]);
                exit;
            }
            $uid = $result[0]["id"];
            
        }

        $query = "SELECT * FROM reports WHERE user_id = ? AND `role` = ?";
        $data = $db->select($query, [$uid, $role]);

        if(count($result) > 0){
            echo json_encode([
                "status" => "success",
                "data" => $data
            ]);
            exit;
        }

    }
    catch(Exception $e){
        echo json_encode([
            "status" => "error",
            "message" => "Exception: " . $e->getMessage()
        ]);
        exit;
    }
}
else{
    echo json_encode([
        "status" => "error", 
        "message" => "Invalid request method"
    ]);
}
?>