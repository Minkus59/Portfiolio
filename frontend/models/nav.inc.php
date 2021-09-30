<?php
$cnx = new Connexion();
$Nbmenu=$cnx->Count("SELECT * FROM ".DB_PREFIX."Menu WHERE statue='1' AND sous_menu='0' ORDER BY position ASC");
$menu=$cnx->Query("SELECT * FROM ".DB_PREFIX."Menu WHERE statue='1' AND sous_menu='0' ORDER BY position ASC");
?>