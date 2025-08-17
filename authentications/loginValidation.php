<?php

header('Content-Type: application/json');

require_once "C:/XAMPP/htdocs/BackEnd/scholarshipManagement/dbHandler.php";

$db = new database();
$conn = $db->connectToDatabase();

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $email = $_POST["email"] ?? "";
    $pass_word = $_POST["pass_word"] ?? "";


    $query = "SELECT pass_word FROM `admin` WHERE email = ?";
    $result = $db->select($query, [$email]);

    if(count($result) === 0){
        echo json_encode([
            "status" => "error", 
            "message" => "Account not found, Please Register."
        ]);
        exit;
    }

    $hashed_pass = $result[0]["pass_word"];

    if(password_verify($pass_word, $hashed_pass)){
        echo json_encode([
            "status" => "success", 
            "message" => "Welcome Back!"
        ]);
    }
    else{
        echo json_encode([
            "status" => "error", 
            "message" => "You have entered a wrong Email or Password."
        ]);
    }


}
else {
    echo json_encode([
        "status" => "error", 
        "message" => "Invalid request method"
    ]);
}

$db->close();

?>