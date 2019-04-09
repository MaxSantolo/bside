
<?php 

include ("tech/functions.php");
include ("tech/connect.php");
// include ("../tech/connect_prod.php");
include ("session.php");

?>
<html>
<head>
    <title>PIN REPLICATI</title>    
<link rel="stylesheet" type="text/css" href="css/baseline.css" />


<style>


#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    font-size:medium;
    border-collapse: collapse;
    width: 80%;
    margin-left:auto;
    margin-right:auto;
}

#customers td, #customers th {
    border: 1px solid #ddd;
    padding: 5px;
	
}


#customers tr:nth-child(even){background-color:#d2d2d2;opacity:0.9;}
#customers tr:nth-child(odd){background-color:#c2c2c2;opacity:0.9;}

#customers tr:hover {background-color: #bbb;}

#customers th {
    padding-top: 5px;
    padding-bottom: 5px;
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

    <?php include ('tech/navbar/navbarca.php'); ?>
<div class="hit-the-floor">PIN duplicati</div><BR>
<!--    <form action="" method="post">

        <table id="tabellaricerca">
                <TR style="font-size:small;">
                        <TD><input name="variabile" height="48" style="color:black" value="<?php echo isset($_POST['variabile']) ? $_POST['variabile'] : '' ?>"></TD><TD><input type="submit" name="button" value="CERCA" style="color:black"></TD>
                </TR>
        </table>
    </form>
    <P>-->
        
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
        <script src="tech/assets/js/jquerypp.custom.js"></script>
        <script src="tech/assets/framewarp/framewarp.js"></script>
        <script src="tech/assets/js/script.js"></script>

<?php

 
                $sql = "select group_concat('<a href=\"fop_dettagli.php?id_vispb=',id, '\">', id,'</A> | ', lastname, ' ', firstname, ' | ', company, ' | ', email, ' | ', email2 order by id separator '<BR>') as duplicati, count(pin) c, pin from visual_phonebook where pin!='' group by pin having c>1" ;
                $result = $conn2->query($sql);

if ($result->num_rows > 0) {

    echo "<P><table id=customers><tr><th>DUPLICATI</th><th>PIN</th><th colspan = '1'></th>";

    while($row = $result->fetch_assoc()) {
                
                echo "<tr><td>".$row["duplicati"]."</td><td>".$row["pin"]."</td>"
                        //. "<td width=24><a href='fop_dettagli.php?id_vispb=".$row['id']."&pinpb=".$row['pin']."'><IMG SRC=\"../images/dettagli.png\" border=0 width=24 title=\"Dettagli\"></a></td>"
                        //. "<td width=24><a href='elenco_pacchetti_scaduti.php?codice=".$row['codice']."&dal=".$row["data_inizio_pacchetto"]."&al=".$row["data_fine_pacchetto"]."'><IMG SRC=\"../images/file_archive.png\" border=0 width=24 title=\"Pacchetti scaduti\"></a></td>"
                        //. "<td width=24><a href='ric_pacchetti.php?codice=".$row['codice']."&dal=".$row["data_inizio_pacchetto"]."&al=".$row["data_fine_pacchetto"]."'><IMG SRC=\"../images/ingressi.png\" border=0 width=24 title=\"Ingressi\"></a></td>"
                        . "<td width=24><a href='mailto:".$row["email"]."'><IMG SRC=\"../images/file_send.png\" border=0 width=24 title=\"Invia dettaglio per email\"></a></td>";
                        //. "<td width=24><a href='areaclienti/cancella.php?id_vispb=".$row['id']."' onclick='return confirm(\"Sicuro di voler eliminare il contatto ".$row["cognome"]." ".$row["nome"]."  (ID # ".$row['id'].")?\")'><IMG SRC=\"../images/file_delete.png\" border=0 width=24 title=\"Elimina pacchetto\"></a></tr>";
		}
		echo "</table>";
		} else { echo "<p align=center>Nessun risultato</P>"; }
	


?>
    <div class="hit-the-floor">NOMI DUPLICATI SUL FOP</div><BR>
    <?php


    $sql2 = "select group_concat('<a href=\"fop_dettagli.php?id_vispb=',id, '\">', id,'</A> | ', lastname, ' ', firstname, ' | ', company, ' | ', email, ' | ', email2 order by id separator '<BR>') as duplicati, count(lastname) c, lastname, count(firstname) d, firstname, count(email) e, email from visual_phonebook where lastname != '' and firstname !='' and email !='' group by lastname, firstname having (c>1 and d>1 and e>1)" ;
    $result2 = $conn2->query($sql2);

    if ($result2->num_rows > 0) {

        echo "<P><table id=customers><tr><th>DUPLICATI</th><!--<th>MAIL</th>--><th colspan = '1'></th>";

        while($row2 = $result2->fetch_assoc()) {

            echo "<tr><td>".$row2["duplicati"]."</td><!--<td>".$row2["email"]."</td>-->"
                //. "<td width=24><a href='fop_dettagli.php?id_vispb=".$row['id']."&pinpb=".$row['pin']."'><IMG SRC=\"../images/dettagli.png\" border=0 width=24 title=\"Dettagli\"></a></td>"
                //. "<td width=24><a href='elenco_pacchetti_scaduti.php?codice=".$row['codice']."&dal=".$row["data_inizio_pacchetto"]."&al=".$row["data_fine_pacchetto"]."'><IMG SRC=\"../images/file_archive.png\" border=0 width=24 title=\"Pacchetti scaduti\"></a></td>"
                //. "<td width=24><a href='ric_pacchetti.php?codice=".$row['codice']."&dal=".$row["data_inizio_pacchetto"]."&al=".$row["data_fine_pacchetto"]."'><IMG SRC=\"../images/ingressi.png\" border=0 width=24 title=\"Ingressi\"></a></td>"
                . "<td width=24><a href='mailto:".$row2["email"]."'><IMG SRC=\"../images/file_send.png\" border=0 width=24 title=\"Invia dettaglio per email\"></a></td>";
            //. "<td width=24><a href='areaclienti/cancella.php?id_vispb=".$row['id']."' onclick='return confirm(\"Sicuro di voler eliminare il contatto ".$row["cognome"]." ".$row["nome"]."  (ID # ".$row['id'].")?\")'><IMG SRC=\"../images/file_delete.png\" border=0 width=24 title=\"Elimina pacchetto\"></a></tr>";
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