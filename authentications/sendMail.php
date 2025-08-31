<?php


require_once __DIR__ . '/../vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function sendEmail($toEmail, $token){
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
        $mail->Subject = "verify Your Email";
        $mail->Body = "Hello.
        Here's Your verification code:
            $token
        Use that code to verify your account";
        
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