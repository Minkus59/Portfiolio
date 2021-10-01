<?php 
session_start();

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

header('Content-Type: text/html; charset=utf-8');

define("DB_HOST", "mysql:host=XXXXXX;dbname=michaeeminkus");
define("DB_USER", "michaeeminkus");
define("DB_PWD", "XXXXXX");

define("DB_PREFIX", "Portfolio__");

define("MAIL_SERVER", "michael.helinckx@hotmail.com");
define("MAIL_DESTINATAIRE", "michael.helinckx@hotmail.com");

define("SOCIETE", "Helinckx Michael");
define("PUBLISHER", "Helinckx Michael");

define("HOME", "http://".$_SERVER['SERVER_NAME']);

spl_autoload_register(function($classe){
    require($_SERVER['DOCUMENT_ROOT'].'/frontend/classes/'.$classe.'.class.php');
});
?>
