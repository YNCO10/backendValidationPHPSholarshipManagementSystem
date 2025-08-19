<?php

//show Hidden errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// force json output
header('Content-Type: application/json');


require_once "C:/XAMPP/htdocs/BackEnd/scholarshipManagement/dbHandler.php";
$db = new database();
$conn = $db->connectToDatabase();

if($_SERVER["REQUEST_METHOD"]==="POST"){
    $name = $_POST["name"] ?? "";
    $email = $_POST["email"] ?? "";
    $pass_word = $_POST["pass"] ?? "";


    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo json_encode([
        "status" => "error",
        "message" => "Invalid email format."
    ]);
    exit;
    }
    // check if user is an applicant
    $isApplicant = "SELECT * FROM `applicant` WHERE email = ?";
    $applicantUser = $db->select($isApplicant, [$email]);

    if(count($applicantUser) > 0){
        echo json_encode([
        "status" => "error",
        "message" => "You already have an Applicant account."
    ]);
    exit;
    }


// check if acc exists
    $query = "SELECT * FROM `admin` WHERE email = ?";
    $result = $db->select($query, [$email]);

    if(count($result)>0){
        echo json_encode([
            "status"=>"error",
            "message"=>"Email already exists"
        ]);
        exit;
    }

    // HASH PASS
    $hashed_pass = password_hash($pass_word, PASSWORD_DEFAULT);


    // insert user
    $query = "INSERT INTO `admin` (`name`, `email`, `pass_word`) VALUES (?,?,?)";
    $insertedValues = $db->execute($query, [$name, $email, $hashed_pass]);


    if($insertedValues){
        echo json_encode([
            "status" => "success",
            "message" => "Registration successful"
        ]);
    }
    else {
        echo json_encode([
            "status" => "error",
            "message" => "Registration failed"
        ]);
    }

}
else {
    echo json_encode([
        "status" => "error", 
        "message" => "Invalid request method"
    ]);
}
$db->close()
?>