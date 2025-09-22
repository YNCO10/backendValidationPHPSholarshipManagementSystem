<?php


require_once __DIR__ . '/../vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function sendNotification($toEmail, $title, $msg){
    $mail = new PHPMailer(true);

    try{
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = "yancokamphandule@gmail.com";
        $mail->Password = "yitp vujw gsti qpcx";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom("yancokamphandule@gmail.com", "Scholarship Management System");
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = $title;
        $mail->Body = $msg;
        
        if($mail->send()){
            return true;
        }
        else{
            return false;
        }
        

    }
    catch(Exception $e){
        // echo "Exception error $e";
        echo json_encode([
            "status"=>"error",
            "message"=>"Exception Error: $e"
        ]);
        return false;
    }
}
?>