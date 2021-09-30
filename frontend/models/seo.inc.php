<?php
$cnx = new Connexion();
//Recup du referencement
$seo=$cnx->preparer("SELECT", "SELECT * FROM ".DB_PREFIX."Page WHERE lien=:page", array(':page'=> $page));
?>