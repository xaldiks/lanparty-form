<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "libs/phpmailer/PHPMailer/src/Exception.php";
require "libs/phpmailer/PHPMailer/src/PHPMailer.php";
require "libs/phpmailer/PHPMailer/src/SMTP.php";

// Els correus enviats amb la funció mail() molts cops són interpretats com a spam.
function envia($email,$qr) {
    $assumpteInscrit = 'Inscripció LAN Party Ripoll';
    $assumpteAdmin = 'Nova inscripció a la LAN Party';
    $from = 'info@lanpartyripoll.cat';
    $headers = "From: LAN Party Ripoll <info@lanpartyripoll.cat>\r\n";
    $headers .= "Reply-To: Aniol Martí <amarti@nnbox.org>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= 'X-Mailer: PHP/' . phpversion();
    $message = '<html><body>';
    $message .= '<p>Gràcies per inscriure\'t a la LAN Party Ripoll!</p>';
    $message .= '<p>Recorda que has de portar el teu ordinador amb els perifèrics corresponents, el DNI, el codi QR i l\'autorització si ets menor d\'edat.</p>';
    $message .= '<p>Enllaç per baixar el codi d\'accés:</p>';
    $message .= "$qr";
    $message .= '<br>';
    $message .= '<br>';
    $message .= '<p>Ens veiem el dia 5,</p>';
    $message .= '<p>L\'organització de la LAN Party</p>';
    $message .= '</body></html>';
    $messageAdmin = '<html><body>';
    $messageAdmin .= '<p>Nova inscripció a la LAN Party!</p>';
    //mail($email, $assumpteInscrit, $message, $headers); // Comento aquest enviament ja que crido la funció envia() per enviar el correu-e a l'organització.
    mail('amarti@ltec.cat', $assumpteAdmin, $messageAdmin, $headers);

}

// Afegeixo una funció que els envia per SMTP. El correu-e per l'organització, com que el més lògic és que sigui del propi domini i/o servidor el deixo amb la funció mail().
function enviasmtp($email,$qr) {
    $mail = new PHPMailer;
    $mail->CharSet = "UTF-8";
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'mail.xarxacatala.cat';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'info@lanpartyripoll.cat';                 // SMTP username
    $mail->Password = 'password';                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to

    $mail->setFrom('info@lanpartyripoll.cat', 'LAN Party Ripoll');
    $mail->addAddress($email);               // Name is optional
    $mail->addReplyTo('aniol.marti@riseup.net', 'Aniol Martí');
    $mail->addBCC('aniol.marti@riseup.net', 'Aniol Martí');

    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = 'Inscripció LAN Party Ripoll';
    $mail->Body    = "<p>Gràcies per inscriure't a la LAN Party Ripoll!</p>
                      <p>Recorda que has de portar el teu ordinador amb els perifèrics corresponents, el DNI, el codi QR i l'autorització si ets menor d'edat.</p>
                      <p>Enllaç per baixar el codi d'accés:</p>
                      {$qr}
                      <br><br>
                      <p>Ens veiem el dia 20,</p>
                      <p>L'organització de la LAN Party</p>";

    $mail->AltBody = "Gràcies per inscriure't a la LAN Party Ripoll!
                      Recorda que has de portar el teu ordinador amb els perifèrics corresponents, el DNI, el codi QR i l'autorització si ets menor d'edat.
                      Enllaç per baixar el codi d'accés:
                      {$qr}

                      Ens veiem el dia 20,
                      L'organització de la LAN Party";

    if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }

    else {
        envia($email,$qr);
    }

}

?>
