<?php
require 'tech/class/PHPMailerAutoload.php';

$mail = new PHPMailer();

$mail->IsSMTP();   
$mail->CharSet="UTF-8"; // set mailer to use SMTP
$mail->Host = "10.20.20.227";  // specify main and backup server
$mail->SMTPAuth = false;     // turn on SMTP authentication
// $mail->SMTPSecure = 'tsl';
$mail->Port = 25;
//$mail->Username = "info@pickcenter.com";  // SMTP username
//$mail->Password = "fm105pick"; // SMTP password

$mail->From = "src.concorsi@beniculturali.it";

$mail->FromName = "Ufficio concorsi Ministero Beni Culturali";
$mail->AddAddress("ilia.virzi@gmail.com","PG");
                 // name is optional
//$mail->AddReplyTo("info@bsidecoworking.it", "Informazioni");

$mail->WordWrap = 50;                                 // set word wrap to 50 characters
//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
$mail->IsHTML(true);                                  // set email format to HTML

$mail->Subject = "Posizione II/OOEP001#2017";
$mail->Body    = "<p><img src=\"http://www.beniculturali.it/mibac/export/system/modules/it.inera.opencms.templates/MiBAC/images/layout/header/logoMIBACT.jpg\"></P><p>Gentilissima,<BR>
siamo lieti di comunicarle l'esito positivo (con graduatoria II/XIII) della prova di concorso relativa alla posizione OOEP001#2017.</P>
<P>Il contratto di lavoro, inquadrato nel segmento C1 del CCNL Dipendenti Pubblici, avrà decorrenza dal 01 Maggio 2018 e inizio attività lavorativa entro e non oltre il 01 Settembre 2018.</P>
Le possiamo confermare inoltre che sarà alle dipendenze dell'<strong>Archeol. Alfonsina RUSSO</strong> presso:</p>
<P><strong>SOPRINTENDENZA ARCHEOLOGIA, BELLE ARTI E PAESAGGIO PER L'AREA METROPOLITANA DI ROMA LA PROVINCIA DI VITERBO E L'ETRURIA MERIDIONALE<br>
Via Cavalletti, 2 - 00186 ROMA</strong></p>
<p>Riceverà il contratto via raccomandata R1, entro il 30 Marzo 2018, salvo diverse disposizioni che le saranno in caso comunicate.</P>
<P>Le rinnoviamo le nostre confratulazioni.</p>
<P>Cordiali saluti,<BR>
Lo staff di selezione p.c. Dott. D. Franceschini</P>
<hr>
Si ricorda che questa è una mail di solo invio e che il contenuto è strettamente confidenziale ed affidato alla cura del destinatario.";
$mail->AltBody = $corpodeltestotxt;
$mail->SMTPDebug = 2;

if(!$mail->Send())
{
   echo "Non sono riuscito a mandare il messaggio.<BR>";
   echo "Errore di invio: " . $mail->ErrorInfo;
   
} else { $mail->ClearAllRecipients(); $mail->ClearAttachments(); }

?>