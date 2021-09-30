<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/lib/script/fonction_perso.inc.php"); 
require_once($_SERVER['DOCUMENT_ROOT']."/lib/script/requete.inc.php");

if ((isset($_POST['Envoyer']))&&($_POST['Envoyer']=="Envoyer")) {

$Tel=FiltreTel('tel');
$Message=FiltreText('message');
$Email=FiltreEmail('email');

session_start();

  if ($Tel[0]===false) {
    $Erreur=$Tel[1]; 
  }  
  else {
    $_SESSION['tel']=$Tel;
  } 

  if ($Message[0]===false) {
    $Erreur=$Message[1];
  }  
  else {
    $_SESSION['message']=$Message;
  }  
         
  if ($Email[0]===false) {
    $Erreur=$Email[1]; 
  }    
  else {
    $_SESSION['email']=$Email;
  }  
  
  if (!isset($Erreur)) { 
  
    $Body="<H1>Demande de contact</H1>
    Message de : ".$Email."<BR />
    Tel : ".$Tel."<BR />
    <BR />
    Message : ".$Message."</p>";
      
    if (EnvoiNotification($Nom, $Email, "Demande de contact", $Body, $EmailDestinataire->email)==false) {
      $Erreur="L'e-mail n'a pu être envoyé, vérifiez que vous l'avez entré correctement !</p>";
      ErreurLog($Erreur);
      header("location:".$Home."?ErreurContact=".$Erreur."#Contact");
    }
    else {
      session_unset();
      session_destroy();
      $Erreur.="Votre message à bien été enregistré, il sera traité dans les meilleurs délais";
      header("location:".$Home."?ValidContact=".$Erreur."#Contact");
    }
  }
  else {
      header("location:".$Home."?ErreurContact=".$Erreur."#Contact");
  }
}   
?>