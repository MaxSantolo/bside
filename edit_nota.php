 
 <html>
 <head>
 <title>Modifica Nota</title>
 <link rel="stylesheet" type="text/css" href="css/baseline.css">
 <style>
#curvochiaro {
    border-radius: 15px;
    background: #dddddd;
    opacity: 1;
    padding: 20px; 
    margin: auto;
    width: 350px;
    font-size:12pt;
    font-family:Verdana;
    font-weight:bold;
    color:#23238e;
    text-align: center;
}
#curvorosso {
    border-radius: 10px;
    background: #cc9400;
    opacity: 1;
    padding: 10px; 
    width: 700px;
    margin: auto;
    font-size:10pt;
    font-family:Verdana;
    font-weight:bold;
    color:#ffffff;
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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>  
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script> 




</head>
 
 <?php
 
 include('tech/connect.php');
 
 
    $pinnota = $_GET["pin"];
    $datanota = $_GET["data"];
    $centronota = $_GET["centro"];
    $notanota = $_GET["nota"];



       
    if (isset($_POST["button"]))  {
 

    $fnota = mysqli_real_escape_string($conn, $_POST["f_nota"]);

    
        if ($notanota != '')  { $insertsql = "UPDATE acs_note_ingressi SET nota = '".$fnota."' WHERE pin = '".$pinnota."' AND data = '".$datanota."' AND sede = '".$centronota."'";
        } else { $insertsql =  "INSERT INTO acs_note_ingressi (pin, data, sede, nota) VALUES ('".$pinnota."', '".$datanota."', '".$centronota."', '".$fnota."')"; }
       
    $conn->query($insertsql);
        
    header('location: ric_'.$_GET['ric'].'.php');
 
    } 
    
     
 
    
 $conn->close();
?>

<body>

  
         <div class="hit-the-floor">Modifica/Inserisci Nota</div><BR>

<form action="" method="post">
 
       <table id="tabellains">

                <TR><TD colspan="2"><P ALIGN="center"><font size="2">i campi indicati sono tutti obbligatori</font></P></TD></TR>
                <TR><TD><strong>PIN UTENTE: </strong></TD><TD><input type="text" name="f_pin" style="color:black" value="<?php echo $pinnota ?>" disabled></TD></TR>
                <TR><TD><strong>Data: </strong></TD><TD><input type="date" name="f_data" style="color:black" value="<?php echo $datanota ?>" disabled=""></TD></TR>
                <TR><TD><strong>Centro: </strong></TD><TD><input type="text" name="f_centro" style="color:black" value="<?php echo $centronota ?>" disabled></TD></TR>                
                <TR><TD><strong>Nota: </strong></TD><TD><textarea name="f_nota" style="color:black" cols="50" rows="7"><?php echo $notanota; ?></textarea></TD></TR>

                <TR><TD colspan="2" style="color:black;text-align:center"><input type="submit" name="button" value="<?php if ($notanota != "") { echo 'AGGIORNA';} else { echo 'INSERISCI'; } ?>"></TD></TR>
        </table> 
 </form> <BR>
    <div id="curvochiaro"><a href="ric_<?php echo $_GET['ric']; ?>.php">TORNA INDIETRO</a></div>
 </body>
 </html> 
 
 