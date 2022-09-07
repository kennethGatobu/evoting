<?php 

use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception; 
  
require_once __DIR__.'/../vendor/autoload.php';

function send_otp_mail($email,$msg,$user)
{  
$mail = new PHPMailer(true); 
  
try { 
  $senderAccount='ictcenter@kca.ac.ke';
  $senderName='KCA University -Evoting System';
  
    $mail->SMTPDebug = 0;                                        
    $mail->isSMTP();                                             
    $mail->Host       = 'smtp.gmail.com';                     
    $mail->SMTPAuth   = true;                              
    $mail->Username   = $senderAccount;                  
    $mail->Password   = '(t.C3nter)';                         
    $mail->SMTPSecure = 'tls';                               
    $mail->Port       = 587;   
    $mail->setFrom($senderAccount, $senderName);  


    $mail->addAddress($email); 
    
       
    $mail->isHTML(true);                                   
    $mail->Subject = 'KCA University - Evoting System Password'; 
    $mail->Body    = 'Dear '.$user.', '.$msg ; 
    $mail->AltBody =$msg ; 
    $mail->send(); 
  // echo "Mail has been sent successfully!"; 
} catch (Exception $e) { 
    echo "Message could not be sent to your email, kindly request the token again";
  //  echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"; 
} 
}
?> 