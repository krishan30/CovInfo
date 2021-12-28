<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once ("mailer/src/Exception.php");
require_once ("mailer/src/PHPMailer.php");
require_once ("mailer/src/SMTP.php");
require_once ("UserProxy.php");


class MailWrapper
{
    public static function sendMail($userProxy,$subject,$message){
        $mail = new PHPMailer();
        try{
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->Username = 'CovInfoSL@gmail.com'; // YOUR gmail email
            $mail->Password = 'CovInfo123'; // YOUR gmail password

            // Sender and recipient settings
            $mail->setFrom('CovInfoSL@gmail.com', 'CovInfo');
            $mail->addAddress($userProxy->getEmailAddress(), $userProxy->getFirstName()." ".$userProxy->getLastName());
            //$mail->addReplyTo('supundhananjaya.518@gmail.com', 'Supun Dhananjaya'); // to set the reply to

            // Setting the email content
            $mail->IsHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->AltBody = $message;

            $mail->send();
        } catch (Exception $e) {

        }

    }



}


