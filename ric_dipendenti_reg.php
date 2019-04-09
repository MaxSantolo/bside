<head>
<link rel="stylesheet" type="text/css" href="css/baseline.css">
<style>

#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size:small;
    border-collapse: collapse;
    width: 90%;
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
    background-color: #AF4c50;
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
<script type="text/javascript" src="tech/jexport/tableExport.js"></script>
<script type="text/javascript" src="tech/jexport/jquery.base64.js"></script>


<body>
<div class="hit-the-floor">Visualizzazione accessi - DIPENDENTI (Regolo)</div></h3><BR>
    
    <form action="" method="post">

        <table id="tabellaricerca">
        
        
                <TR><TD colspan="10" width="400"><P ALIGN="center"><STRONG>Strumenti di ricerca</STRONG><BR><font size="2">lasciare un campo vuoto equivale ad estrarne tutti i valori</font></P></TD></TR>
                <TR style="font-family:Georgia, Garamond, Serif;font-size:small;">
                        <TD><strong>Nome: </strong></TD>
                        <TD><input name="azienda" style="color:black" value="<?php echo isset($_POST['azienda']) ? $_POST['azienda'] : '' ?>"></TD>
                        <TD><strong>Dal: </strong></TD>
                        <TD><input type="date" name="dal" style="color:black" value="<?php echo isset($_POST['dal']) ? $_POST['dal'] : '' ?>"></TD>
                        <TD><strong>Al: </strong></TD>
                        <TD><input type="date" name="al" style="color:black" value="<?php echo isset($_POST['al']) ? $_POST['al'] : '' ?>"></TD>
                        <TD><strong>Badge: </strong></TD>
                        <TD><input name="codice" style="color:black" value="<?php echo isset($_POST['codice']) ? $_POST['codice'] : '' ?>"></TD>
                        <!-- <TD><strong>Centro: </strong></TD>    
                        <TD><select name="centro" style="color:black"><option value="TUTTI">TUTTI</option><option value="BOEZIO">BOEZIO</option><option value="MARCONI">MARCONI</option></TD> -->
                </TR>
                <TR><TD colspan="10" style="color:black;text-align:center"><input type="submit" name="button" value="CERCA"></TD></TR>
        </table>
    </form>

<?php

include ("tech/functions.php");
include ('tech/connect.php');
include ('tech/connect_prod.php');
    
if (isset($_POST["button"])) {
    
	
        $azienda = $_POST["azienda"];
	$codice = $_POST["codice"];
	$dal = $_POST["dal"];
	$al = $_POST["al"];
	
	
	
	$dal = $dal ?: '1900-01-01 00:00';
	$al = $al ?: '9999-12-31 23:59';
	

	
	$sql = "SELECT * FROM cdr_accessi_reg_sum WHERE (nome LIKE '%".$azienda."%' OR cognome LIKE '%".$azienda."%') AND (data_accesso BETWEEN '".$dal."' AND '".$al."') AND badge LIKE '%".$badge."%' ORDER BY badge ASC, data_accesso ASC";
	$result = $conn_prod_intranet->query($sql);
	$num_righe = $result->num_rows;

		if ($result->num_rows > 0) {
		echo "<table id=customers><tr><thead><th>NOME</th><th>COGNOME</th><th>DATA</th><th>ACCESSI</th><th>BADGE</th></thead></tr>";
		// output
		while($row = $result->fetch_assoc()) {
                

                
                echo "<tr><td>".$row["nome"]."</td><td>".$row["cognome"]."</td><td>".date('l d/m/y', strtotime($row["data_accesso"]))."</td><td width=\"500\">".$row["ingressi"]."</td><td>".$row["badge"]."</td></tr>";
		}
				echo "<P align=center><a href=\"#\" onclick=\"$('#customers').tableExport({type:'excel',escape:'false'});\"><img src='images/export_xls.png' width=48 title='ESPORTA IN EXCEL' align='center'></a><a href=\"#\" onclick=\"stampa()\"><img src='images/export_print.png' width=48 title='STAMPA' align='center'></a>"
                        . "<BR><strong>Numero di risultati: ".$num_righe."</strong><BR>"
                        . "</p></table>";
                
		} else {
			echo "<DIV style=\"font-family:Georgia, Garamond, Serif;font-size:12pt;background-color:rgba(200,200,200,0.7);border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;margin-left:auto;margin-right:auto; width: 40%;\"><p align=center><b>Nessun risultato</b></P></div>";
				}
	}
else{  
    echo "<DIV style=\"font-family:Georgia, Garamond, Serif;font-size:12pt;background-color:rgba(200,200,200,0.7);border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;margin-left:auto;margin-right:auto;width: 40%;\"><p align=center><b>Nessuna ricerca impostata</b></p></div>";

}

$conn->close();
$conn2->close();
$conn_prod_intranet->close();
?>

<?php if (isset($_POST["button"])) { echo "<BR><P align='center'><a href=\"#\" onclick=\"$('#customers').tableExport({type:'excel',escape:'false'});\"><img src='images/export_xls.png' width=64 title='ESPORTA IN EXCEL'></a><a href=\"#\" onclick=\"stampa()\"><img src='images/export_print.png' width=64 title='STAMPA'></a></p>"; } ?>

<script>
function stampa() {
    window.print();
}
</script>