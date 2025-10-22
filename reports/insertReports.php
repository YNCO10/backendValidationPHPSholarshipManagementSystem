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
        $name = $_POST["name"] ?? "";
        $filePath = $_POST["filePath"] ?? "";
        $email = $_POST["email"] ?? "";
        $role = $_POST["role"] ?? "";

        #get email
        $query = "SELECT * FROM `admin` WHERE email = ?";
        $result = $db->select($query, [$email]);

        #check if email is in admin tbl
        if(count($result) <= 0){
            $query = "SELECT * FROM `applicant` WHERE email = ?";
            $result = $db->select($query, [$email]);
            #check if email is in applicant tbl
            if(count($result) <= 0){
                echo json_encode([
                    "status" => "success", 
                    "message" => "Invalid Email! Check if Email entered is accurate."
                ]);
                exit;
            }
            else{
                $uid = $result[0]["id"];
            }
        }
        else{
            $uid = $result[0]["id"];
        }

        $query = "INSERT INTO reports (`name`, user_id, filepath, `role`) VALUES(?,?,?,?)";
        $result = $db->execute($query, [$name, $uid, $filePath, $role]);

        if($result > 0){
            echo json_encode([
                "status" => "success", 
                "message" => "Report Saved!"
            ]);
        }
        else{
            echo json_encode([
                "status" => "error", 
                "message" => "Failed to insert values into report table."
            ]);
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