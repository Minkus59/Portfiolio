<?php 

require_once($_SERVER['DOCUMENT_ROOT']."/Admin/impinfbdd/config.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/fonction_perso.inc.php");  
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/redirect.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/requete.inc.php");


$Text=$_POST['text'];

echo '<form name="Form_Text" action="" method="POST">';
echo '<textarea class="InputTampon" name="text" onChange="submitFormText()">'.nl2br($Text).'</textarea>';
echo '</form>';
echo'test';
?>