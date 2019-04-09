<?php
/**
 * Created by PhpStorm.
 * User: msantolo
 * Date: 19/10/2018
 * Time: 10:32
 */

include '../connect.php';
include '../connect_prod.php';

$sql_crm = "select concat(first_name,'_',last_name,'_',email_address) as sync_id, id from email_lead where first_name != '' and last_name != '' and email_address != '' and primario = 1" ;
$sql_fop = "select concat(firstname,'_',lastname,'_',email) as sync_id, visual_phonebook.tipo, visual_phonebook.id, visual_phonebook.company  from visual_phonebook where firstname != '' and lastname != '' and email != '' and tipo NOT IN ('null','')";

$crm_rows = $conn_prod_crm->query($sql_crm);

$fop_rows = $conn2->query($sql_fop);

while ($fop_row = $fop_rows->fetch_assoc()) {

 // echo $sql_crm. " AND sync_id = '".$fop_row['sync_id']."'";

    $crm_row = $conn_prod_crm->query($sql_crm. " AND concat(first_name,'_',last_name,'_',email_address) = '".$fop_row['sync_id']."'")->fetch_assoc();
    $sql2upd_crm = "UPDATE leads SET status = '".$fop_row['tipo']."' WHERE id = '".$crm_row['id']."'";
    $conn_prod_crm->query($sql2upd_crm);

    //elimina azienda collegata da crm se excliente
    if ($fop_row['tipo'] == 'excliente') {

        $conn_prod_crm->query("UPDATE leads SET account_id = '' WHERE id = '".$crm_row['id']."'");
        $conn_prod_crm->query("UPDATE leads_cstm SET account_id_c = '' WHERE id_c = '".$crm_row['id']."'");
    }




}



