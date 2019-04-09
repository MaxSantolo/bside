<?php
/**
 * Created by PhpStorm.
 * User: msantolo
 * Date: 19/10/2018
 * Time: 11:53
 */

$sql_fop_sync_ok = "select concat(firstname,'_',lastname,'_',email) as sync_id,
                           visual_phonebook.tipo, visual_phonebook.id
                    from   visual_phonebook
                    where  firstname != '' and lastname != '' and email != ''";

$sql_crm = "        select concat(first_name,'_',last_name,'_',email_address) as sync_id, id
                    from email_lead
                    where first_name != '' and last_name != '' and email_address != '' and primario = 1" ;

$fop_rows = $conn2->query($sql_fop);

while ($fop_row = $fop_rows->fetch_assoc()) {

    // echo $sql_crm. " AND sync_id = '".$fop_row['sync_id']."'";
    $crm_row = $conn_prod_crm->query($sql_crm. " AND concat(first_name,'_',last_name,'_',email_address) = '".$fop_row['sync_id']."'")->fetch_assoc();

    $sql2upd_crm = "UPDATE leads SET status = '".$fop_row['tipo']."' WHERE id = '".$crm_row['id']."'";
    $conn_prod_crm->query($sql2upd_crm);

    //echo $crm_row['id']." ".$sql2upd_crm."</BR>";



}