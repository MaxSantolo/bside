
 <html>
 <head>

     <title>Modifica Pacchetto</title>
 <link rel="stylesheet" type="text/css" href="css/baseline.css">
 <style>
#curvochiaro {
    border-radius: 15px;
    background: #dddddd;
    opacity: 1;
    padding: 20px; 
    width: 400px;
    margin: auto;
    font-size:10pt;
    font-family:Verdana;
    font-weight:bold;
    color:#23238e;
    text-align: center;
}
  
body {
  background-image: url(images/sfondo.jpg);
  background-position: center center;
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: cover;
  background-color: #464646; 
    }
     
 </style>
 <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>  
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>  
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script> 

<script type="text/javascript">
       $(function() {
               $("#i_al").datepicker({ dateFormat: "yy-mm-dd" }).val()
       });
</script>

 <?php include('tech/navbar/navbarca.php'); ?>
 </head>
 
 <?php
 
 include('tech/connect.php');

 

$id = $_GET['id'];
$sql = "SELECT * FROM acs_pacchetti WHERE id_pacchetto='".$id."' && cestinato != '1'";
$result = $conn->query($sql);
 

if ($result->num_rows > 0) { 
 while($row = $result->fetch_assoc()) {
// leggo i dati da acs_pacchetti
    $azienda = $row['azienda'];
    $codice = $row['codice'];
    $cod_auth = $row['cod_auth'];
    $ore_utilizzate = $row['ore_utilizzate'];
    $ore_totali_pacchetto = $row['ore_totali_pacchetto'];
    $data_inizio = $row['data_inizio_pacchetto'];
    $data_fine = $row['data_fine_pacchetto'];
    $tipo = $row['tipo'];
    $email_notifiche = $row['email_notifiche'];
    $delta_ore = $row['delta_ore'];
    }
 }
 else { echo "Il pacchetto non esiste!"; }
 
 if (isset($_POST["button"])) {
 
    $i_az = $_POST['i_azienda'];
    $i_cod = $_POST['i_codice'];
    $i_ca = $_POST['i_codauth'];
    $i_oreu = $_POST['i_oreusate'];
    $i_oret = $_POST['i_oretotali'];
    $i_dal = date('Y-m-d', strtotime($_POST['i_dal']));
    $i_al = date('Y-m-d', strtotime($_POST['i_al']));
    $i_tipo = $_POST['i_pacchetto'];
    $i_email = $_POST['i_email'];
    $i_delta = $_POST['i_deltaore'];     
    
    $updatesql =  "UPDATE acs_pacchetti SET azienda='".$i_az."', "
                    . "ore_totali_pacchetto='".$i_oret."', data_fine_pacchetto='".$i_al."', "
                    . "data_inizio_pacchetto='".$i_dal."', tipo='".$i_tipo."', email_notifiche='".$i_email."', delta_ore='".$i_delta."'  "
                    . "WHERE id_pacchetto='".$id."'"; 
    $conn->query($updatesql);
    
    //$conn ->query("UPDATE acs_utenti SET email='".$i_email."' WHERE pin = '".$i_cod."'"); //mail -> utenti
    //$conn2->query("UPDATE visual_phonebook SET email='".$i_email."' WHERE visual_phonebook.pin = '97".$i_cod."'"); //mail->fop
    
    
    header('Location: edit_pacchetto.php');
 }
 $conn->close();
?>

<body>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>  
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>  
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script> 

<script type="text/javascript">
       $(function() {
               $("#i_al").datepicker({ dateFormat: "yy-mm-dd" }).val()
       });
</script>
     
 <form action="" method="post">
 
       <table id="tabellamod">
        <div class="hit-the-floor">Modifica pacchetto</div></h3><BR>
                <TR><TD colspan="2" width="400"><P ALIGN="center"><STRONG>Modifica il pacchetto (ID: <?php echo $id; ?>)</STRONG><BR><font size="2">i campi indicati sono tutti obbligatori</font></P></TD></TR>
                <TR><TD width="200"><strong>Nome/Azienda: </strong></TD><TD width="200"><input name="i_azienda" style="color:black" value="<?php echo $azienda; ?>" required></TD></TR>
                <TR><TD><strong>Codice Autorizzazione: </strong></TD><TD><input name="i_codauth" style="color:black" value="<?php echo $cod_auth; ?>" disabled></TD></TR>
                <TR><TD><strong>Codice (6 cifre): </strong></TD><TD><input name="i_codice" style="color:black" value="<?php echo $codice; ?>" disabled></TD></TR>
                <TR><TD><strong>Tipo pacchetto: </strong></TD><TD><select name="i_pacchetto" id="pacchetto" style="color:black" value="<?php echo $tipo; ?>"><option value="BSIDE10">BSide 10 ore</option><option value="BSIDE50">BSide 50 ore</option><option value="BSIDE100" selected="yes">BSide 100 ore</option></select></TD></TR>
                <TR><TD><strong>Data inizio: </strong></TD><TD><input name="i_dal" style="color:black" value="<?php echo date('d/m/Y',strtotime($data_inizio)); ?>" disabled></TD></TR>
                <TR><TD><strong>Scade il: </strong></TD><TD><input id="i_al" name="i_al" style="color:black" value="<?php echo date('d/m/Y',strtotime($data_fine)); ?>" required></TD></TR>
                <TR><TD><strong>Ore utilizzate: </strong></TD><TD><input name="i_oreusate" style="color:black" value="<?php echo $ore_utilizzate; ?>" disabled></TD></TR>
                <TR><TD><strong>Ore totali: </strong></TD><TD><input name="i_oretotali" style="color:black" value="<?php echo $ore_totali_pacchetto; ?>" required></TD></TR>
                <TR><TD><strong>Ore gratuite: </strong></TD><TD><input name="i_deltaore" style="color:black" value="<?php echo $delta_ore; ?>"></TD></TR>
                <TR><TD><strong>Email: </strong></TD><TD><input name="i_email" style="color:black" value="<?php echo $email_notifiche; ?>" disabled></TD></TR>
                <TR><TD colspan="2" style="color:black;text-align:center"><input type="submit" name="button" value="APPLICA"></TD></TR>
        </table> 
 </form> <BR>
    <div id="curvochiaro"><a href="edit_pacchetto.php">TORNA INDIETRO</a></div>
 </body>
 </html> 
 
 