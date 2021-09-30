<?php 
session_start();

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

header('Content-Type: text/html; charset=utf-8');

define("DB_HOST", "mysql:host=michaeeminkus.mysql.db;dbname=michaeeminkus");
define("DB_USER", "michaeeminkus");
define("DB_PWD", "Cqdfx301");

define("DB_PREFIX", "Portfolio__");

define("MAIL_SERVER", "michael.helinckx@hotmail.com");
define("MAIL_DESTINATAIRE", "michael.helinckx@hotmail.com");

define("SOCIETE", "Helinckx Michael");
define("PUBLISHER", "Helinckx Michael");

define("HOME", "http://".$_SERVER['SERVER_NAME']);

// spl_autoload_register(function($classe){
//     require($_SERVER['DOCUMENT_ROOT'].'/frontend/classes/'.$classe.'.class.php');
// });

$repIntOriginal=$_SERVER['DOCUMENT_ROOT']."/frontend/public//05456rh4564rh564trh54tr4hr5460thrhrhreh5ujolol4ui5uyui540ttgy7uy9u7kty/Document/Original/";
$repIntSigner=$_SERVER['DOCUMENT_ROOT']."/frontend/public//05456rh4564rh564trh54tr4hr5460thrhrhreh5ujolol4ui5uyui540ttgy7uy9u7kty/Document/Signer/";

$repIntJpgOriginal=$_SERVER['DOCUMENT_ROOT']."/frontend/public//05456rh4564rh564trh54tr4hr5460thrhrhreh5ujolol4ui5uyui540ttgy7uy9u7kty/Document/Jpg/Original/";
$repIntJpgSigner=$_SERVER['DOCUMENT_ROOT']."/frontend/public//05456rh4564rh564trh54tr4hr5460thrhrhreh5ujolol4ui5uyui540ttgy7uy9u7kty/Document/Jpg/Signer/";

$repExtOriginal=HOME."/frontend/public//05456rh4564rh564trh54tr4hr5460thrhrhreh5ujolol4ui5uyui540ttgy7uy9u7kty/Document/Original/";
$repExtSigner=HOME."/frontend/public//05456rh4564rh564trh54tr4hr5460thrhrhreh5ujolol4ui5uyui540ttgy7uy9u7kty/Document/Signer/";

$repExtJpgOriginal=HOME."/frontend/public//05456rh4564rh564trh54tr4hr5460thrhrhreh5ujolol4ui5uyui540ttgy7uy9u7kty/Document/Jpg/Original/";
$repExtJpgSigner=HOME."/frontend/public//05456rh4564rh564trh54tr4hr5460thrhrhreh5ujolol4ui5uyui540ttgy7uy9u7kty/Document/Jpg/Signer/";

$Signature=$_SERVER['DOCUMENT_ROOT']."/frontend/public//05456rh4564rh564trh54hrhrhreh5ujolol4ui5uyui540ttgy7uy9u7kty/Signature/signature-numerique.png";
$Tampon=$_SERVER['DOCUMENT_ROOT']."/frontend/public//05456rh4564rh564trh54hrhrhreh5ujolol4ui5uyui540ttgy7uy9u7kty/Signature/tampon-numerique.png";

$Signature_Ext=HOME."/frontend/public//05456rh4564rh564trh54hrhrhreh5ujolol4ui5uyui540ttgy7uy9u7kty/Signature/signature-numerique.png";
$Tampon_Ext=HOME."/frontend/public//05456rh4564rh564trh54hrhrhreh5ujolol4ui5uyui540ttgy7uy9u7kty/Signature/tampon-numerique.png";

$repIntTampon=$_SERVER['DOCUMENT_ROOT']."/frontend/public//05456rh4564rh564trh54hrhrhreh5ujolol4ui5uyui540ttgy7uy9u7kty/Tampon/";
$repExtTampon=HOME."/frontend/public//05456rh4564rh564trh54hrhrhreh5ujolol4ui5uyui540ttgy7uy9u7kty/Tampon/";
?>