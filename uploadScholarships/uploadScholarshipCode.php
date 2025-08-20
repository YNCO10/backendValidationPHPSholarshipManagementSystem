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
    $filename = $_POST["filename"] ?? "";
    $descrip = $_POST["descrip"] ?? "";
    $deadline = $_POST["deadline"] ?? "";
    $email = $_POST["email"] ?? "";

    if(isset($_FILES["document"])){
        $storageDir = __DIR__ . "/docs/uploadedFiles/";

        // create folder if it doesn't exist
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0777, true);
        }

        $UniqueFilename = time() . "_" . basename($_FILES["document"]["name"]);
        $mainFilepath = $storageDir . $UniqueFilename;

        if(move_uploaded_file($_FILES["document"]["tmp_name"], $mainFilepath)){
            $path = $UniqueFilename;

            // get admin id to add into tbl as foreign key
            // $demoEmail = "yanco@gmail.com";

            $query = "SELECT `id` FROM `admin` WHERE email = ?";
            $adminId = $db->select($query, [$email]);

            if(count($adminId) == 0){
                if (ob_get_length()) { ob_clean(); }
                echo json_encode([
                    "status"=>"error", 
                    "message"=>"UserID Not Found."
                ]);
                exit;
            }

            try{
                // insert data into db
                $query = "INSERT INTO scholarships (`name`, `type`, `file_path`, `admin_id`, `deadline`) VALUES (?,?,?,?,?)";

                $insertedValues = $db->execute($query, [$filename, $descrip, $path, $adminId[0]["id"], $deadline]);

                if($insertedValues > 0){
                
                    echo json_encode([
                    "status"=>"success",
                    "message"=>"Scholarship uploaded successfuly"
                    ]);
                }
                else{
                    if (ob_get_length()) { ob_clean(); }
                        echo json_encode([
                            "status"=>"error", 
                            "message"=>"File Upload Failed"
                        ]);
                }

            }
            catch(Exception $e){
                echo json_encode([
                    "status"=>"error",
                    "message"=>"Exception error:". $e
                ]);
            }
        }
    }
    else{
        if (ob_get_length()) { ob_clean(); } 
            echo json_encode([
                "status" => "error", 
                "message" => "No file recieved"
            ]);
    }

}
else{
    if (ob_get_length()) { ob_clean(); } 
        echo json_encode([
            "status" => "error", 
            "message" => "Invalid request method"
        ]);
}

?>