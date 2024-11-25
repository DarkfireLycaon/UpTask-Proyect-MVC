<?php
namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;

class Email {
    protected $email;
    protected $nombre;
    protected $token;


    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;

    }
    public function enviarConfirmacion(){
        $mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'sandbox.smtp.mailtrap.io';
$mail->SMTPAuth = true;
$mail->Port = 2525;
$mail->Username = 'abe8c22a82ead1';
$mail->Password = '5e20317afaf48d';

$mail->setFrom('cuentas@uptask.com');
$mail->addAddress('cuentas@uptask.com', 'uptask.com');
$mail->Subject = 'Confirm your account';

$mail->isHTML(TRUE);
$mail->CharSet = 'UTF-8';

$contenido = '<html>';
$contenido .= "<p><strong>Hi " . $this->nombre . "</strong> You have created your account in uptask, just need to confirm it in the following link</p>";
$contenido .= "<p>Press here: <a href='". $_ENV['APP_URL'] ."confirmar?token=". $this->token ."'>Confirm Account </a> </p>";
$contenido .= "<p> If you haven't created this account, you can ignore this message </p>";
$contenido .= '</html>';

$mail->Body = $contenido;
//enviar mail
$mail->send();
    }
    public function enviarInstrucciones(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'abe8c22a82ead1';
        $mail->Password = '5e20317afaf48d';
        
        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Reset your password';
        
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';
        
        $contenido = '<html>';
        $contenido .= "<p><strong>Hi " . $this->nombre . "</strong> It seems that you have forgotten your password, follorw the next link to reset your password</p>";
        $contenido .= "<p>Press here: <a href='". $_ENV['APP_URL'] ."/restablecer?token=". $this->token ."'>Reset Password </a> </p>";
        $contenido .= "<p> If you haven't created this account, you can ignore this message </p>";
        $contenido .= '</html>';
        
        $mail->Body = $contenido;
        //enviar mail
        $mail->send();
    }
}