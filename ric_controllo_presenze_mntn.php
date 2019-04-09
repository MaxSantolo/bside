<head>

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
    font-size: medium;
    background-color: #AF4c50;
	opacity:0.9;
    color: white;
}
body {
  background-image: url(images/sfondo_pulizie.jpg);
  background-position: center center;
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: cover;
  background-color: #464646;
}
</style>
<link rel="stylesheet" type="text/css" href="css/baseline.css" />
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>  

<?php include('tech/navbar/navbarca.php'); ?>

</head>


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>  
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script> 
<script type="text/javascript" src="tech/jexport/tableExport.js"></script>
<script type="text/javascript" src="tech/jexport/jquery.base64.js"></script>


<body>

<div class="hit-the-floor">Controllo presenze - DIPENDENTI</div><BR>
    <form action="" method="post">
        <table id="tabellaricerca">
                <TR><TD colspan="10" width="400"><P ALIGN="center"><STRONG>Strumenti di ricerca</STRONG><BR><font size="2">lasciare un campo vuoto equivale ad estrarne tutti i valori</font></P></TD></TR>
                <TR style="font-family:Georgia, Garamond, Serif;font-size:small;">
                        <TD><strong>Dal: </strong></TD>
                        <TD><input type="date" name="dal" style="color:black" value="<?php echo isset($_POST['dal']) ? $_POST['dal'] : '' ?>"></TD>
                        <TD><strong>Al: </strong></TD>
                        <TD><input type="date" name="al" style="color:black" value="<?php echo isset($_POST['al']) ? $_POST['al'] : '' ?>"></TD>
                        <TD><strong>Codice: </strong></TD>
                        <TD><input name="codice" style="color:black" value="<?php echo isset($_POST['codice']) ? $_POST['codice'] : '' ?>"></TD>
                </TR>
                <TR><TD colspan="10" style="color:black;text-align:center"><input type="submit" name="button" value="CERCA"></TD></TR>
        </table>
    </form>

<script>
function stampa() {
    window.print();
}

       
</script>



</body>
<?php

include ("tech/functions.php");
include ('tech/connect.php');

//definisco dal-al
//$dal = date('Y-m-d',strtotime("first day of this month")); //inizio_mese
//$al = date('Y-m-d',strtotime("last day of this month")); //fine_mese
//$pin = '%';


if (isset($_POST["button"])) {
    
        if ( $_POST['codice'] !== '' ) { $pin = $_POST["codice"]; } else { $pin = '%'; }
        if ( $_POST['dal'] !== '' ) { $dal = $_POST["dal"]; } else { $dal =  date('Y-m-d',strtotime("first day of this month")); }
	if ( $_POST['al'] !== '' ) { $al = $_POST["al"]; } else { $al =  date('Y-m-d',strtotime("last day of this month")); }
        if ( $_POST['codice'] !== '') { $pin = $_POST['codice']; } else { $pin = '%'; }
	

        
                $intervallo_periodo = dateRange($dal,$al);
		echo "<table id=customers><tr><thead><th colspan = 4>ACCESSI PERIODO (dal ".date('d-M-Y', strtotime($dal))." al ".date('d-M-Y', strtotime($al)).")</th></tr>"
                        . "<tr><th>DIPENDENTE</th><th>INGRESSI</th><th>TURNI</th><th>FER/PERM/RITAR/MAL/STRAOR</th></thead>";
                  		
                $q_nomi_sing = $conn->query("select distinct(nome_azienda) as dipendente, pin, auth from union_sums "
                        . " where nome_azienda !=  '' and nome_azienda like '%MNTN%' and data_ingresso between '".$dal."' and '".$al."' and pin like '".$pin."' "
                        . " order by nome_azienda");
                
                
                while ($nomi_singoli = $q_nomi_sing->fetch_assoc() )
                       
                {   $datifop = $conn2->query("SELECT * FROM visual_phonebook WHERE pin like '".$nomi_singoli["auth"].$nomi_singoli["pin"]."' ")->fetch_assoc();
                    //riga intestazione dipendente
                    echo "<tr style='background-color: #3b5998; color: #ffffff; font-weight: bold'><td colspan = 4>"
                            .$nomi_singoli['dipendente']. " - " . $datifop['firstname'] . " " . $datifop['lastname'] . ""
                            . " (CODICE ". $nomi_singoli['auth'] . $nomi_singoli['pin'] . ")</td></tr>" ;
                    
                    //foreach datarange
                    
                    foreach ($intervallo_periodo as $data_controllo) {
                        
                    $ingressi_aggregati = $conn->query("select data_ingresso, group_concat(ingressi separator ' -|- ') as ingressi_tot from union_sums "
                            . "where "
                            . "nome_azienda = '".$nomi_singoli['dipendente']."' and pin = '".$nomi_singoli['pin']."'"
                            . "and data_ingresso = '" .$data_controllo."' "
                            . "group by pin, data_ingresso order by data_ingresso asc")->fetch_assoc();
                    
                        $note_accesso = $conn->query("SELECT nota FROM acs_note_ingressi WHERE pin LIKE '".$ingressi_aggregati['pin']."' AND data = '".$data_controllo."'")->fetch_assoc();    
                            
                        echo "<tr><td>". date("l d/m/Y", strtotime($data_controllo) ). "</td><td width=50%>". $ingressi_aggregati['ingressi_tot'] . "</td><td></td>"
                                . "<td>".$note_accesso["nota"]."</td>"
                                . "</tr>";
                    }

                }
		echo "<P align=center><a href=\"#\" onclick=\"$('#customers').tableExport({type:'excel',escape:'false'});\"><img src='images/export_xls.png' width=48 title='ESPORTA IN EXCEL' align='center'></a><a href=\"#\" onclick=\"stampa()\"><img src='images/export_print.png' width=48 title='STAMPA' align='center'></a>"
                . "<BR><strong>Numero di risultati: ".$num_righe."</strong><BR>"
                . "</p></table>";
        } 
        
$conn->close();
$conn2->close();
?>

<?php if (isset($_POST["button"])) { echo "<BR><P align='center'><a href=\"#\" onclick=\"$('#customers').tableExport({type:'excel',escape:'false'});\"><img src='images/export_xls.png' width=64 title='ESPORTA IN EXCEL'></a><a href=\"#\" onclick=\"stampa()\"><img src='images/export_print.png' width=64 title='STAMPA'></a></p>"; } ?>

