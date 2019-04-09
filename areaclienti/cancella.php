<?php
include ('../tech/connect.php');

if (isset($_GET['id_leadcrm']) && is_numeric($_GET['id_leadcrm'])) {
    $id = $_GET['id_leadcrm'];
    $conn->query("DELETE FROM acs_crm WHERE id = '".$id."'");
    $conn->query("DELETE FROM acs_crm_eventi WHERE id_utente = '".$id."'");
    header("Location: crm_lead_elenco.php");}


if (isset($_GET['id_eventocrm']) && isset($_GET['utente'])) {
    $id = $_GET['id_eventocrm'];
    $utente = $_GET['utente'];
    $conn->query("DELETE FROM acs_crm_eventi WHERE id_evento = '".$id."'");
    header("Location: crm_lead_dettagli.php?id_leadcrm=".$utente.""); }


if (isset($_GET['id_vispb']) ) {
    $id = $_GET['id_vispb'];
    $conn2->query("DELETE FROM visual_phonebook WHERE id = '".$id."'");
    header("Location: ../rubrica_fop.php?id_leadcrm=".$utente.""); }



$conn->close();
?>