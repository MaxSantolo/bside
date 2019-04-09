<?php

include 'connect.php';

$now = (new DateTime("Europe/Rome"))->format('Y-m-d');

$output = $now . "START>--------------------------------------------------";

$pinscaduti = $conn2->query("SELECT * FROM visual_phonebook WHERE scadenza_pin < curdate() and scadenza_pin != '0000-00-00'");

while ($riga = $pinscaduti->fetch_assoc()) {
    
    $id_pin = $riga["id"];
    $oldpin = $riga["pin"];
    $newpin = '55' . substr($oldpin, 2);

    $sql = "UPDATE visual_phonebook SET pin = '".$newpin."' WHERE id ='".$id_pin."'";
    $output .= $sql . PHP_EOL;

    $conn2->query($sql);

}

$output .= PHP_EOL . "---------------------------------------------------------<END";

echo $output;

$conn2->close(); 

?>