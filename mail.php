<?php

include 'vendor/autoload.php';

$mail = new PHPMailer();

try {
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = TRUE;
    $mail->Username = "adrien.huygens@gmail.com";
    $mail->Password = "";
    $mail->SMTPSecure = "ssl";
    $mail->Port = 465;
    //$mail->SMTPDebug = 1;

    $mail->From = "adrien.huygens@gmail.com";
    $mail->FromName = "Ton cul";
    $mail->addAddress('laurent.cardon@jsb.be');

    $mail->isHTML(true);

    $mail->Subject = "Coucou";
    $mail->Body = "This is the HTML";
    $mail->AltBody = "This is my cul";

    if ($mail->send()) {
        echo "So bingo !";
    } else {
        echo "Dans ton cul !";
        var_dump($mail);
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
?>