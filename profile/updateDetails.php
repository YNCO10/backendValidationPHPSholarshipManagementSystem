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
        $category = $_POST["cat"] ?? "";
        $email = $_POST["email"] ?? "";
        $value = $_POST["value"] ?? "";

        $query = "SELECT id FROM `admin` WHERE email = ?";
        $result = $db->select($query, [$email]);

        if(count($result) < 1){
            echo json_encode([
                "status" => "error", 
                "message" => "UID not found"
            ]);
            exit;
        }

        $uid = $result[0]["id"];
        

        if($category == "pass"){
            $hashed_pass = password_hash($value, PASSWORD_DEFAULT);
            $query = "UPDATE `admin` SET pass_word = ? WHERE id = ?";

            $result = $db->execute($query, [$hashed_pass, $uid]);

            if($result > 0){
                echo json_encode([
                    "status" => "success", 
                    "message" => "Password Updated successfully!"
                ]);
                exit;
            }
            else{
                echo json_encode([
                    "status" => "error", 
                    "message" => "Failed to update Password"
                ]);
                exit;
            }
        }
        else if($category == "email"){
            $query = "UPDATE `admin` SET email = ? WHERE id = ?";

            $result = $db->execute($query, [$value, $uid]);

            if($result > 0){
                echo json_encode([
                    "status" => "success", 
                    "message" => "Email Updated successfully!"
                ]);
                exit;
            }
            else{
                echo json_encode([
                    "status" => "error", 
                    "message" => "Email to update Password"
                ]);
                exit;
            }
        }
        else if($category == "username"){
            $query = "UPDATE `admin` SET `name` = ? WHERE id = ?";

            $result = $db->execute($query, [$value, $uid]);

            if($result > 0){
                echo json_encode([
                    "status" => "success", 
                    "message" => "Name Updated successfully!"
                ]);
                exit;
            }
            else{
                echo json_encode([
                    "status" => "error", 
                    "message" => "Name to update Password"
                ]);
                exit;
            }

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

?>