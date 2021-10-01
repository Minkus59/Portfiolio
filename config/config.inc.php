<?php 
header('Content-Type: text/html; charset=utf-8');

// define("DB_HOST", "mysql:host=XXXXXX;dbname=michaeeminkus");
// define("DB_USER", "michaeeminkus");
// define("DB_PWD", "XXXXXX");

define("DB_HOST", "mysql:host=localhost;dbname=michaeeminkus");
define("DB_USER", "root");
define("DB_PWD", "");

define("DB_PREFIX", "Portfolio__");

define("MAIL_SERVER", "michael-helinckx@hotmail.fr");
define("MAIL_DESTINATAIRE", "michael-helinckx@hotmail.fr");

define("SOCIETE", "Helinckx Michael");
define("PUBLISHER", "Helinckx Michael");

//define("HOME", "https://".$_SERVER['SERVER_NAME']);
define("HOME", "beta");

spl_autoload_register(function($classe){
    require ($_SERVER['DOCUMENT_ROOT'].'/classes/' .$classe. '.class.php');
});
?>
