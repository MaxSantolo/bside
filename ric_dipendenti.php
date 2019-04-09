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



<div class="hit-the-floor">Visualizzazione accessi - DIPENDENTI</div></h3><BR>
    
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
                        <TD><strong>Codice: </strong></TD>
                        <TD><input name="codice" style="color:black" value="<?php echo isset($_POST['codice']) ? $_POST['codice'] : '' ?>"></TD>
                        <TD><strong>Centro: </strong></TD>    
                        <TD><select name="centro" style="color:black"><option value="TUTTI">TUTTI</option><option value="BOEZIO">BOEZIO</option><option value="MARCONI">MARCONI</option><option value='REGOLO'>REGOLO</option></TD>
                </TR>
                <TR><TD colspan="10" style="color:black;text-align:center"><input type="submit" name="button" value="CERCA"></TD></TR>
        </table>
    </form>

<?php

include ("tech/functions.php");
include ('tech/connect.php');
    
if (isset($_POST["button"])) {
    
            $azienda = $_POST["azienda"];
	$codice = $_POST["codice"];
	$dal = $_POST["dal"];
	$al = $_POST["al"];
	
	
	
	$dal = $dal ?: '1900-01-01 00:00';
	$al = $al ?: '9999-12-31 23:59';
        
        	$centro = $_POST["centro"];
        $intcit = "IN('3531','3532','3533','3535','3831','3832','3833','3834','3331','3332','3333','3334','3335')";
        
            switch ($centro) {
                    case "TUTTI":
                    $intcit = "IN('3531','3532','3533','3535','3831','3832','3833','3834','3331','3332','3333','3334','3335')";
                break;
                    case "BOEZIO":
                    $intcit = "IN('3531','3532','3533','3535')";
                break;
                    case "MARCONI":
                    $intcit = "IN('3831','3832','3833','3834')";
                break;
                    case "REGOLO":
                    $intcit = "IN('3331','3332','3333','3334','3335')";
                break;

                }

         $sql = 
                  "SELECT * FROM cdr_accessi_sum WHERE (nome_azienda LIKE '%".$azienda."%' and nome_azienda NOT LIKE '%fornit%' AND nome_azienda NOT LIKE '%MNTN%' AND nome_azienda LIKE '%PICK%') && pin LIKE '%".$codice."%' && (data_ingresso BETWEEN '".$dal."' AND '".$al."') && auth LIKE '95' AND src ".$intcit." "
                . "     UNION ALL "
                . "SELECT * FROM cdr_accessi_eur_sum WHERE (nome_azienda LIKE '%".$azienda."%' and nome_azienda NOT LIKE '%fornit%' AND nome_azienda NOT LIKE '%MNTN%' AND nome_azienda LIKE '%PICK%') && pin LIKE '%".$codice."%' && (data_ingresso BETWEEN '".$dal."' AND '".$al."') && auth LIKE '95' AND src ".$intcit." "
                . "     UNION ALL "
                . "SELECT * FROM cdr_accessi_regolo_sum WHERE (nome_azienda LIKE '%".$azienda."%' and nome_azienda NOT LIKE '%fornit%' AND nome_azienda NOT LIKE '%MNTN%' AND nome_azienda LIKE '%PICK%') && pin LIKE '%".$codice."%' && (data_ingresso BETWEEN '".$dal."' AND '".$al."') && auth LIKE '95' AND src ".$intcit." "
                 
                 . " GROUP BY  data_ingresso ORDER BY pin ASC, data_ingresso ASC, nome_azienda ASC ";
        
        
} else { $sql = 
                  "SELECT * FROM cdr_accessi_sum WHERE (nome_azienda NOT LIKE '%fornit%' AND nome_azienda NOT LIKE '%MNTN%' AND nome_azienda LIKE '%PICK%') AND (data_ingresso BETWEEN '".date("Y-m-d", strtotime("first day of this month"))."' AND '".date("Y-m-d", strtotime("last day of this month"))."') && auth LIKE '95'"
                . "     UNION ALL "
                . "SELECT * FROM cdr_accessi_eur_sum WHERE (nome_azienda NOT LIKE '%fornit%' AND nome_azienda NOT LIKE '%MNTN%' AND nome_azienda LIKE '%PICK%') AND (data_ingresso BETWEEN '".date("Y-m-d", strtotime("first day of this month"))."' AND '".date("Y-m-d", strtotime("last day of this month"))."') && auth LIKE '95'"
                . "     UNION ALL "
                . "SELECT * FROM cdr_accessi_regolo_sum WHERE (nome_azienda NOT LIKE '%fornit%' AND nome_azienda NOT LIKE '%MNTN%' AND nome_azienda LIKE '%PICK%') AND (data_ingresso BETWEEN '".date("Y-m-d", strtotime("first day of this month"))."' AND '".date("Y-m-d", strtotime("last day of this month"))."') && auth LIKE '95'"
                . "     ORDER BY pin ASC, data_ingresso ASC, nome_azienda ASC"; }
                
        
        
        $citofoni_boezio = array('3531','3532','3533','3535');
        $citofoni_eur = array('3831','3832','3833','3834');
        $citofoni_regolo = array('3331','3332','3333','3334','3335');
	
        $result = $conn->query($sql);
	$num_righe = $result->num_rows;

		if ($result->num_rows > 0) {
		echo "<table id=customers><tr><thead><th>AZIENDA</th><th>NOME</th><th>COGNOME</th><th>DATA</th><th>ACCESSI</th><th>CODICE</th><th>COD. AUT.</th><th>SEDE</th><TH>NOTE</th><th></th></thead></tr>";
		// output
		while($row = $result->fetch_assoc()) {
                
                if ( in_array($row['src'], $citofoni_eur) ) { $sede = "EUR"; }
                if ( in_array($row['src'], $citofoni_boezio) ) { $sede = "BOEZIO"; }
                if ( in_array($row['src'], $citofoni_regolo) ) { $sede = "REGOLO"; } 
                    
                $datifop = $conn2->query("SELECT * FROM visual_phonebook WHERE pin like '".$row["auth"].$row["pin"]."' ")->fetch_assoc();
                $note_accesso = $conn->query("SELECT nota FROM acs_note_ingressi WHERE pin LIKE '".$row['pin']."' AND sede = '".$sede."' AND data = '".$row["data_ingresso"]."'")->fetch_assoc();
                
                echo "<tr><td>".$row["nome_azienda"]."</td><td>".$datifop["firstname"]."</td><td>".$datifop["lastname"]."</td><td>".date("l d/m/Y", strtotime($row["data_ingresso"]))."</td><td width=\"500\">".$row["ingressi"]."</td><td>".$row["pin"]."</td><td>".$row["auth"]."</td><td>".$sede."</td>"
                        . "<td>".$note_accesso["nota"]."</td>"
                        ."<td  width='24'><A HREF='edit_nota.php?pin=".$row["pin"]."&data=".$row["data_ingresso"]."&centro=".$sede."&nota=".$note_accesso["nota"]."&ric=dipendenti'><IMG SRC='../images/file_edit.png' width='24' title='MODIFICA/INSERISCI NOTA'></a></TD>";
		}
				echo "<P align=center><a href=\"#\" onclick=\"$('#customers').tableExport({type:'excel',escape:'false'});\"><img src='images/export_xls.png' width=48 title='ESPORTA IN EXCEL' align='center'></a><a href=\"#\" onclick=\"stampa()\"><img src='images/export_print.png' width=48 title='STAMPA' align='center'></a>"
                        . "<BR><strong>Numero di risultati: ".$num_righe."</strong><BR>"
                        . "</p></table>";
                
		} else {
			echo "<DIV style=\"font-family:Georgia, Garamond, Serif;font-size:12pt;background-color:rgba(200,200,200,0.7);border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;margin-left:auto;margin-right:auto; width: 40%;\"><p align=center><b>Nessun risultato</b></P></div>";
				}
	

$conn->close();
$conn2->close();
?>

<?php if (isset($_POST["button"])) { echo "<BR><P align='center'><a href=\"#\" onclick=\"$('#customers').tableExport({type:'excel',escape:'false'});\"><img src='images/export_xls.png' width=64 title='ESPORTA IN EXCEL'></a><a href=\"#\" onclick=\"stampa()\"><img src='images/export_print.png' width=64 title='STAMPA'></a></p>"; } ?>

<script>
function stampa() {
    window.print();
}
</script>