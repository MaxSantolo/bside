<?php 

include ("functions.php");
require_once ('simple_html_dom.php');
require 'class/PHPMailerAutoload.php';
require_once  $_SERVER['DOCUMENT_ROOT']."/areaclienti/classes/Log.php";
require_once  $_SERVER['DOCUMENT_ROOT']."/areaclienti/classes/PickLog.php";
require_once  $_SERVER['DOCUMENT_ROOT']."/areaclienti/classes/Mail.php";


include("connect.php"); 
include("connect_prod.php");

$now = (new DateTime('Europe/Rome'))->format('Y-m-d');
$plog = new PickLog();
$mail = new Mail();

$output = $now . ": START>------------------------------------------------------------";

$errlogmsg = "";
$logmsg = "";

$sql = "SELECT * FROM cdr_accessi_sum WHERE auth like '%9%' && data_ingresso = curdate()"; //SELECT * FROM cdr_accessi_sum WHERE auth like '%9%' &&
$result = $conn->query($sql);

$todayhours = json_decode($plog->sql2Text($result));
($conn->error) ? $errlogmsg = "Impossibile caricare le ore di oggi. Errore: " . $conn->error : $logmsg = "Ingressi trovati: " . PHP_EOL . $todayhours;


$output .= "SELECT * FROM cdr_accessi_sum WHERE auth like '%9%' && data_ingresso = curdate()" . PHP_EOL . PHP_EOL;


// output
while($row = $result->fetch_assoc()) {
    $conn->query("INSERT INTO acs_dati_ore (azienda, codice, auth, data_ingressi, ore) VALUES ('".$row["nome_azienda"]."', '".$row["pin"]."', '".$row["auth"]."', '".date('Y-m-d', strtotime($row["data_ingresso"]))."', '".calcolaore($row["ingressi"])."')"); // importa dati badge

    $sqlout = "INSERT INTO acs_dati_ore (azienda, codice, auth, data_ingressi, ore) VALUES ('".$row["nome_azienda"]."', '".$row["pin"]."', '".$row["auth"]."', '".date('Y-m-d', strtotime($row["data_ingresso"]))."', '".calcolaore($row["ingressi"])."')";

    ($conn->error) ? $errlogmsg .= PHP_EOL . "Impossibile eseguire: " . $sqlout . ". Errore: " . $conn->error : $logmsg .= PHP_EOL . $sqlout;
    $output .= $sqlout. PHP_EOL;
}


//.logs action: ORE_ACCESSO
Log::wLog($logmsg . $errlogmsg);
$plog->sendLog(array("app"=>"BSIDE","content"=>$msg . $errlogmsg,"action"=>"ORE_ACCESSO"));
if ($errlogmsg != "") $mail->sendErrorEmail($errlogmsg);

/*//raggruppa codici 010672 (Maretto) e 030973 (Fornario)
$conn->query("UPDATE acs_dati_ore SET codice = '010672' WHERE codice = '030973'"); */

//importa dati badge
$conn->query("UPDATE acs_pacchetti destinazione, (SELECT sum(ore) as sommaore, acs_dati_ore.codice, data_ingressi, data_inizio_pacchetto, acs_pacchetti.codice as codetotest FROM acs_dati_ore, acs_pacchetti WHERE data_ingressi >= data_inizio_pacchetto and acs_dati_ore.codice = acs_pacchetti.codice group BY acs_dati_ore.codice) origine SET destinazione.ore_utilizzate = origine.sommaore WHERE origine.codice = destinazione.codice"); //aggiorna i pacchetti

$output .= PHP_EOL . "UPDATE acs_pacchetti destinazione, (SELECT sum(ore) as sommaore, acs_dati_ore.codice, data_ingressi, data_inizio_pacchetto, acs_pacchetti.codice as codetotest FROM acs_dati_ore, acs_pacchetti WHERE data_ingressi >= data_inizio_pacchetto and acs_dati_ore.codice = acs_pacchetti.codice group BY acs_dati_ore.codice) origine SET destinazione.ore_utilizzate = origine.sommaore WHERE origine.codice = destinazione.codice" . PHP_EOL . PHP_EOL;

//aggiorna dati fop
	$sql3 = "SELECT * FROM acs_pacchetti WHERE cestinato != '1' AND ( data_fine_pacchetto < curdate() OR ( ore_utilizzate >= (ore_totali_pacchetto + delta_ore) AND ore_totali_pacchetto > 0))";
        $result3 = $conn->query($sql3);

        $output .= "SELECT * FROM acs_pacchetti WHERE cestinato != '1' AND ( data_fine_pacchetto < curdate() OR ( ore_utilizzate >= (ore_totali_pacchetto + delta_ore) AND ore_totali_pacchetto > 0))" . PHP_EOL . PHP_EOL;

        if ($result3->num_rows > 0) {
            while($row = $result3->fetch_assoc()) {
                $pintocheck = $row['cod_auth'].$row['codice'];
                $codice = $row['codice'];
                $email = $row['email_notifiche'];
                $conn2->query("DELETE FROM visual_phonebook WHERE pin = '".$pintocheck."'");
                //cancella account area clienti
                $conn->query("DELETE FROM acs_utenti WHERE  pin = '".$codice."'");
                
                //cancella account wi-fi
                $conn_prod_radius->query("DELETE FROM radcheck WHERE username = '".$email."' ");
                $conn_prod_radius->query("DELETE FROM radreply WHERE username = '".$email."' ");
                }
        }
include ('mail_contratto_scaduto.php');

//archivia pacchetti scaduti

$sqlInsertExpContract = "INSERT INTO acs_pacchetti_scaduti (acs_pacchetti_scaduti.azienda, acs_pacchetti_scaduti.codice, acs_pacchetti_scaduti.cod_auth, acs_pacchetti_scaduti.ore_utilizzate, acs_pacchetti_scaduti.ore_totali, acs_pacchetti_scaduti.tipo, acs_pacchetti_scaduti.email_notifiche, acs_pacchetti_scaduti.delta_ore, acs_pacchetti_scaduti.data_inizio, acs_pacchetti_scaduti.data_fine)
    SELECT acs_pacchetti.azienda, acs_pacchetti.codice, acs_pacchetti.cod_auth, acs_pacchetti.ore_utilizzate, acs_pacchetti.ore_totali_pacchetto, acs_pacchetti.tipo, acs_pacchetti.email_notifiche, acs_pacchetti.delta_ore, acs_pacchetti.data_inizio_pacchetto, acs_pacchetti.data_fine_pacchetto
    FROM acs_pacchetti
    WHERE cestinato != '1' AND ( data_fine_pacchetto < curdate() OR ( ore_utilizzate >= (ore_totali_pacchetto + delta_ore) AND ore_totali_pacchetto > 0) )";

$conn->query($sqlInsertExpContract);

if ($conn->error) {$msg = "Impossibile eseguire la archiviazione dei contratti scaduti. Errore: " . $conn->error;
                   $smail = $mail->sendErrorEmail($msg);}
else $msg = "Archiviazione del contratto avvenuta correttamente.";

$plog->sendLog(array("app"=>"BSIDE","content"=>$msg,"action"=>"ARCHIVIA_CONTRATTO"));
Log::wLog($msg);

$output .= $sqlInsertExpContract .PHP_EOL . PHP_EOL;

//cancella pacchetti scaduti da acs_pacchetti

$sqlDelExpContract = "DELETE FROM acs_pacchetti WHERE cestinato != '1' AND ( data_fine_pacchetto < curdate() OR ( ore_utilizzate >= (ore_totali_pacchetto + delta_ore) AND ore_totali_pacchetto > 0))";
$conn->query($sqlDelExpContract);

if ($conn->error) {
    $msg = "Impossibile eliminare i contratti scaduti. Errore: " . $conn->error;
    $smail = $mail->sendErrorEmail($msg);
} else $msg = "Eliminazione dei contratti avvenuta correttamente.";
$plog->sendLog(array("app"=>"BSIDE","content"=>$msg,"action"=>"ARCHIVIA_CONTRATTO"));
Log::wLog($msg);

$output .= $sqlDelExpContract . PHP_EOL . "-------------------------------------------------------<END";

echo $output;

$conn->close();
$conn2->close();
$conn_prod_radius->close();

?>
