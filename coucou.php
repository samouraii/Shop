<?php
require "PHPMailer/PHPMailerAutoload.php";
    $mail = new PHPmailer();
    $mail->IsSMTP();
    $mail->IsHTML(true);
    $mail->Host='smtp.gmail.com';  //aussi essayé smtp.mondomaine.com
    $mail->Port=465;
    $mail->Username = 'test.costa70@gmail.com';      // SMTP login
    $mail->Password = 'Azertyuiop1234';        // SMTP password
    $mail->SMTPAuth = true;      // Active l'uthentification par smtp
    $mail->SMTPSecure = 'ssl';
    $mail->SMTPDebug = 1;
    $mail->From='test.costa70@gmail.com';
    $mail->AddAddress('dannystaquet@hotmail.com');
    $mail->AddReplyTo('test.costa70@gmail.com');
    $mail->Subject='Confirmation du compte';
    $mail->Body='<html><body><head><style>.entete{background-color:#0000FF;color:#FFFFFF;border:solid 3px;font-size:25px}';
    $mail->Body.='.ligne{color:#0000FF;border:solid 1px;text-align:center;font-size:23px}</style></head>';
    $mail->Body.='<center><table><tr><td class="entete">Veuillez confirmer votre compte</td></tr>';
    $mail->Body.='<tr><td class="ligne"><a href="http://localhost/Site_ecommerce/confirmationemail.php?Token=">http://localhost/Site_ecommerce/confirmationemail.php?Token=0</a></td></tr></table></center></body></html>';

    if(!$mail->Send()){ //Teste si le return code est ok.
        echo "couou";
        echo $mail->ErrorInfo; //Affiche le message d'erreur
        echo 'erreur';
    }
    else{
        echo 'Mail envoyé avec succès';
    }
    $mail->SmtpClose();
    unset($mail);