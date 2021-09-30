<?php
$cnx = new Connexion();
//Recup du logo principal
$LogoHeader=$cnx->Query("SELECT * FROM ".DB_PREFIX."Logo WHERE id='2'");   
?>