<head>
<link rel="stylesheet" type="text/css" href="css/baseline.css">
<style>

#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size:small;
    border-collapse: collapse;
    width: 75%;
	margin-left:auto;
	margin-right:auto;
}

#customers td, #customers th {
    border: 1px solid #ddd;
    padding: 4px;
	
}


#customers tr:nth-child(even){background-color:#d2d2d2;opacity:0.9;}
#customers tr:nth-child(odd){background-color:#c2c2c2;opacity:0.9;}

#customers tr:hover {background-color: #bbb;}

#customers th {
    padding-top: 4px;
    padding-bottom: 4px;
    text-align: left;
    background-color: #504Caf;
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

<div class="hit-the-floor">Visualizzazione accessi - PACCHETTI</div></h3><BR>
    
    <form action="" method="post">

       <table id="tabellaricerca">

        
                <TR><TD colspan="8" width="400"><P ALIGN="center"><STRONG>Strumenti di ricerca</STRONG><BR><font size="2">lasciare un campo vuoto equivale ad estrarne tutti i valori</font></P></TD></TR>
                <TR style="font-family:Georgia, Garamond, Serif;font-size:small;">
                        <TD><strong>Nome: </strong></TD>
                        <TD><input name="azienda" style="color:black" value="<?php echo isset($_POST['azienda']) ? $_POST['azienda'] : '' ?>"></TD>
                        <TD><strong>Dal: </strong></TD>
                        <TD><input id="dal" name="dal" style="color:black" value="<?php echo isset($_POST['dal']) ? $_POST['dal'] : '' ?>"></TD>
                        <TD><strong>Al: </strong></TD>
                        <TD><input id="al" name="al" style="color:black" value="<?php echo isset($_POST['al']) ? $_POST['al'] : '' ?>"></TD>
                        <TD><strong>Codice: </strong></TD>
                        <TD><input name="codice" style="color:black" value="<?php echo isset($_POST['codice']) ? $_POST['codice'] : '' ?>"></TD>
                </TR>
                <TR><TD colspan="8" style="color:black;text-align:center"><input type="submit" name="button" value="CERCA"></TD></TR>
        </table>
    </form>

<?php

include ("tech/functions.php");

date_default_timezone_set('Europe/Rome');
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
	$codice = $_POST["codice"];
	$dal = $_POST["dal"];
	$al = $_POST["al"];
	
	
	
	$dal = $dal ?: '1900-01-01 00:00';
	$al = $al ?: '9999-12-31 23:59';
	
        $somma_ore += calcolaore($row["ingressi"]);
	
	$sql = 
                "SELECT * FROM cdr_accessi_sum WHERE nome_azienda LIKE '%".$azienda."%' && pin LIKE '%".$codice."%' && (data_ingresso BETWEEN '".$dal."' AND '".$al."') && src = '3531'";
	$result = $conn->query($sql);
	$num_righe = $result->num_rows;
        

		if ($result->num_rows > 0) {
		echo "<table id=customers><tr><th>NOME/AZIENDA</th><th>DATA</th><th>ACCESSI</th><th>CODICE</th><th>COD. AUT.</th><th>ORE</th></tr>";
		// output
		while($row = $result->fetch_assoc()) {
                $somma_ore += calcolaore($row["ingressi"]);
                echo "<tr><td>".$row["nome_azienda"]."</td><td>".date('d/m/y', strtotime($row["data_ingresso"]))."</td><td>".$row["ingressi"]."</td><td>".$row["pin"]."</td><td>".$row["auth"]."</td><td>".calcolaore($row["ingressi"])."</td></tr>";
		}
		echo "<DIV style=\"font-family:Georgia, Garamond, Serif;font-size:12px;background-color:rgba(200,200,200,0.7);width: 800px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;margin-left:auto;margin-right:auto;\"><P align=center><strong>Ore totali: ".$somma_ore."</strong><BR>La ricerca è stata effettuata con i seguenti parametri:<BR>Nome: <strong>".$azienda."</strong>, nelle date comprese fra <strong>".date('d/m/y H:i', strtotime($dal))."</strong> e <strong>".date('d/m/y H:i', strtotime($al))."</strong>. La ricerca è filtrata per codice di accesso (<strong>".$codice."</strong>) e per ingresso (<strong>".$ingresso."</strong>).</P></DIV> </table>";
		} else {
			echo "<p align=center>Nessun risultato</P>";
				}
	}
else{  
    echo "<p align=center>Nessuna ricerca impostata</p>";
}

$conn->close();
?>