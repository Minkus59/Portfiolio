<?php
require($_SERVER['DOCUMENT_ROOT']."/lib/script/fonction_perso.inc.php"); 
$Destinataire="contact@vivremobile-armentieres.fr";
//$Destinataire="contact@neuro-soft.fr";
$Home="http://vivremobile-armentieres.fr/";

if (isset($_POST['Valider'])) {

  $Marque=FiltreText('marque');
  $Model=FiltreText('model');
  $Cp=FiltreText('cp');                     
  $Nom=FiltreText('nom');
  $Tel=FiltreText('tel');
  $Email=FiltreEmail('email');
  $Descrip=FiltreText('descriptif');

  session_start();
  
  $boundary = md5(uniqid(mt_rand()));
  
  $Entete = "MIME-Version: 1.0\n";
  $Entete .= "Content-Type:multipart/mixed; boundary=\"$boundary\"\n";
  $Entete .= "From: \"Vivre Mobile\"<postmaster@vivremobile-armentieres.fr>\n";
  $Entete .= "Reply-to: \"Vivre Mobile\"<contact@vivremobile-armentieres.fr>\n";
  $Entete .= "\n";
  
  $Message_mail="Ce message est au format MIME.\n";
  
  $Message_mail.="--$boundary\n";
  $Message_mail.= "content-type: text/html; charset=iso-8859-1\n";

  $Message_mail.="\n";

  $Message_mail.="<html><head><title>Demande de devis</title></head><body>
  Message de ".$Nom.",<BR />
  Code postal : ".$Cp."<BR />
  E-mail : ".$Email."<BR />
  Téléphone : ".$Tel."</p>
  Marque : ".$Marque."<BR />
  Modèle : ".$Model."</p>
  Descriptif : ".$Descrip."</p>
  </body></html>";

  $Message_mail.="\n\n";

  $Message_mail.="--$boundary\n";

  if ($Marque===false) {
     $Erreur="Erreur !<BR />";
  }
  else {
  $_SESSION['marque']=$Marque;
  }
  if ($Model===false) {
     $Erreur="Erreur !<BR />";
  }  
  else {
  $_SESSION['model']=$Model;
  }
  if ($Cp===false) {
     $Erreur="Erreur !<BR />";
  }
  else {
  $_SESSION['cp']=$Cp;
  }
  if ($Nom===false) {
     $Erreur="Erreur !<BR />";
  }
  else {
  $_SESSION['nom']=$Nom;
  }
  if ($Tel===false) {
     $Erreur="Erreur !<BR />";
  }
  else {
  $_SESSION['tel']=$Tel;
  }
  if ($Email===false) {
     $Erreur="Erreur !<BR />";
  }
  else {
  $_SESSION['email']=$Email;
  }
  if ($Descrip===false) {
     $Erreur="Erreur !<BR />";
  }
  else {
  $_SESSION['descriptif']=$Descrip;
  }

  if (!isset($Erreur)) {
      if (!mail($Destinataire, "Demande de devis", $Message_mail, $Entete)) {
          $Erreur="L'e-mail n'a pu être envoyé, vérifiez que vous l'avez entré correctement !</p>";
          header("location:".$Home."?ErreurRepa=".$Erreur."#Reparation");
      }
      else {
          session_unset();
          session_destroy();
          $Erreur="Votre demande de rendez-vous à bien été enregistré, vous serez rappelé dans les meilleurs délais</p>";
          header("location:".$Home."?ValidRepa=".$Erreur."#Reparation");
      }
  }
  else {
     header("location:".$Home."?ErreurRepa=".$Erreur."#Reparation");
  }
}
else {
     header("location:".$Home."#Reparation");
}

?>