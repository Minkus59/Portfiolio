<?php
$cnx = new Connexion();
$SelectSocial=$cnx->Query("SELECT * FROM ".DB_PREFIX."Social WHERE statue='1' ORDER BY id ASC");
?>