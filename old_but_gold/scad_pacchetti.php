<head>
<link rel="stylesheet" type="text/css" href="css/baseline.css">
<style>



table.customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size:small;
    border-collapse: collapse;
    width: 75%;
	margin-left:auto;
	margin-right:auto;
}

table.customers td, table.customers th {
    border: 1px solid #ddd;
    padding: 4px;
	
}


table.customers tr:nth-child(even){background-color:#d2d2d2;opacity:0.9;}
table.customers tr:nth-child(odd){background-color:#c2c2c2;opacity:0.9;}

table.customers tr:hover {background-color: #bbb;}

table.customers th {
    padding-top: 4px;
    padding-bottom: 4px;
    text-align: left;
    background-color: #4c4Cff;
	opacity:0.9;
    color: white;
}


body {
  /* Location of the image */
  background-image: url(images/sfondo.jpg);
  
  /* Background image is centered vertically and horizontally at all times */
  background-position: center center;
  
  /* Background image doesn't tile */
  background-repeat: no-repeat;
  
  /* Background image is fixed in the viewport so that it doesn't move when 
     the content's height is greater than the image's height */
  background-attachment: fixed;
  
  /* This is what makes the background image rescale based
     on the container's size */
  background-size: cover;
  
  /* Set a background color that will be displayed
     while the background image is loading */
  background-color: #464646;
}

</style>
<?php include('tech/navbar/navbarca.php'); ?>
</head>

<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>  
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>  
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script> 
<script type="text/javascript">
       $(function() {
               $("#dal").datepicker({ dateFormat: "yy-mm-dd 00:00" }).val()
               $("#al").datepicker({ dateFormat: "yy-mm-dd 23:59" }).val()
       });
</script>

<body>
<div class="hit-the-floor">Pacchetti in scadenza</div><BR>
    
<?php

include ("tech/functions.php");
include ("tech/connect.php");
 
  
        $sql = "SELECT * FROM acs_pacchetti WHERE cestinato != '1' && ((data_fine_pacchetto - curdate())/(data_fine_pacchetto-data_inizio_pacchetto))< 0.2 && data_fine_pacchetto > curdate()" ;
        $result = $conn->query($sql);
        echo "<table id=customers><TR><TH colspan=14 STYLE=\"font-family:Verdana;color:white;text-align: center;font-size:medium;background-color:#0085ff;\">PACCHETTI IN SCADENZA PER DATA</TH></TR><tr><th>ID</th><th>NOME/AZIENDA</th><th>AUTH</th><th>CODICE</th><th>TIPO</th><th>DATA INIZIO</th><th>DATA TERMINE</th><th>ORE UTILIZZATE</th><th>ORE GRATUITE</th><th>EMAIL NOTIFICA</th><th colspan=4 style=\"text-align:right\"></th></TR>";
		// output
	while($row = $result->fetch_assoc()) {
              
        echo "<tr><td>".$row["id_pacchetto"]."</td><td>".$row["azienda"]."</td><td>".$row["cod_auth"]."</td><td>".$row["codice"]."</td><td>".$row["tipo"]."</td><td>".date('d/m/y', strtotime($row["data_inizio_pacchetto"]))."</td><td>".date('d/m/y', strtotime($row["data_fine_pacchetto"]))."</td><td>".$row["ore_utilizzate"]."</td><td>".$row["delta_ore"]."</td><td>".$row["email_notifiche"]."</td>"
                . "<td width=32><a href='mod_pacchetto.php?id=".$row['id_pacchetto']."'><IMG SRC=\"images/file_edit.png\" border=0 width=24 alt=\"Modifica\"></a></td>"
                . "<td width=32><IMG SRC=\"images/file_archive.png\" border=0 width=24 alt=\"Consumi passati\"></td>"
                . "<td width=32><a href='mail_pacchetto.php?id=".$row['id_pacchetto']."' onclick='return confirm(\"Inviare dettaglio a ".$row["azienda"]." (ID # ".$row['id_pacchetto'].")?\")'><IMG SRC=\"images/file_send.png\" border=0 width=24 alt=\"Invia dettaglio per email\"></a></td>"
                . "<td width=32><a href='canc_pacchetto.php?id=".$row['id_pacchetto']."&pin=".$row['codice']."' onclick='return confirm(\"Sicuro di voler eliminare il pacchetto di ".$row["azienda"]." (ID # ".$row['id_pacchetto'].")?\")'><IMG SRC=\"images/file_delete.png\" border=0 width=24 alt=\"Elimina pacchetto\"></a></td></tr>";
	};
        echo "</table><P><HR width=400><p>";
        
        $sql2 = "SELECT * FROM acs_pacchetti WHERE cestinato != '1' && ore_utilizzate / (ore_totali_pacchetto + delta_ore) > 0.8 &&  ore_utilizzate < (ore_totali_pacchetto + delta_ore) && data_fine_pacchetto > curdate()";
        $result = $conn->query($sql2);
        echo "<table id=customers><TR><TH colspan=14 STYLE=\"font-family:Verdana;color:white;text-align: center;font-size:medium;;background-color:#0085ff;\">PACCHETTI IN SCADENZA PER NUMERO DI ORE</TH></TR><tr><th>ID</th><th>NOME/AZIENDA</th><th>AUTH</th><th>CODICE</th><th>TIPO</th><th>DATA INIZIO</th><th>DATA TERMINE</th><th>ORE UTILIZZATE</th><th>ORE GRATUITE</th><th>EMAIL NOTIFICA</th><th colspan=4 style=\"text-align:right\"></th></TR>";
		// output
	while($row = $result->fetch_assoc()) {
              
        echo "<tr><td>".$row["id_pacchetto"]."</td><td>".$row["azienda"]."</td><td>".$row["cod_auth"]."</td><td>".$row["codice"]."</td><td>".$row["tipo"]."</td><td>".date('d/m/y', strtotime($row["data_inizio_pacchetto"]))."</td><td>".date('d/m/y', strtotime($row["data_fine_pacchetto"]))."</td><td>".$row["ore_utilizzate"]."</td><td>".$row["delta_ore"]."</td><td>".$row["email_notifiche"]."</td>"
                . "<td width=32><a href='mod_pacchetto.php?id=".$row['id_pacchetto']."'><IMG SRC=\"images/file_edit.png\" border=0 width=24 alt=\"Modifica\"></a></td>"
                . "<td width=32><IMG SRC=\"images/file_archive.png\" border=0 width=24 alt=\"Consumi passati\"></td>"
                . "<td width=32><a href='mail_pacchetto.php?id=".$row['id_pacchetto']."' onclick='return confirm(\"Inviare dettaglio a ".$row["azienda"]." (ID # ".$row['id_pacchetto'].")?\")'><IMG SRC=\"images/file_send.png\" border=0 width=24 alt=\"Invia dettaglio per email\"></a></td>"
                . "<td width=32><a href='canc_pacchetto.php?id=".$row['id_pacchetto']."&pin=".$row['codice']."' onclick='return confirm(\"Sicuro di voler eliminare il pacchetto di ".$row["azienda"]." (ID # ".$row['id_pacchetto'].")?\")'><IMG SRC=\"images/file_delete.png\" border=0 width=24 alt=\"Elimina pacchetto\"></a></td></tr>";
	};


$conn->close();
?>