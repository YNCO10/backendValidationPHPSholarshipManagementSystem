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
        $keyword = $_POST["keyword"] ?? "";
        // $keyword = "nacit";
        $searchKeyword = "%$keyword%";

        $query = "SELECT
        s.id,
        s.name AS scholarship_name,
        s.scheme_type,
        s.file_path,
        s.deadline,
        s.descrip,
        s.provider,
        s.financial_amount,
        s.applicantion_link,
        s.provider_email,
        s.scheme_type,
        GROUP_CONCAT(p.perk_name SEPARATOR ', ') AS perks 
        FROM scholarships s
        LEFT JOIN sholarship_perks sp 
        ON s.id = sp.scholarship_id
        LEFT JOIN perks p 
        ON sp.perk_id = p.perk_id
        WHERE `name` LIKE ? 
        OR scheme_type LIKE ?
        OR descrip LIKE ?
        OR `provider` LIKE ?
        OR financial_amount LIKE ?
        OR provider_email LIKE ?";

        $result = $db->select($query, [
            $searchKeyword, 
            $searchKeyword,
            $searchKeyword,
            $searchKeyword,
            $searchKeyword,
            $searchKeyword
        ]);

        if(count($result) > 0){
                echo json_encode([
                    "status" => "success",
                    "data" => $result
                ]);
                exit;
            }
        else{
            echo json_encode([
                "status" => "error", 
                "message" => "No items matched your search. Try using another keyword."
            ]);
            exit;
        }
    }
    catch(Exception $e){
        echo json_encode([
            "status" => "error",
            "message"=> "Exception Error: " . $e->getMessage()
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