<?php

header('Content-Type: application/json');

require_once "C:/XAMPP/htdocs/BackEnd/scholarshipManagement/dbHandler.php";

$db = new database();
$conn = $db->connectToDatabase();

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $email = $_POST["email"] ?? "";
    $pass_word = $_POST["pass_word"] ?? "";


    $query = "SELECT `name`, pass_word FROM `admin` WHERE email = ?";
    $result = $db->select($query, [$email]);

    
    $email = trim($email);

    $pass_word = (string) $pass_word; 
    $pass_word = trim($pass_word);

// check if user is admin
    if(count($result) > 0){

        $hashed_pass = $result[0]["pass_word"];

        // var_dump(bin2hex($pass_word));
        // var_dump(bin2hex($hashed_pass));

        // var_dump("[$pass_word]");
        // var_dump("[$result]");
        // var_dump(password_verify($pass_word, $hashed_pass));
        // exit;

        if(password_verify($pass_word, $hashed_pass)){

            echo json_encode([
            "status" => "admin",
            "adminName" => $result[0]["name"]
            ]);
            exit;
        }
        else{
            echo json_encode([
            "status" => "error", 
            "message" => "You have entered a wrong Email or Password."
            ]);
            exit;
        }
    }

// check if user is applicant
    $isApplicant = "SELECT `name`, pass_word FROM `applicant` WHERE email = ?";
    $applicantUser = $db->select($isApplicant, [$email]);

    if(count($applicantUser) > 0){

        $applicantHashed_pass = $applicantUser[0]["pass_word"];
        // $demoHash = password_hash("shema1234!", PASSWORD_DEFAULT);

        // var_dump(bin2hex($pass_word));
        // var_dump(bin2hex($applicantHashed_pass));

        // var_dump("[$pass_word]");
        // var_dump("[$applicantHashed_pass]");
        // var_dump(password_verify($pass_word, $applicantHashed_pass));
        // exit;

        if(password_verify($pass_word, $applicantHashed_pass)){

            echo json_encode([
                "status" => "applicant", 
                "applicantName" => $applicantUser[0]["name"]
            ]);
            exit;
        }
        else{
            echo json_encode([
            "status" => "error", 
            "message" => "You have entered a wrong Email or Password."
            ]);
            exit;
        }
    }
    else{
        echo json_encode([
            "status" => "error", 
            "message" => "Account not found, Please Register."
        ]);
        exit;
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