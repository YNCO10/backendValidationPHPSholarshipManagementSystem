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
    // make sure all values are added!
    $name = $_POST["name"] ?? "";
    $email= $_POST["email"] ?? "";
    $naitonality= $_POST["nationality"] ?? "";
    $educationLevel= $_POST["education_level"] ?? "";
    $pass_word= $_POST["pass"] ?? "";
    $dob = $_POST["dob"] ?? "";
    $gender = $_POST["gender"] ?? "";
    $phoneNum = $_POST["phone_number"] ?? "";
    $age = $_POST["age"] ?? "";

    $pass_word = (string) $pass_word; 
    $pass_word = trim($pass_word);

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        if (ob_get_length()) { ob_clean(); } 
        echo json_encode([
        "status" => "error",
        "message" => "Invalid email format."
        ]);
        exit;
    }

// check if user is an admin
    $isAdmin = "SELECT * FROM `admin` WHERE email = ?";
    $adminUser = $db->select($isAdmin, [$email]);

    if(count($adminUser) > 0){
        if (ob_get_length()) { ob_clean(); } 
        echo json_encode([
        "status" => "error",
        "message" => "You already have an Admin account."
        ]);
        exit;
    }

// check if acc exists
    $query = "SELECT * FROM `applicant` WHERE email = ?";
    $result = $db->select($query, [$email]);   

    if(count($result)>0){
        if (ob_get_length()) { ob_clean(); } 
        echo json_encode([
            "status"=>"error",
            "message"=>"Email already exists"
        ]);
        exit;
    }
    else{
        $query2 = "SELECT * FROM applicant WHERE phone_num = ?";
        $result2 = $db->select($query2, [$phoneNum]);

        if(count($result2) > 0){
            if (ob_get_length()) { ob_clean(); }
            echo json_encode([
            "status"=>"error",
            "message"=>"Phone Number already exists"
            ]);
            exit;
        }
    }
    

    // HASH PASS
    $hashed_pass = password_hash($pass_word, PASSWORD_DEFAULT);

    // INSERT USER
    $query = "INSERT INTO `applicant` (`name`, `email`, `pass_word`, `age`, `phone_num`, `gender`, `nationality`, `education_level`, `dob`) VALUES (?,?,?,?,?,?,?,?,?)";
    $insertedValues = $db->execute(
        $query,
        [
            $name, $email, $hashed_pass, $age, $phoneNum, $gender, $naitonality, $educationLevel, $dob
        ]
    );

    if($insertedValues > 0){
        if (ob_get_length()) { ob_clean(); } 
        echo json_encode([
            "status" => "success",
            "message" => "Registration successful"
            ]
        );
        exit;
    }
    else {
        if (ob_get_length()) { ob_clean(); } 
        echo json_encode([
            "status" => "error",
            "message" => "Regstration Failed"
        ]);
        exit;
    }
    
        
}
else{
    if (ob_get_length()) { ob_clean(); } 
    echo json_encode([
        "status" => "error", 
        "message" => "Invalid request method"
    ]);
}
$db->close();
?>