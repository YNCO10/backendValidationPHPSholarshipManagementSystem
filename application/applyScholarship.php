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
        $schoolAttended = $_POST["schoolAttended"] ?? "";
        $gpa = $_POST["gpa"] ?? "";
        $fin_assistance = $_POST["fin_assistance"] ?? "";
        $reasonForApplying = $_POST["reasonForApplying"] ?? "";
        $incomeBracket = $_POST["incomeBracket"] ?? "";
        $careerGoal = $_POST["careerGoal"] ?? "";
        $email = $_POST["email"] ?? "";
        // GET USER ID USING EMAIL
        // $email = "test11@gmail.com";
        $scholsrshipID = 10;
        $getIdQuery = "SELECT id FROM applicant WHERE email = ?";
        $userIdResult = $db->select($getIdQuery, [$email]);

        if(count($userIdResult) > 0){
            $userId = $userIdResult[0]["id"];
        }
        else{
            echo json_encode([
                "status" => "error",
                "message" => "User ID not found."
                ]
            );
        }

        if(!empty($_FILES)){
            //store document
            $storageDir = __DIR__ . "/docs/uploadedFiles/";

            // create folder if it doesn't exist
            if (!is_dir($storageDir)) {
                mkdir($storageDir, 0777, true);
            }

            $docUploaded = 0;

            foreach($_FILES as $key => $file){
                if($file["error"] === UPLOAD_ERR_OK){
                    $uniqueFilename = time() . "_" . basename($file["name"]);
                    $mainFilepath = $storageDir . $uniqueFilename;
                }

                if (move_uploaded_file($file["tmp_name"], $mainFilepath)) {
                    try{
                        $query = "INSERT INTO documents (user_id, file_path, doc_type) VALUES (?, ?, ?)";
                        $docUploaded = $db->execute(
                            $query, 
                            [
                                $userId, 
                                $mainFilepath, 
                                $key
                            ]
                        );
                    }
                    catch(Exception $e){
                        echo json_encode([
                            "status"=>"error", 
                            "message"=>"Exception error: ". $e->getMessage()
                        ]);
                        exit;
                    }
                }
                else{
                    echo json_encode([
                    "status"=>"error", 
                    "message"=>"Document not uploaded"
                    ]);
                    exit;
                }
            }
            if($docUploaded > 0){
                
                $query = "INSERT INTO applications(
                user_id, 
                scholarship_id, 
                school_attended, 
                gpa, 
                fin_assistance, 
                reason_for_applying,
                careerGoals
                )
                VALUES(?,?,?,?,?,?,?)";

                $result = $db->execute(
                    $query, 
                    [
                        $userId,
                        $scholsrshipID,
                        $schoolAttended,
                        $gpa,
                        $fin_assistance,
                        $reasonForApplying,
                        $careerGoal
                    ]);

                if($result > 0){
                    echo json_encode([
                        "status" => "success",
                        "message" => "Your application has been sent."
                        ]
                    );
                    exit;
                }
                else{
                    echo json_encode([
                        "status" => "error", 
                        "message" => "Application Process failed."
                    ]);
                    exit;
                }
            }
            


            // // insert the values into doc tbl
            // $douments = [$transcript, $nationalID, $recommendation_letter, $need];
            // $docNames = ["Transcript", "National ID","Recommendation Letter", "Proof Of Need"];

            // foreach(array_combine($documents,$docNames) as $doc=>$name){

            //     $query = "INSERT INTO documents (user_id, file_path, doc_type) VALUES (?,?,?)";

            //     $result = $db->execute($query, [$userId, $doc, $name]);

            // }
        }
        else{
            echo json_encode([
                "status" => "error", 
                "message" => "No file recieved"
            ]);
            exit;
        }
    }
    catch(Exception $e){
        echo json_encode([
            "status" => "error", 
            "message" => "Excpetion Error: ". $e->getMessage()
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