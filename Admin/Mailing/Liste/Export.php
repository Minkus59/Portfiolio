<?php
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/impinfbdd/config.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/fonction_perso.inc.php");  
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/redirect.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/requete.inc.php");
$Select=$cnx->prepare("SELECT * FROM ".DB_PREFIX."mailing_Liste ORDER BY nom ASC");
$Select->execute();

$Ouverture = fopen("contact.csv", "w+");
fputcsv($Ouverture, array('nom', 'prenom', 'email'), ';');

while($AjoutListe=$Select->fetch(PDO::FETCH_OBJ)) {
    fputcsv($Ouverture, array($AjoutListe->nom, $AjoutListe->prenom, $AjoutListe->email), ';');
}
fclose($Ouverture);

header("Content-Type: application/force-download");
header('Content-Disposition: attachment; filename="contact.csv"');
header('Content-Length: '.  filesize('contact.csv'));
readfile('contact.csv');
?>