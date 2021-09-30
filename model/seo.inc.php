<?php
$cnx = new Connexion();
//Recup du referencement
$seo=$cnx->prepare("SELECT", "SELECT * FROM ".DB_PREFIX."_Page WHERE lien=:page", array(':page'=> $page));
?>