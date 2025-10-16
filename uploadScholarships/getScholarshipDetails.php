<?php

//show Hidden errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// force json output
header('Content-Type: application/json');

ob_clean();
require_once "C:/XAMPP/htdocs/BackEnd/scholarshipManagement/dbHandler.php";
ob_end_clean();

$db = new database();
$conn = $db->connectToDatabase();


    // $query = "SELECT `id`, `name`, `type`, `file_path`, `deadline` FROM scholarships";
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
    s.subject,
    GROUP_CONCAT(p.perk_name SEPARATOR ', ') AS perks
    FROM scholarships s
    LEFT JOIN sholarship_perks sp 
    ON s.id = sp.scholarship_id
    LEFT JOIN perks p 
    ON sp.perk_id = p.perk_id
    GROUP BY 
	s.id, 
    s.name, 
    s.scheme_type,
    s.file_path,
    s.deadline, 
    s.descrip,
    s.provider, 
    s.financial_amount, 
    s.applicantion_link,
    s.provider_email,
    s.subject;
    ";

    $tblData = $db->select($query, []);

    if(count($tblData) > 0){
        
        echo json_encode([
            "status" => "success",
            "data" => $tblData
        ]);
        exit;
    }
    else{
        
        echo json_encode([
            "status"=>"error", 
            "message"=>"No data Found."
        ]);
        exit;
    }


?>