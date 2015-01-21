<?php
include '../vendor/autoload.php';
class Message {

    public static function messages($msg) {
        if (isset($_SESSION['e'])) {
            $_SESSION['e'] += $msg;
        } else {
            $_SESSION['e'] = $msg;
        }
    }
    public static function messages_S($msg) {
        if (isset($_SESSION['s'])) {
            $_SESSION['s'] += $msg;
        } else {
            $_SESSION['s'] = $msg;
        }
    }
    public static function sendEmail($msg,$adresseMail,$titre,$headers) {
        try {
	$mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = TRUE;
    $mail->Username = "adrien.huygens@gmail.com";
    $mail->Password = "";
    $mail->SMTPSecure = "ssl";
    $mail->Port = 465;
    //$mail->SMTPDebug = 1;

    $mail->From = "adrien.huygens@gmail.com";
    $mail->FromName = "Adrien";
    $mail->addAddress($adresseMail);

    $mail->isHTML(true);

    $mail->Subject = $titre;
    $mail->Body = $msg;
    $mail->AltBody = $msg;

    if ($mail->send()) {
        Message::messages_S("Mail envoyer");
    } else {
       
        var_dump($mail);
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
    }
    

}
