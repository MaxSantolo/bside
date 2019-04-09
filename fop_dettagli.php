
<html>
    <head>
        <title>Pagina dettagli contatto fop</title>
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
    border-radius: 5px;
    background: #ff0000;
    opacity: 1;
    padding: 5px; 
    margin: auto;
    font-size:10pt;
    font-family:Verdana;
    font-weight:bold;
    color:#ffffff;
    text-align: center;
}   
            
            
 #customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    font-size:small;
    border-collapse: collapse;
    width: 100%;
    margin-left:auto;
    margin-right:auto;
}

#customers td, #customers th {
    border: 1px solid #ddd;
    padding: 2px;
	
}

#customers tr:nth-child(even){background-color:#d2d2d2;opacity:0.9;}
#customers tr:nth-child(odd){background-color:#c2c2c2;opacity:0.9;}

#customers tr:hover {background-color: #bbb;}

#customers th {
    padding-top: 1px;
    padding-bottom: 1px;
    text-align: left;
    background-color: rgba(17,14,94,.7);
    color: white;
}    
            
            
    body {
    background-image: url(../../images/sfondo.jpg);
    background-position: center center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
    background-color: #464646;
    }            
    
    input, select { 
    background: rgba(17,14,94,.7); 
    border: 1px solid white; 
    color: #fff; 
    height: 30px; 
    line-height: 30px;
    width: 300px;
    padding: 0 10px;
    font-family: Verdana;
    font-size: 12pt;
    } 
        </style>
        <link rel="stylesheet" type="text/css" href="css/baseline.css">

    <script type="text/javascript">
        function random() {
        document.getElementById('f_pin').value = '99' + (Math.floor(Math.random() * 900000)+100000); }
    </script>        
        
    </head>
    
    
    <body>

<?php 
        
        require ("tech/functions.php");
        include ("tech/connect.php");
        
        
        

$id_pb = $_GET["id_vispb"] ?: '';
$pinpb = $_GET["pinpb"] ?: '';

header("Location: https://acs.pickcenter.com/pinutils/vbdetail.php?vbid={$id_pb}&vbpin={$pinpb}");


$result = $conn2->query("SELECT * FROM visual_phonebook WHERE id = '".$id_pb."'")->fetch_assoc()  ;              

            
    if (isset($_POST["salva"])) {
        
                $fcognome = mysqli_real_escape_string($conn2, $_POST['f_cognome']);
                $fnome = mysqli_real_escape_string($conn2, $_POST['f_nome']);
                $ftel1 = $_POST['f_tel1'];
                $ftel2 = $_POST['f_tel2'];
                $ftel3 = $_POST['f_tel3'];
                $femail1 = $_POST['f_email1'];
                $femail2 = $_POST['f_email2'];
                $fazienda = mysqli_real_escape_string($conn2, $_POST['f_azienda']);
                $fpin = $_POST['f_pin'] ?: '';
                $fnote1 = mysqli_real_escape_string($conn2, $_POST['f_note1']);
                $fnote2 = mysqli_real_escape_string($conn2, $_POST['f_note2']);
                $fnote3 = mysqli_real_escape_string($conn2, $_POST['f_note3']);
                $ftcm = $_POST['f_tcm'];
                $fservizi = $_POST['f_servizi'];
                $fscadenza = $_POST['f_scadenza'];
                $ftipo = $_POST['f_tipo'];

                $errore = '';
                
                if ($fscadenza != NULL) { $fscadenza = date('Y-m-d', strtotime($_POST['f_scadenza'])); }
                
                //campo azienda sostituisce - con _
                if (strpos($fazienda, '-') !== false) {
                 $fazienda = str_replace('-','_', $fazienda);
                }

                //campo azienda sostituisce - con _
                if (strpos($azienda, '&') !== false) {
                $azienda = str_replace('$',' AND ', $fazienda);
                }
                
                //pin duplicato
                $pinerrore = $conn2->query("SELECT * FROM visual_phonebook WHERE pin = '".$fpin."'");
                
        if ( $pinerrore->num_rows > 0 && $fpin != '') { $errore = "Codice gi&agrave; presente, sceglierne un altro.";}
        if ($pinpb == $fpin) { $errore = ''; } 
                
        if (isset($_GET["id_vispb"]) ) {
    
            if ($errore=='') { 
                
            $location = "Location: fop_dettagli.php?id_vispb=".$id_pb."&pinpb=".$fpin;
            
            $update =   "UPDATE visual_phonebook SET lastname = '".$fcognome."', "
                .       "firstname = '".$fnome."', "
                .       "phone1 = '".$ftel1."', phone2 = '".$ftel2."', phone3 = '".$ftel3."', email = '".$femail1."', email2 = '".$femail2."', "
                .       "company = '".$fazienda."', tcm = '".$ftcm."', servizi = '".$fservizi."',"
                .       "pin = '".$fpin."', note = '".$fnote1."', note2 = '".$fnote2."', note3 = '".$fnote3."', scadenza_pin = '".$fscadenza."', tipo = '".$ftipo."' "
                .       "where id = '".$id_pb."'";
            
            $update_agg = $conn2->query($update);
            }
            } else { 
                    if ($errore=='') { 
                    $update = "INSERT INTO visual_phonebook (lastname, firstname, phone1, phone2, phone3, email, email2, company, pin, note, note2, note3, scadenza_pin, tcm, servizi, tipo) VALUES "
                    . "('".$fcognome."', '".$fnome."', '".$ftel1."', '".$ftel2."', '".$ftel3."', '".$femail1."', '".$femail2."', '".$fazienda."', '".$fpin."', '".$fnote1."', '".$fnote2."', '".$fnote3."', '".$fscadenza."', '".$ftcm."', '".$fservizi."', '".$ftipo."')";
                    $update_agg = $conn2->query($update);
                    
                    $wheretogo = $conn2->query("SELECT * FROM visual_phonebook ORDER BY id DESC LIMIT 1")->fetch_assoc();
                    $location = "Location: fop_dettagli.php?id_vispb=".$wheretogo["id"]."&pinpb=".$wheretogo["pin"];
                    }
                   }

        echo "<script type='text/javascript'>alert('Operazione completata')</script>";

        header($location);

        }


           
?>
        <?php         include('tech/navbar/navbarca.php'); ?>
        <div class="hit-the-floor">SCHEDA DETTAGLIO DELL'UTENTE FOP (ID <?php echo $_GET["id_vispb"] ?>)</div>
        
        <div style="height: 20px"></div>
        
        <form action="" method="post">
        
            <table id="tabellains" style="width: 95%; border: 1px solid black;">
            <tr>
                <td>Nome: </td><td><input type="text" name="f_nome" value="<?php echo $result["firstname"] ?>"></td>
                <td>Cognome: </td><td><input type="text" name="f_cognome" value="<?php echo $result["lastname"] ?>"></td>
                <td>Azienda: </td><td><input type="text" name="f_azienda" value="<?php echo $result["company"] ?>"></td>
            </tr>
            
            <tr>
                <td><a id='linkrnd' title='GENERA CASUALMENTE' href="#" onclick="random(); return false;"><img src='images/dado.png' width='24' hspace='10' title='GENERA CASUALMENTE'></a>PIN: </td><td><input id="f_pin" type="text" name="f_pin" value="<?php echo $result["pin"] ?>"></td>
                <?php if ($errore!='') { echo "<TD><div id='curvorosso'>".$errore."</div></TD>"; } ?>
                <td>Scadenza pin:</td><td><input type="date" name ="f_scadenza" value="<?php if ($result["scadenza_pin"] == '0000-00-00') { echo '--/--/----'; } else { echo $result["scadenza_pin"]; } ?>"></td>
                <td>Tipo:</td><td><select name ="f_tipo" hidden>
                        <option value="<?php echo $result['tipo'] ?>"><?php switch ($result['tipo']) {case "Converted": echo 'Cliente'; break; case "autorizzato": echo 'Persona Autorizzata'; break; case 'excliente': echo 'Ex-Cliente'; break;} ?></option>
                        <option value="" <?php if ($result['tipo'] == '') {echo 'hidden';}  ?>></option>
                        <option value="Converted">Cliente</option>
                        <option value="autorizzato">Persona Autorizzata</option>
                        <option value="excliente">Ex-Cliente</option>
                        <option value="ammin">Contatto Amministrativo</option>
                </td>
            </tr>
            
            <tr>
               <td>Telefono: </td><td><input type="text" name="f_tel1" value="<?php echo $result["phone1"] ?>"></td>
               <td>Altro telefono: </td><td><input type="text" name="f_tel2" value="<?php echo $result["phone2"] ?>"></td>
               <td>Altro telefono: </td><td><input type="text" name="f_tel3" value="<?php echo $result["phone3"] ?>"></td>
            </tr>
            
            <tr>
                <td>Email: </td><td><input type="text" name="f_email1" value="<?php echo $result["email"] ?>"></td>
                <td>Altra Email: </td><td><input type="text" name="f_email2" value="<?php echo $result["email2"] ?>"></td>
            </tr>

            <tr>
                <td>TCM/CODICI: </td><td><input type="text" name="f_tcm" value="<?php echo $result["tcm"] ?>"></td>
                <td>Servizi: </td><td><input type="text" name="f_servizi" value="<?php echo $result["servizi"] ?>"></td>
            </tr>

            <TR>
                <td>Note: </td><td colspan='5'><textarea name="f_note1" style="color:black;font-size: small;" cols="80" rows="2" ><?php echo $result["note"] ?></textarea></td>
            </TR>
            <TR>
                <td>Risposta: </td><td colspan='5'><textarea name="f_note2" style="color:black;font-size: small;" cols="80" rows="2" ><?php echo $result["note2"] ?></textarea></td>
            </TR>
            
            <TR>
                <td>Istruzioni: </td><td colspan='5'><textarea name="f_note3" style="color:black;font-size: small;" cols="80" rows="10" ><?php echo $result["note3"] ?></textarea></td>
            </TR>
            
            <TR><TD colspan="6" align='center'><input type="submit" name="salva" value="<?php if (isset($_GET["id_vispb"])) { echo 'AGGIORNA';} else { echo 'INSERISCI'; } ?>" style="color: #110e5e; background-color: rgba(255,255,255,.75); width: auto;font-weight: bold;"></td>
                
            </TR>
        </table>
        
        
        </form>
        
        <div id="curvochiaro"><a href="rubrica_fop.php">TORNA INDIETRO</a></div>
    
    
    </body>
    

    
    
    <?php $conn->close(); $conn2->close(); ?>
</html>