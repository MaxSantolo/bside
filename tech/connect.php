<?php

date_default_timezone_set('Europe/Rome');

$servername = "10.8.0.10";
$username = "pick";
$password = "Pick.2017";
$db = "asteriskcdrdb";
$db2 = "asterisk";



// creo connessione
$conn = new mysqli($servername, $username, $password, $db); //asteriskcdrdb
$conn2 = new mysqli($servername, $username, $password, $db2); //asterisk

// controllo connessioni
if ($conn->connect_error) { die("Errore connessione Asterisk CDR: " . $conn->connect_error); }

if ($conn2->connect_error) { die("Errore connessione Asterisk: " . $conn->connect_error); }



?>