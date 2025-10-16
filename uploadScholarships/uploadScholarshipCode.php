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
    $filename = $_POST["name"] ?? "";
    $type =$_POST["type"] ?? "";
    $deadline = $_POST["deadline"] ?? "";
    $descrip = $_POST["descrip"] ?? "";
    $email = $_POST["email"] ?? "";
    $provider = $_POST["provider"] ?? "";
    $subject = $_POST["subject"] ?? "";
    $financialAmount = $_POST["financialAmount"] ?? "";
    $link = $_POST["applicationLink"] ?? "";
    $providerEmail = $_POST["providerEmail"] ?? "";
    $selectedPerks = $_POST["perks"] ?? "";
    $schemeName = $_POST["schemeName"] ?? "";


    if(isset($_FILES["document"])){//this is the file path
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

            $query = "SELECT id FROM schemes WHERE scheme_name = ?";
            $result = $db->select($query, [$schemeName]);
            if(count($result) <= 0){
                echo json_encode([
                    "status"=>"error", 
                    "message"=>"Scheme ID not found. Check if the scheme name was accurate."
                ]);
                exit;
            }
            $schemeID = $result[0]["id"];

            try{
                // insert data into db
                $query = "INSERT INTO scholarships (
                `name`, 
                `scheme_type`, 
                `file_path`, 
                `admin_id`, 
                `deadline`,
                `descrip`,
                `provider`,
                `financial_amount`,
                `applicantion_link`,
                `provider_email`,
                `subject`,
                `scheme_id`
                ) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";

                $insertedValues = $db->execute(
                    $query, 
                    [
                        $filename, 
                        $schemeName, 
                        $path, 
                        $adminId[0]["id"], 
                        $deadline,
                        $descrip,
                        $provider,
                        $financialAmount,
                        $link,
                        $providerEmail,
                        $subject,
                        $schemeID
                    ]
                );

                if($insertedValues > 0){
                    if(empty($selectedPerks) || $selectedPerks ==="[]"){
                        echo json_encode([
                        "status"=>"success", 
                        "message"=>"File uploaded Successfully with no percs."
                        ]);
                        exit;
                    }

                    $scholsrshipID = $conn->insert_id;

                    $perkArray = is_array($selectedPerks) ? $selectedPerks : json_decode($selectedPerks, true);

                    if(empty($perkArray)){
                        echo json_encode([
                        "status"=>"error", 
                        "message"=>"Perk Array is empty. Did you select any scholarship benefits?"
                        ]);
                        exit;
                    
                    }
                    // var_dump($perkArray);
                    // for each perk in the perk arrary
                    foreach($perkArray as $perk){
                        // select the id for that perk
                        $query = "SELECT perk_id FROM perks WHERE perk_name = ?";
                        $perkResult = $db->select($query, [$perk]);

                        if(count($perkResult) > 0){
                            // if it exists we reuse the id
                            $perkID = $perkResult[0]["perk_id"];
                        }
                        else {
                            // else we insert the new entry an extract id from it
                            $query = "INSERT INTO perks (perk_name) VALUES (?)";
                            $db->execute($query, [$perk]);
                            $perkID = $db->getLastInsertId(); 
                        }

                        // then we link scholarship to it's perk
                        $query = "INSERT INTO sholarship_perks (scholarship_id, perk_id) VALUES (?,?)";
                        $db->execute($query, [$scholsrshipID, $perkID]);

                        // echo "Processing perk: $perk\n";

                    }
                    echo json_encode([
                        "status"=>"success", 
                        "message"=>"File uploaded Successfully."
                    ]);
                    exit;
                    
                }
                else{
                    
                    echo json_encode([
                        "status"=>"error", 
                        "message"=>"File Upload Failed(scholarTbl)"
                    ]);
                    exit;
                }

            }
            catch(Exception $e){
                echo json_encode([
                    "status"=>"error",
                    "message"=>"Exception error:". $e
                ]);
                exit;
            }
        }
    }
    else{
        if (ob_get_length()) { ob_clean(); } 
            echo json_encode([
                "status" => "error", 
                "message" => "No file recieved"
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
?>