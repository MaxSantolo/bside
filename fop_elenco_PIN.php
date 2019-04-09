
<?php 

include ("tech/functions.php");
include ("tech/connect.php");
// include ("../tech/connect_prod.php");
include ("session.php");

?>
<html>
<head>
    <title>Dettagli PIN</title>    
<link rel="stylesheet" type="text/css" href="css/baseline.css" />


<style>


#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    font-size:small;
    border-collapse: collapse;
    width: 65%;
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
    background-color: #2c2c2c;
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
    <?php include ('tech/navbar/navbarca.php'); ?>
</head>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>  
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>  
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="tech/jexport/tableExport.js"></script>
<script type="text/javascript" src="tech/jexport/jquery.base64.js"></script>


<body>


    
    
<div class="se-pre-con"></div>

<div class="hit-the-floor">Dettagli PIN</div><BR>

    <P align='center'><a href='#' onClick="$('#customers').tableExport({type:'excel',escape:'false'});"><img src='images/export_xls.png' width=64 title='ESPORTA IN EXCEL'></a></p>
        

<?php


$sql2 = "SELECT firstname, lastname, company, pin from visual_phonebook where pin != '' and pin not like '55%' order by company" ;
$result = $conn2->query($sql2);
$num_righe = $result->num_rows;
                                
if ($result->num_rows > 0) {
echo "<P><table id=customers><tr><thead><th>COGNOME</th><th>NOME</th><th>AZIENDA</th><th>PIN</th></thead></tr>";

    while($row = $result->fetch_assoc()) {
                
                echo "<tr><td>".htmlentities(utf8_decode($row["lastname"]))."</td><td>".htmlentities(utf8_decode($row["firstname"]))."</td><td>".htmlentities(utf8_decode($row["company"]))."</td>"
                        . "<td>".$row["pin"]."</td>";
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