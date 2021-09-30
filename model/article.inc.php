<?php
//Recup des articles de la page courante
$cnx = new Connexion();
$article = $cnx->Prepare("SELECT", "SELECT * FROM ".DB_PREFIX."_Article WHERE page=:page AND statue='1' ORDER BY position ASC", 
array(':page' => $page));
?>