<?php

include ("functions.php");

date_default_timezone_set('Europe/Rome');
$servername = "10.8.0.10";
$username = "pick";
$password = "Pick.2017";
$db = "asteriskcdrdb";

$oggi = date("Y-m-d");

// creo connessione
$conn = new mysqli($servername, $username, $password,$db);

// controllo connessione
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
        $sql = "SELECT * FROM cdr_accessi_sum WHERE auth like '%9%' && data_ingresso = curdate()"; //SELECT * FROM cdr_accessi_sum WHERE auth like '%9%' && 
	$result = $conn->query($sql);
	

	// output
	while($row = $result->fetch_assoc()) {
        $conn->query("INSERT INTO acs_dati_ore (azienda, codice, auth, data_ingressi, ore) VALUES ('".$row["nome_azienda"]."', '".$row["pin"]."', '".$row["auth"]."', '".date('Y-m-d', strtotime($row["data_ingresso"]))."', '".calcolaore($row["ingressi"])."')"); // importa dati badge
	}
		

$conn->close();

?>



