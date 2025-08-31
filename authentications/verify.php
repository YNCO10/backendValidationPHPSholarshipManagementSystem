<?php
require_once "C:/XAMPP/htdocs/BackEnd/scholarshipManagement/dbHandler.php";

if($_SERVER["REQUEST_METHOD"]==="POST"){

    $token = $_POST["token"] ?? "";

    if($token === ""){
        echo json_encode([
            "status"=>"error",
            "message"=>"No token was given"
        ]);
        exit;
    }

    if($token){
        $db = new database();
        $conn = $db->connectToDatabase();

        $query = "SELECT id FROM `admin` WHERE verify_token = ?";
        $isVerified = $db->select($query,[$token]);

        if($isVerified > 0){
            $query = "UPDATE admin SET verified = 1, verify_token = NULL WHERE id = ?";
            $db->execute($query,[$isVerified[0]["id"]]);

            echo json_encode([
                "status"=>"success",
                "message"=>"Your Account has been Verified"
            ]);
            exit;
        }

    }
    else{
        echo json_encode([
            "status"=>"error",
            "message"=>"Invalid Token"
        ]);
        exit;
    }
}
else{
    echo json_encode([
        "status"=>"error",
        "message"=>"Invalid Request Method"
    ]);
}
?>