<?php


include 'tech/connect_prod.php';

$data = '2018-05-01';

echo date('d/m/Y', strtotime($data));
echo "<BR>";
$data = date('Y-m-d', strtotime($data. '+30 days'));
echo $data;


//creo contratto nel sistema a punti
//$conn_prod_punti->query("INSERT INTO anagrafica_punti (id_cliente_dom2, data_inizio, data_fine, nome, email, risorse, data_scadenza) VALUES ('B".$codice."','".$dal."','".$al."','".$azienda."','".$email."','[".$tipo_pacchetto."]','".date('Y-m-d', strtotime($al. '+90 days'))."')");



$messaggio .= "<BR>La creazione del contratto per il sistema a punti è andata a buon fine.<BR>";

$datacc = $dal;

$trovaid = $conn_prod_punti->query("SELECT id FROM anagrafica_punti WHERE id_cliente_dom2 = 'B".$codice."'")->fetch_assoc();

while ($datacc < $al) { //finchè la data di accredito è minore della fine del contratto

    $conn_prod_punti->query("INSERT INTO accrediti (id_cliente, data_accredito, punti, accreditato, scadenza) VALUES ('".$trovaid['id']."', '".$datacc."', '5', 'In attesa', '".date('Y-m-d', strtotime($datacc. '+60 days'))."' )");

    $messaggio .= "Accredito di 5 punti predisposto per il " .date('d/m/Y', strtotime($datacc)) ."<BR>";

    $datacc = date('Y-m-d', strtotime($datacc . '+30 days'));


}




?>