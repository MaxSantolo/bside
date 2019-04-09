<head>
<link rel="stylesheet" type="text/css" href="css/baseline.css">
<style type="text/css" media="print">
.dontprint
{ display: none; }
</style>
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

<form action="" method="post">

<table class="dontprint" style="font-family:Georgia, Garamond, Serif;color:white;font-style:bold;background: #4b7880;opacity:0.9;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;margin-left:auto;margin-right:auto;">
<div class="hit-the-floor">Visualizzazione accessi - BOEZIO</div></h3><BR>
	<TR><TD colspan="6" width="400"><P ALIGN="center"><B>Strumenti di ricerca<B><BR><font size="2">lasciare un campo vuoto equivale ad estrarne tutti i valori</font></P><HR></TD></TR>
	<TR>
		<TD><strong>Azienda: </strong></TD>
		<TD><input name="azienda" type="search"></TD>
		<TD><strong>Dal: </strong></TD>
		<TD><input id="dal" name="dal"></TD>
		<TD><strong>Al: </strong></TD>
		<TD><input id="al" name="al"></TD>
  	<TR>
		<TD><strong>Ingresso: </strong></TD>
		<TD><select name="ingresso" id="ingresso"><option value="">Tutti</option><option value="3531">Boezio 4c - BSide</option><option value="3532">Via Boezio, 6</option><option value="3533">Via Boezio, 6 - I piano</option></select></TD>
		<TD><strong>Codice: </strong></TD>
		<TD><input name="codice" type "search"></TD>
		<TD><strong>Tipo: </strong></TD>
		<TD>Tutti <input type="radio" name="tipo" value="ch" checked="checked"/>Ingresso  <input type="radio" name="tipo" value="checkin"/>Uscita <input type="radio" name="tipo" value="checkout"/></TD>
	</TR> 
	<TR>
		<TD colspan="6"><P ALIGN="center"><input type="submit" name="button" value="CERCA"></P></TD>
	</TR> 
  
</table>
</form>

<?php
$servername = "10.8.0.10";
$username = "pick";
$password = "Pick.2017";
$db = "asteriskcdrdb";

// creo connessione
$conn = new mysqli($servername, $username, $password,$db);

// controllo connessione
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
if (isset($_POST["button"])) {
    
	$azienda = $_POST["azienda"];
	$dal = $_POST["dal"];
	$al = $_POST["al"];
	$ingresso = $_POST["ingresso"];
	$codice = $_POST["codice"];
	$dal = $_POST["dal"];
	$al = $_POST["al"];
	$tipo = $_POST["tipo"];
	
	$dal = $dal ?: '1900-01-01 00:00';
	$al = $al ?: '9999-12-31 23:59';
	
	
	$sql = "SELECT * FROM cdr_accessi WHERE stato LIKE '%".$tipo."%' && (nome_azienda LIKE '%".$azienda."%' && nome_azienda NOT LIKE '%Pick Center%') && src LIKE '%".$ingresso."%' && codice_pin LIKE '%".$codice."%' && (calldate BETWEEN '".$dal."' AND '".$al."')";
	$result = $conn->query($sql);
	$num_righe = $result->num_rows;

		if ($result->num_rows > 0) {
		echo '<table style="font-family:helvetica;font-size:11px;width:100%;border-style:solid;border-width:1px"><tr><th>DATA/ORA</th><th>AZIENDA</th><th>ACCESSO</th><th>CODICE</th><th>TIPO</th></tr>';
		// output
		while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["calldate"]."</td><td>".$row["nome_azienda"]."</td><td>".$row["src"]."</td><td>".$row["codice_pin"]."</td><td>".$row["stato"]."</td></tr>";
		}
		echo "<P align=center><strong>Numero di risultati: ".$num_righe."</P></strong></table>";
		} else {
			echo "<p align=center>Nessun risultato</P>";
				}
	}
else{  
    echo "<p align=center>Nessuna ricerca impostata</p>";
}

$conn->close();
?>