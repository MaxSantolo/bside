
<?php 

include ("tech/functions.php");
include ("tech/connect.php");
// include ("../tech/connect_prod.php");
include ("session.php");

?>
<html>
<head>
    <title>Rubrica FOP</title>    
<link rel="stylesheet" type="text/css" href="css/baseline.css" />


<style>


#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    font-size:small;
    border-collapse: collapse;
    width: 80%;
    margin-left:auto;
    margin-right:auto;
}

#customers td, #customers th {
    border: 1px solid #ddd;
    padding: 1px;
	
}


#customers tr:nth-child(even){background-color:#d2d2d2;opacity:0.9;}
#customers tr:nth-child(odd){background-color:#c2c2c2;opacity:0.9;}

#customers tr:hover {background-color: #bbb;}

#customers th {
    padding-top: 2px;
    padding-bottom: 2px;
    text-align: left;
    background-color: #bf2d2c;
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

</head>

<body>
<div class="se-pre-con"></div>
    <?php include ('tech/navbar/navbarca.php'); ?>
<div class="hit-the-floor">Rubrica FOP</div><BR>
    <form action="" method="post">

        <table id="tabellaricerca">
                <TR style="font-size:small;">
                        <TD><input name="variabile" height="48" style="color:black" value="<?php echo isset($_POST['variabile']) ? $_POST['variabile'] : '' ?>"></TD><TD><input type="submit" name="button" value="CERCA" style="color:black"></TD>
                </TR>
        </table>
    </form>
    <P>
        
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
        <script src="tech/assets/js/jquerypp.custom.js"></script>
        <script src="tech/assets/framewarp/framewarp.js"></script>
        <script src="tech/assets/js/script.js"></script>

<?php



if (isset($_POST["button"])) {
    
        $variabile = $_POST["variabile"];
	$sql = "SELECT * FROM visual_phonebook WHERE firstname LIKE '%".$variabile."%' OR lastname LIKE '%".$variabile."%' OR pin LIKE '%".$variabile."%' OR company LIKE '%".$variabile."%'";
	$result = $conn2->query($sql);
        
}       else {
                $sql2 = "SELECT * FROM visual_phonebook ORDER BY company, lastname" ;
                $result = $conn2->query($sql2);
            }        

$num_righe = $result->num_rows;
                                
if ($result->num_rows > 0) {
echo "<P><table id=customers><tr><th>ID</th><th>COGNOME</th><th>NOME</th><th>AZIENDA</th><!--<th>TELEFONO 1</th><th>TELEFONO 2</th><th>TELEFONO 3</th><th>EMAIL</th><th>EMAIL 2</th><th>NOTE 1</th><th>NOTE 2</th><th>NOTE 3</th><th>SERVIZI</th>--><th>PIN</th><th colspan=5 style=\"text-align:right\"><A HREF=\"http://crm.pickcenter.com/index.php?module=Leads&action=EditView&return_module=Leads&return_action=DetailView\" target='_blank'><IMG SRC=\"../images/file_new.png\" border=0 height=32 title=\"Nuovo\"></A></th>";

    while($row = $result->fetch_assoc()) {
                
                echo "<tr><td>".$row["id"]."</td><td>".$row["lastname"]."</td><td>".$row["firstname"]."</td><td>".$row["company"]."</td><!--<td>".$row["phone1"]."</td><td>".$row["phone2"]."</td><td>".$row["phone3"]."</td><td>".$row["email"]."</td><td>".$row["email2"]."</td>"
                        . "<td>".$row["note"]."</td><td>".$row["note2"]."</td><td>".$row["note3"]."</td><td>".$row["servizi"]."</td>--><td>".$row["pin"]."</td>"
                        . "<td width=24><a href='fop_dettagli.php?id_vispb=".$row['id']."&pinpb=".$row['pin']."'><IMG SRC=\"../images/dettagli.png\" border=0 width=24 title=\"Dettagli\"></a></td>"
                        //. "<td width=24><a href='elenco_pacchetti_scaduti.php?codice=".$row['codice']."&dal=".$row["data_inizio_pacchetto"]."&al=".$row["data_fine_pacchetto"]."'><IMG SRC=\"../images/file_archive.png\" border=0 width=24 title=\"Pacchetti scaduti\"></a></td>"
                        //. "<td width=24><a href='ric_pacchetti.php?codice=".$row['codice']."&dal=".$row["data_inizio_pacchetto"]."&al=".$row["data_fine_pacchetto"]."'><IMG SRC=\"../images/ingressi.png\" border=0 width=24 title=\"Ingressi\"></a></td>"
                        //. "<td width=24><a href='mailto:".$row["email1"]."'><IMG SRC=\"../images/file_send.png\" border=0 width=24 title=\"Invia dettaglio per email\"></a></td>"
                        . "<td width=24><a href='areaclienti/cancella.php?id_vispb=".$row['id']."' onclick='return confirm(\"Sicuro di voler eliminare il contatto ".$row["cognome"]." ".$row["nome"]."  (ID # ".$row['id'].")?\")'><IMG SRC=\"../images/file_delete.png\" border=0 width=24 title=\"Elimina\"></a></tr>";
		}
		echo "</table>";
		} else { echo "<p align=center>Nessun risultato</P>"; }
	

$conn->close();
$conn2->close();
?>
<P>

<script>
	$(document).ready(function() {
                $(".se-pre-con").fadeOut("slow");
       });
</script>

</body>
</html>