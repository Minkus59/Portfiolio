<?php
//Recup des articles de la page courante
$cnx = new Connexion();
$article = $cnx->Preparer("SELECT", "SELECT * FROM ".DB_PREFIX."Article WHERE page=:page AND statue='1' ORDER BY position ASC", 
array(':page'=>$page));
?>