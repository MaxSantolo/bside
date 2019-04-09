<head>
    <title>Inserisci Nuovo Contratto</title>
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


<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>  

</head>

<?php include('tech/navbar/navbarca.php'); ?>
<body>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>  
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script> 
<script type="text/javascript">
       $(function() {
               $("#dal").datepicker({ dateFormat: "yy-mm-dd 00:00" }).val()
               $("#al").datepicker({ dateFormat: "yy-mm-dd 23:59" }).val()
       });
</script>
<div class="hit-the-floor">Inserimento pacchetto</div></h3><BR>

    <form action="" method="post" name="inserisci_pacchetto">

        <table id="tabellains">
        
                <TR><TD colspan="2"><P ALIGN="center"><STRONG>Inserisci il pacchetto</STRONG><BR><font size="2">i campi indicati sono tutti obbligatori</font></P></TD></TR>
                <TR><TD><strong>Azienda: </strong></TD><TD width="200"><input name="azienda" style="color:black" value="<?php echo isset($_POST['azienda']) ? $_POST['azienda'] : '' ?>" required></TD></TR>
                <TR><TD><strong>A partire dal: </strong></TD><TD><input id="dal" name="dal" style="color:black" value="<?php echo isset($_POST['dal']) ? $_POST['dal'] : '' ?>" required></TD></TR>
                <TR><TD><strong>Scade il: </strong></TD><TD><input id="al" name="al" style="color:black" value="<?php echo isset($_POST['al']) ? $_POST['al'] : '' ?>" required></TD></TR>
                <TR><TD><strong>Codice (6 cifre): </strong></TD><TD><input name="codice" style="color:black" value="<?php echo isset($_POST['codice']) ? $_POST['codice'] : '' ?>" required></TD></TR>
                <TR><TD><strong>Tipo pacchetto: </strong></TD><TD><select name="pacchetto" id="pacchetto" style="color:black" value="<?php echo isset($_POST['pacchetto']) ? $_POST['pacchetto'] : '' ?>"><option value="10">B-Side 10 ore</option><option value="50">B-Side 50 ore</option><option value="100">B-Side 100 ore</option></TD></TR>
                <TR><TD><strong>Email: </strong></TD><TD><input name="email" style="color:black" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>"></TD></TR>
                <TR><TD colspan="2" style="color:black;text-align:center"><input type="submit" name="button" value="INSERISCI"></TD></TR>
        </table>
    </form>
    <div id="curvochiaro"><a href="edit_pacchetto.php">TORNA INDIETRO</a></div>
</body>
<?php


date_default_timezone_set('Europe/Rome');
$servername = "10.8.0.10";
$username = "pick";
$password = "Pick.2017";
$db = "asteriskcdrdb";
$db2 = "asterisk";
// creo connessione
$conn = new mysqli($servername, $username, $password, $db);
$conn2 = new mysqli($servername, $username, $password, $db2);
// controllo connessione
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if ($conn2->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if (isset($_POST["button"])) {
        

    
    
	$azienda = $_POST["azienda"];
	$dal = date('Y-m-d', strtotime($_POST["dal"]));
	$al = date('Y-m-d', strtotime($_POST["al"]));
	$pacchetto = $_POST["pacchetto"];
	$codice = $_POST["codice"];
        $email = $_POST["email"];
        $errore = false;
        $checkcodice = $conn2->query("SELECT pin FROM visual_phonebook WHERE pin like '%".$codice."'"); //esiste da fop
        $checkcodice2 = $conn->query("SELECT codice FROM acs_pacchetti WHERE codice like '%".$codice."'"); //esiste come pacchetto
        
        
        
        $tipo_pacchetto = "";
        $specimen = '';
        
                switch ($pacchetto) {
                    case "10":
                    $tipo_pacchetto = "BSIDE 10 ore";
                    $specimen = 'PACK10';
                break;
                    case "50":
                    $tipo_pacchetto = "BSIDE 50 ore";
                    $specimen = 'PACK50';
                break;
                    case "100":
                    $tipo_pacchetto = "BSIDE 100 ore";
                    $specimen = 'PACK100';
                break;
                }
        
        echo "<DIV id=\"curvochiaro\">";
                
        if ((strlen($codice) < 6) or (strlen($codice) > 6)) { echo("Controllate la lunghezza del campo codice<BR>"); $errore=true;}
        elseif (($checkcodice->num_rows > 0) || ($checkcodice2->num_rows > 0)) {echo("Codice presente, controllate il <A HREF=\"http://10.8.0.10/fop2/\" target=\"blank\">posto operatore</a> della centrale telefonica.<BR>"); $errore=true;}
        if ((((strtotime($al) - strtotime($dal)) / 86400) < 28)) { echo("Un pacchetto non pu&ograve durare meno di un mese<BR>"); $errore=true;}
       
        echo($errori);
        echo "</div>";    
        
                    
        if (!$errore) { //inserimento
           
            $conn->query("INSERT INTO acs_pacchetti (azienda, codice, cod_auth, data_inizio_pacchetto, data_fine_pacchetto, tipo, ore_totali_pacchetto, email_notifiche) VALUES ('".$azienda."', '".$codice."', '97', '".$dal."', '".$al."', '".$tipo_pacchetto."', '".$pacchetto."', '".$email."')");
            $conn->query("INSERT INTO acs_utenti (nome_azienda, pin, auth, email, tipo, specimen, livello) VALUES ('".$azienda."', '".$codice."', '97', '".$email."', 'BSIDE', '".$specimen."', 'UTENTE')");
            $conn2->query("INSERT INTO visual_phonebook (company, pin, email) VALUES ('".$azienda."', '97".$codice."', '".$email."')");
            
            echo("<strong><DIV id=\"curvochiaro\">Il pacchetto &egrave stato inserito correttamente</strong></div>");
            header("Location: edit_pacchetto.php");
	                           
        }
             
        /*	$sql = 
                "SELECT * FROM cdr_accessi WHERE stato LIKE '%".$tipo."%' && (nome_azienda LIKE '%".$azienda."%' && nome_azienda NOT LIKE '%Pick Center%') && src LIKE '%".$ingresso."%' && codice_pin LIKE '%".$codice."%' && (data_ingresso BETWEEN '".$dal."' AND '".$al."')";
	$result = $conn->query($sql);
	$num_righe = $result->num_rows;

		if ($result->num_rows > 0) {
		echo "<table id=customers><tr><th>DATA</th><th>ORA</th><th>AZIENDA</th><th>ACCESSO</th><th>CODICE</th><th>TIPO</th></tr>";
		// output
		while($row = $result->fetch_assoc()) {
        echo "<tr><td>".date('d/m/y', strtotime($row["data_ingresso"]))."</td><td>".date('H:i', strtotime($row["ora_ingresso"]))."</td><td>".$row["nome_azienda"]."</td><td>".$row["src"]."</td><td>".$row["codice_pin"]."</td><td>".$row["stato"]."</td></tr>";
		}
		echo "<DIV style=\"font-family:Georgia, Garamond, Serif;font-size:12px;background-color:rgba(200,200,200,0.7);width: 800px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;margin-left:auto;margin-right:auto;\"><P align=center><strong>Numero di risultati: ".$num_righe."</strong><BR>La ricerca è stata effettuata con i seguenti parametri:<BR>Azienda: <strong>".$azienda."</strong>, nelle date comprese fra <strong>".date('d/m/y H:i', strtotime($dal))."</strong> e <strong>".date('d/m/y H:i', strtotime($al))."</strong>. La ricerca è filtrata per codice di accesso (<strong>".$codice."</strong>) e per ingresso (<strong>".$ingresso."</strong>).</P></DIV> </table>";
		} else {
			echo "<p align=center>Nessun risultato</P>";
				}
	*/ 

}
$conn->close();
?>