<?php

include 'connect.php';
require_once  $_SERVER['DOCUMENT_ROOT']."/areaclienti/classes/Log.php";
require_once  $_SERVER['DOCUMENT_ROOT']."/areaclienti/classes/PickLog.php";

$now = (new DateTime("Europe/Rome"))->format('Y-m-d');

$output = $now . "START>--------------------------------------------------";

$pinscaduti = $conn2->query("SELECT * FROM visual_phonebook WHERE scadenza_pin < curdate() and scadenza_pin != '0000-00-00' and pin not like '55%'");

while ($riga = $pinscaduti->fetch_assoc()) {
    
    $id_pin = $riga["id"];
    $oldpin = $riga["pin"];
    $newpin = '55' . substr($oldpin, 2);

    $sql = "UPDATE visual_phonebook SET pin = '".$newpin."' WHERE id ='".$id_pin."'";
    $output .= $sql . PHP_EOL;

    $conn2->query($sql);

}

//.log per action: SCADENZA_CODICE
($conn2->error) ? $logmsg = "Impossibile aggiornare i PIN scaduti. Errore: " . $conn2->error : $logmsg = "Aggiornati " . $pinscaduti->num_rows . " come scaduti.";
Log::wLog($logmsg);
$plog->sendLog(array("app"=>"BSIDE","content"=>$logmsg,"action"=>"SCADENZA_CODICE"));


$output .= PHP_EOL . "---------------------------------------------------------<END";



echo $output;

$conn2->close(); 

?>