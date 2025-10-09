<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);

// force json output
header('Content-Type: application/json');

ob_clean();
require_once "C:/XAMPP/htdocs/BackEnd/scholarshipManagement/dbHandler.php";
ob_end_clean();

$db = new database();
$conn = $db->connectToDatabase();

if($_SERVER["REQUEST_METHOD"]==="POST"){

    try{
        $userID = $_POST["id"] ?? "";
        $email = $_POST["email"] ?? "";

        if($userID == ""){
            if($email == ""){
                echo json_encode([
                    "status" => "error",
                    "message" => "No Email or ID was given"
                ]);
                exit;
            }
            else{
                $query = "SELECT * FROM applicant WHERE email = ?";
                $result = $db->select($query, [$email]);

                if(count($result) > 0){
                    $uid = $result[0]["id"];
                    $query = "DELETE FROM applicant WHERE id = ?";
                    $isDeleted = $db->execute($query, [$uid]);

                    if($isDeleted > 0){
                        echo json_encode([
                            "status" => "success", 
                            "message" => "Applicant Has been deleted."
                        ]);
                    }
                    else{
                        
                        echo json_encode([
                            "status" => "error", 
                            "message" => "Applicant deletion Failed"
                        ]);
                    }
                }
            }
        }
        else{
            $query = "DELETE FROM applicant WHERE id = ?";
            $isDeleted = $db->execute($query, [$userID]);

            if($isDeleted > 0){
                echo json_encode([
                    "status" => "success", 
                    "message" => "Applicant Has been deleted."
                ]);
            }
            else{
                
                echo json_encode([
                    "status" => "error", 
                    "message" => "Applicant deletion Failed"
                ]);
            }
        }

    }
    catch(Exception $e){
        echo json_encode([
            "status"=>"error",
            "message"=>"Exception: " . $e->getMessage()
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